<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Screen;
use App\Models\ScreenCategory;
use DB;
use Illuminate\Http\Request;
use Validator;

class ScreenCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $screenCategories = ScreenCategory::all();

        return ApiHelper::responseSuccess('List Screen Category', ['screen_categories' => $screenCategories]);
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
            'name' => 'required',
            'display_name' => 'required',
            'description' => 'required'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validate', ['error' => $validator->errors()->messages()]);
        }
        $screenCategory = new ScreenCategory();
        $screenCategory->fill($request->all());

        if ($screenCategory->save()) {
            return ApiHelper::responseSuccess('Created Screen Category', ['screen_category' => $screenCategory]);
        }
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
        $screenCategory = ScreenCategory::find($id);

        return ApiHelper::responseSuccess('Screen Category Infomation', ['screen_category' => $screenCategory]);
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
        $screenCategory = ScreenCategory::find($id);
        $screenCategory->fill($request->all());

        if ($screenCategory->save()) {
            return ApiHelper::responseSuccess('Updated Screen Category', ['screen_category' => $screenCategory]);
        }
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
            $screenCategory = ScreenCategory::find($id);
            $screenCategory->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted Screen Category', ['screen_category_id' => $screenCategory->id]);
    }

    public function getAllCategoryInfo()
    {
        $screenCategories = ScreenCategory::with(['screens' => function ($query) {
            $query->whereNull('deleted_at');
        }])->whereHas('screens')->whereNull('deleted_at')->get();
        return ApiHelper::responseSuccess('List Screen Category', ['screen_categories' => $screenCategories]);
    }
}
