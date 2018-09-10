<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Screen;
use App\Models\ScreenCategory;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScreenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $screen = Screen::with('category')->whereNull('deleted_at')->get();
        return ApiHelper::responseSuccess('List Screen', ['screens' => $screen]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'url' => 'required|min:0|max:191',
            'screen_category_id' => 'required|integer',
            'name' => 'required',
            'description' => 'required',
            'display_name' => 'required',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail($validator->errors()->all());
        }
        $screen_category_id = $request->input('screen_category_id');
        $screen_category = ScreenCategory::find($screen_category_id);
        $name = $request->input('name');
        $screen = Screen::where('name', 'like', $name)->first();
        if ($screen_category == null) {
            return ApiHelper::responseClientFail('screen category doesn\'t exist');
        }
        if ($screen != null) {
            return ApiHelper::responseClientFail('screen name already exists');
        }
        try {
            DB::beginTransaction();
            $screen = new Screen();
            $screen->fill($request->all());
            $screen->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTraceAsString());
        }
        return ApiHelper::responseSuccess('Created Screen', ['screen' => $screen]);

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
        $screen = Screen::with('category')->whereNull('deleted_at')->find($id);

        return ApiHelper::responseSuccess('Screen Infomation', ['screen' => $screen]);
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
            'url' => 'min:0|max:191',
            'screen_category_id' => 'integer',
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail($validator->errors()->all());
        }
        $screen_category = ScreenCategory::find($request->input('screen_category_id'));
        if ($screen_category == null) {
            return ApiHelper::responseClientFail(__('messages.screen_category_exists_0'));
        }
        try {
            DB::beginTransaction();
            $screen = Screen::find($id);
            $screen->fill($request->all());
            $screen->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated Screen', ['screen' => $screen]);
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
        $screen = Screen::find($id);
        try {
            DB::beginTransaction();
            $screen->deleted_at = new \DateTime();
            $screen->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail('failed', [$e->getMessage() => $e->getTraceAsString()]);
        }
        return ApiHelper::responseSuccess('Deleted Screen', ['screen_id' => $screen]);
    }
}
