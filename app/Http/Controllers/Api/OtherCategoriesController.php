<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\OtherCategory;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class OtherCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'type' => 'min:0|max:191'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', ['error' => $validator->errors()->all()]);
        }
        $type = $request->input('type');
        if ($type) {
            $otherCategory = OtherCategory::where('deleted_at', '=', null)
                ->where('type', '=', $type)->get();
        } else {
            $otherCategory = OtherCategory::where('deleted_at', '=', null)->get();
        }

        return ApiHelper::responseSuccess('List Item Category', ['item_category' => $otherCategory]);
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
            'name' => 'required|min:0:max:191',
            'description' => 'required|min:0|max:191',
            'type' => 'required|min:0|max:191',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $otherCategory = new OtherCategory();
            $otherCategory->fill($request->all());
            $otherCategory->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Other Category Item', ['item_category' => $otherCategory]);
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
        $otherCategory = OtherCategory::where('deleted_at', '=', null)
            ->where('id', '=', $id)->first();
        return ApiHelper::responseSuccess('Other Category Item Info', ['item_category' => $otherCategory]);
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
            'name' => 'min:0:max:191',
            'description' => 'min:0|max:191',
            'type' => 'min:0|max:191',
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $otherCategory = OtherCategory::find($id);
            $otherCategory->fill($request->all());
            $otherCategory->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created Other Category Item', ['item_category' => $otherCategory]);
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
            $otherCategory = OtherCategory::find($id);
            $otherCategory->deleted_at = new \DateTime();
            $otherCategory->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Other Category Item', ['item_category_id' => $otherCategory->id]);
    }
}
