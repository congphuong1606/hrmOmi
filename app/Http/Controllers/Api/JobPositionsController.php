<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\JobPosition;
use DB;
use Illuminate\Http\Request;

class JobPositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //
        $page = $request->input('page');
        $limit = $request->input('limit');

        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;

        $paginated_data = JobPosition::where('deleted_at', '=', null)
            ->paginate($limit, ['*'], 'page', $page);
        $jobPositions = array();
        foreach ($paginated_data as $item) {
            array_push($jobPositions, $item);
        }
        $data = [
            'job_positions' => $jobPositions,
            'pagination' => ['total' => $paginated_data->total()
                , 'per_page' => $paginated_data->perPage()
                , 'current_page' => $paginated_data->currentPage()
                , 'last_page' => $paginated_data->lastPage()]
        ];
        return ApiHelper::responseSuccess('List Job Position', $data);
    }

    /**
     * Store a newly created resource in storag e.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:0|max:191',
            'description' => 'required|min:0|max:191'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $jobPosition = new JobPosition();
            $jobPosition->fill($request->all());
            $jobPosition->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Job Position', ['job_position' => $jobPosition]);
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
        $jobPosition = JobPosition::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Job Status Infomation', ['job_status' => $jobPosition]);
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
        $validator = \Validator::make($request->all(), [
            'name' => 'min:0|max:191',
            'description' => 'min:0|max:191'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $jobPosition = JobPosition::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($jobPosition == null) {
            return ApiHelper::responseClientFail('Job Position doesn\'t exist');
        }
        try {
            DB::beginTransaction();
            $jobPosition->fill($request->all());
            $jobPosition->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Job Position', ['job_position' => $jobPosition]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        try {
            DB::beginTransaction();
            $jobPosition = JobPosition::whereNull('deleted_at')->find($id);
//            $employee = Employee::whereHas('position', function ($q) use ($id) {
//                $q->where('id', '=', $id);
//            })->first();
//            if ($employee) {
//                return ApiHelper::responseClientFail(__('messages.position_used_1'));
//            }
            $jobPosition->deleted_at = new \DateTime();
            $jobPosition->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Job Position', ['job_position_id' => $jobPosition->id]);
    }

}
