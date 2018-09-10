<?php
/**
 * Created by PhpStorm.
 * User: DatPA
 * Date: 2/22/2018
 * Time: 8:54 AM
 */

namespace App\Http\Controllers\Api;


use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Employee;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AuthenticateController extends Controller
{

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Register new User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            $user = $this->user->create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password'))
            ]);
        } catch (\Exception $exception) {
            return ApiHelper::responseFail(__('messages.Tạo tài khoản thất bại'));
        }

        return ApiHelper::responseSuccess('User created sucessfully', ['user' => $user]);
    }

    /**
     * Login Action
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $token = null;
        try {
            if (!$token = \JWTAuth::attempt($credentials)) {
                return ApiHelper::responseFail(__('messages.email_password_invalid'));
            }
        } catch (JWTException $e) {
            return ApiHelper::responseFail(__('messages.create_token_fail'));
        }
        $user = \JWTAuth::toUser($token);
        $role = $user->roles()->where('name', 'like', 'admin')->first();
        if ($role == null) {
            $employee = $user->employee()->whereNull('deleted_at')->first();
            if ($employee == null) {
                return ApiHelper::responseFail(__('messages.wantsEmployee'));
            }
        }
        return ApiHelper::responseSuccess('Successfully', ['token' => $token]);
    }

    /**
     * Logout Action
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'integer|min:0|nullable'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $device_id = $request->input('device_id');
            if ($device_id != null) {
                $device = Device::whereNull('deleted_at')->find($device_id);
                if ($device != null) {
                    $device->delete();
                }
            }
            DB::commit();
            $token = $request->get('token');
            \JWTAuth::invalidate($token);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Logouted!', []);
    }

    /**
     * Get User Login Information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo(Request $request)
    {
        $user = \JWTAuth::toUser($request->token);
        if ($user) {
            $employee = Employee::where('user_id', '=', $user->id)->first();

            if ($employee) {
                $user->employee_id = $employee->id;
                $user->avatar = $employee->avatar;
            } else {
                $user->employee_id = null;
                $user->avatar = null;
            }
        }
        $role = $user->roles()->where('name', 'like', 'admin')
            ->orWhere('name', 'like', 'manager')->first();
        if ($role) {
            $user->is_admin = true;
        } else {
            $user->is_admin = false;
        }
        return ApiHelper::responseSuccess('result', ['user' => $user]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|max:191|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/',
        ], [
            'old_password.required' => 'Mật khẩu cũ không được bỏ trống',
            'new_password.required' => 'Mật khẩu mới không được bỏ trống',
            'new_password.max' => 'Mật khẩu mới quá dài',
            'new_password.regex' => 'Mật khẩu mới không đúng định dạng',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), [
                'error' => $validator->errors()->toArray()
            ]);
        }
        $oldPassword = $request->input('old_password');
        $newPassword = $request->input('new_password');
        if ($oldPassword === $newPassword) {
            return ApiHelper::responseClientFail('Mật khẩu mới không được trùng mật khẩu cũ', [
                'error' => [
                    'new_password' => [
                        'Mật khẩu mới không được trùng mật khẩu cũ'
                    ]
                ]
            ]);
        }
        $user = User::query()->find(\Auth::id());
        if ($user === null) {
            return ApiHelper::responseFail('Người dùng không tồn tại');
        }
        $isValidOldPassword = Hash::check($oldPassword, $user->password);
        if (!$isValidOldPassword) {
            return ApiHelper::responseClientFail('Mật khẩu cũ không đúng', [
                'error' => [
                    'old_password' => [
                        'Mật khẩu cũ không đúng'
                    ]
                ]
            ]);
        }

        try {
            DB::beginTransaction();
            $user->password = $newPassword;
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage());
        }
        return ApiHelper::responseSuccess('Đã đổi mật khẩu thành công', ['user' => $user]);
    }
}