<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiHelper;
use App\Helpers\MailHelper;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\PasswordForget;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Validator;

class UsersController extends Controller
{
    /**
     * List User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request)
    {
        $limit = $request->input('limit');
        $page = $request->input('page');
        $searchData = $request->input('search_data');

        $paginatedData = null;

        $searchedUser = User::query();
        if ($searchData) {
            $searchedUser->with('employee')->where('email', 'like', "%$searchData%")
                ->orWhereHas('employee', function ($q) use ($searchData) {
                    $q->where('employee_code', 'like', "%$searchData%");
                });

        }
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $paginatedData = $searchedUser->paginate($limit, ['*'], 'page', $page);
        foreach ($paginatedData as $user) {
            $employeeCode = null;
            if ($user->employee) {
                $employeeCode = $user->employee->employee_code;
            }
            unset($user->employee);
            $user->employee_code = $employeeCode;
        }
        $data = [
            'users' => $paginatedData->items(),
            'pagination' => ['total' => $paginatedData->total(),
                'per_page' => $paginatedData->perPage(),
                'current_page' => $paginatedData->currentPage(),
                'last_page' => $paginatedData->lastPage()
            ]
        ];
        return ApiHelper::responseSuccess('Paginate List User ', $data);
    }


    /**
     * Create New User
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        if ($user->save()) {
            return ApiHelper::responseSuccess('Created User', ['user' => $user]);
        }
    }

    /**
     * View User Information
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        return ApiHelper::responseSuccess('User Information', ['user' => $user]);
    }

    /**
     * Update User Information
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $user = User::find($id);
        $user->fill($request->all());

        if ($user->save()) {
            return ApiHelper::responseSuccess('Updated User Information', ['user' => $user]);
        }
    }

    /**
     * Delete User
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $user = User::find($id);
            $user->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted User', ['user_id' => $user->id]);

    }

    /**
     * List User Role
     *
     * @param Request request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRole($id)
    {
        $role = User::find($id)->roles()->get();

        return ApiHelper::responseSuccess(' List User Role', ['roles' => $role]);
    }

    /**
     * Grant User Role
     * @param Request request
     * @return \Illuminate\Http\JsonResponse
     */
    public function attachRole($user_id, $role_id)
    {
        User::find($user_id)->roles()->attach($role_id);
        return ApiHelper::responseSuccess('Attached Role', ['role_user' => ['user_id' => $user_id, 'role_id' => $role_id]]);
    }

    /**
     * Delete User Role
     * @param Request request
     * @return \Illuminate\Http\JsonResponse
     */
    public function detachRole($user_id, $role_id)
    {
        User::find($user_id)->roles()->detach($role_id);

        return ApiHelper::responseSuccess('Detached Role', ['role_user' => ['user_id' => $user_id, 'role_id' => $role_id]]);
    }

