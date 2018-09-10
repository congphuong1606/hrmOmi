<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Position;
use DB;
use Illuminate\Http\Request;
use Validator;

class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $position = Position::where('deleted_at', '=', null)->get();

        return ApiHelper::responseSuccess('List Positions', ['positions' => $position]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'code'=>'required',
            'name' => 'required',
            'description' => 'required'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', ['error' => $validator->errors()->messages()]);
        }
        try {
            DB::beginTransaction();
            $position = new Position();
            $position->fill($request->all());
            $position->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Position', ['position' => $position]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $position = Position::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Position Infomation', ['position' => $position]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        $position = Position::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($position == null) {
            return ApiHelper::responseClientFail('Position doesn\'t exist');
        }
        try {
            DB::beginTransaction();
            $position->fill($request->all());
            $position->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Position', ['position' => $position]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        //
        try {
            DB::beginTransaction();
            $position = Position::whereNull('deleted_at')->find($id);
            $employee = Employee::whereHas('position', function ($q) use ($id) {
                $q->where('id', '=', $id);
            })->first();
            if ($employee) {
                return ApiHelper::responseClientFail(__('messages.position_used_1'));
            }
            $position->deleted_at = new \DateTime();
            $position->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
        return ApiHelper::responseSuccess('Deleted Position', ['position_id' => $position->id]);
    }
}
