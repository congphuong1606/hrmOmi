<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\JobPosition;
use App\Models\SpecializedSkill;
use DB;
use Illuminate\Http\Request;
use Validator;

class SpecializedSkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //
        $page = $request->input('page');
        $limit = $request->input('limit');

        $page = $page == null ? DEFAULT_DISPLAY_PAGE : $page;
        $limit = $limit == null ? DEFAULT_PAGE_LIMIT : $limit;

        $paginated_data = SpecializedSkill::where('deleted_at', '=', null)
            ->paginate($limit, ['*'], 'page', $page);
        $jobPositions = array();
        foreach ($paginated_data as $item) {
            array_push($jobPositions, $item);
        }
        $data = [
            'specialized_skills' => $jobPositions,
            'pagination' => ['total' => $paginated_data->total()
                , 'per_page' => $paginated_data->perPage()
                , 'current_page' => $paginated_data->currentPage()
                , 'last_page' => $paginated_data->lastPage()]
        ];
        return ApiHelper::responseSuccess('List Specialized Skill',$data);
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
            'name' => 'required|min:0|max:191',
            'description' => 'required|min:0|max:191',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $skill = new SpecializedSkill();
            $skill->fill($request->all());
            $skill->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Specialized Skill', ['specialized_skill' => $skill]);
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
        $skill = SpecializedSkill::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();

        return ApiHelper::responseSuccess('Specialized Skill Information', ['specialized_skill' => $skill]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'min:0|max:191',
            'description' => 'min:0|max:191',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseFail('Error Validation', ['error' => $validator]);
        }
        $skill = SpecializedSkill::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($skill == null) {
            return ApiHelper::responseClientFail('Specialized Skill doesn\'t exist');
        }
        try {
            DB::beginTransaction();
            $skill->fill($request->all());
            $skill->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Specialized Skill', ['specialized_skill' => $skill]);

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
        $skill = SpecializedSkill::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        if ($skill == null) {
            return ApiHelper::responseClientFail('Specialized Skill doesn\'t exist');
        }
        try {
            DB::beginTransaction();
            $skill->deleted_at = new \DateTime();
            $skill->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Specialized Skill', ['specialized_skill_id' => $skill->id]);
    }
}