    /**
     *
     * @param Request request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserScreenUrl($id)
    {
        $user = User::whereHas('employee', function ($query) {
            $query->whereNull('deleted_at');
        })->find($id);
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $roles = $user->roles()->get();
        $urls = array();
        foreach ($roles as $role) {
            $screens = $role->screens()->whereNull('deleted_at')->get();
            foreach ($screens as $screen) {
                if ($screen) {
                    array_push($urls, $screen->url);
                }
            }
        }
        $urls = array_unique($urls);
        return ApiHelper::responseSuccess('List Url', ['urls' => array_values($urls)]);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserNews(Request $request)
    {
        $user = \JWTAuth::toUser($request->token);
        $news = $user->news()->get();
        return ApiHelper::responseSuccess('List News', ['news' => $news]);
    }

    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserCourses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'own' => 'required|boolean',
            'completed' => 'boolean'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validator->errors()]);
        }
        $page = $request->input('page');
        $limit = $request->input('limit');
        $own = $request->input('own');
        $completed = $request->input('completed');
        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;
        $user = \JWTAuth::toUser($request->token);
        $user_id = $user->id;
        $paginatedData = Course::with('sessions', 'otherCategory')
            ->whereNull('deleted_at')
            ->whereHas('otherCategory')
            ->where(function ($query) use ($user_id, $own) {
                if ($own == true) {
                    $query->whereHas('sessions', function ($query) use ($user_id) {
                        $query->whereNull('deleted_at');
                        $query->whereHas('users', function ($query) use ($user_id) {
                            $query->where('id', '=', $user_id);
                        });
                    });
                } else {
                    $query->whereDoesntHave('sessions', function ($query) use ($user_id) {
                        $query->whereHas('users', function ($query) use ($user_id) {
                            $query->where('id', '=', $user_id);
                        });
                    });
                }
            })->get();
        $courses = array();
        foreach ($paginatedData as $course) {
            $sessions = $course->sessions;
            $sessionsData = array();
            $requiredSessionsNumber = 0;
            $status = true;
            $training = $course->training()->whereHas('user')->where('user_id', '=', $user_id)->first();
            foreach ($sessions as $session) {
                $user = $session->users()->find($user_id);
                $requiredSessionsNumber = $user == null ? $requiredSessionsNumber : $requiredSessionsNumber + 1;
                $sessionUser = $user == null ? null : $user->sessions()->find($session->id);
                $sessionUserPivot = $sessionUser == null ? null : $sessionUser->pivot;
                $requiredSession = $user == null ? false : true;
                $presence = $sessionUserPivot == null ? false : $sessionUserPivot->presence;
                if ($status) {
                    $status = $requiredSession == $presence ? true : false;
                }
                array_push($sessionsData, [
                    'id' => $session->id,
                    'start_datetime' => $session->start_datetime,
                    'end_datetime' => $session->end_datetime,
                    'trainer' => ApiHelper::getNameFromEmail($session->trainer, true),
                    'supporter' => ApiHelper::getNameFromEmail($session->supporter, false),
                    'content' => $session->content,
                    'required_session' => $requiredSession,
                    'presence' => $presence,
                ]);

            }
            $start = $course->sessions()->orderBy('start_datetime')->first();
            $start_date = $start != null ? date('d-m-Y H:i', strtotime($start['start_datetime'])) : '';
            $end = $course->sessions()->orderBy('end_datetime', 'desc')->first();
            $end_date = $end != null ? date('d-m-Y H:i', strtotime($end['end_datetime'])) : '';

            $sessions_number = count($sessions);
            $session = $course->sessions()->first();
            $room = $session != null ? $session->room()->first()['name'] : '';
            $data = [
                'id' => $course->id,
                'course_name' => $course->otherCategory->name,
                'room_name' => $room,
                'sessions_number' => $sessions_number,
                'required_session_number' => $requiredSessionsNumber,
                'description' => $course->description,
                'current_order' => $course->current_order,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'status' => $status,
                'score' => $training == null ? null : $training->score . '',
                'sessions' => $sessionsData,
            ];
            if ($completed == null || $status == $completed) {
                array_push($courses, $data);
            }
        }
//        usort($courses, function ($a, $b) {
//            return strtotime($b['end_date']) - strtotime($a['end_date']);
//        });
        $actualCcourses = array();
        $startIndex = $limit * ($page - 1);
        $endIndex = $limit * $page - 1;
        $currentIndex = 0;
        foreach ($courses as $course) {
            if ($currentIndex >= $startIndex && $currentIndex <= $endIndex) {
                array_push($actualCcourses, $course);
            }
        }
        $data = [
            'courses' => $actualCcourses,
            'pagination' => [
                'total' => count($actualCcourses),
                'per_page' => $limit,
                'current_page' => $page,
                'last_page' => count($courses) * 1.0 / $limit <= 1 ? 1 : count($courses) / $limit + 1,
            ]
        ];
        return ApiHelper::responseSuccess('List Course', $data);
    }

    public function checkEmail(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|string|min:0|max:191'
        ]);
        if (!$validation->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validation->errors()->all()]);
        }
        $email = $request->input('email');
        $users = User::where('email', 'like', "$email%")
            ->whereHas('employee', function ($query) {
                $query->whereNull('deleted_at');
            })->get();
        if (count($users) == 1) {
            return ApiHelper::responseSuccess('User', ['user' => $users[0]]);
        }
        return ApiHelper::responseFail(__('messages.user_exists_0'));
    }

    public function findEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email', 'string|nullable'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail(__('messages.validation_0'));
        }
        $email = $request->input('email');
        $results = array();
        $users = User::where('email', 'like', "$email%")
            ->orWhere('email', 'like', "%$email%")
            ->limit(10)
            ->get();
        foreach ($users as $user) {
            array_push($results, [
                'email' => $user->email,
                'name' => $user->name,
            ]);
        }
        return ApiHelper::responseSuccess('List User', ['users' => $results]);
    }

    public function sendMailForgetPassword(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);
        if (!$validation->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validation->errors()->all()]);
        }
        $email = $request->input('email');
        $user = User::where('email', '=', $email)->first();
        if ($user == null) {
            return ApiHelper::responseClientFail(__('messages.user_exists_0'));
        }
        $verified_code = null;
        $passwordForget = PasswordForget::where('email', '=', $email)->first();
        try {
            DB::beginTransaction();
            if ($passwordForget != null) {
                $verified_code = $passwordForget->verified_code;
                $passwordForget->created_at = new \DateTime();
                $passwordForget->save();
                \Log::info('$passwordForget != null');
            } else {
                $verified_code = ApiHelper::generateRandomString(8);
                $passwordForget = new PasswordForget();
                $passwordForget->email = $email;
                $passwordForget->verified_code = $verified_code;
                $passwordForget->save();
                \Log::info('$passwordForget == null');
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseClientFail($e->getMessage());
        }
        MailHelper::sendMailForgetPassword($email, $verified_code);
        return ApiHelper::responseSuccess(__('messages.password_forget'));
    }

    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @uses Change password with received code from email
     */
    public function verifyCodeFromEmail(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'verified_code' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);
        if (!$validation->passes()) {
            return ApiHelper::responseFail(__('messages.validation_0'), ['error' => $validation->errors()->all()]);
        }
        $email = $request->input('email');
        $verified_code = $request->input('verified_code');
        $newPassword = $request->input('password');
        $passwordForget = PasswordForget::where('email', '=', $email)
            ->where('verified_code', '=', $verified_code)
            ->first();
        if ($passwordForget != null) {
            $now = (new \DateTime())->format('Y-m-d H:i:s');
            $created_at = $passwordForget->created_at;
            if (strtotime($now) - strtotime($created_at) < 1800) {
                try {
                    DB::beginTransaction();
                    $user = User::where('email', '=', $email)->first();
                    $user->password = $newPassword;
                    $user->save();
                    PasswordForget::where('email', '=', $email)
                        ->where('verified_code', '=', $verified_code)
                        ->delete();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                }
            } else {
                return ApiHelper::responseClientFail(__('messages.password_forget_verified_code'));
            }
            return ApiHelper::responseSuccess('Success', ['email' => $email]);
        }
        return ApiHelper::responseFail('Mã xác nhận không hợp lệ');
    }
}
