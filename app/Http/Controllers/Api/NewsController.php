<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Models\News;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            'title' => 'required|min:0|max:191',
            'link' => 'required|min:0|max:191',
            'description' => 'min:0|max:191',
            'scope' => 'integer|min:1|max:10'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $news = new News();
            $news->fill($request->all());
            $news->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Created News', ['news' => $news]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * update the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'title' => 'min:0|max:191',
            'link' => 'min:0|max:191',
            'description' => 'min:0|max:191',
            'scope' => 'integer|min:1|max:10'
        ]);
        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $news = News::where('deleted_at', '=', null)
                ->where('id', '=', $id)->first();
            $news->fill($request->all());
            $news->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Updated News', ['news' => $news]);
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
            $news = News::where('deleted_at', '=', null)
                ->where('id', '=', $id)->first();
            $news->deleted_at = new \DateTime();
            $news->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
        }
        return ApiHelper::responseSuccess('Deleted News', ['news' => $news->id]);
    }

    public function attachUsers(Request $request, $news_id)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required',
            'user_ids.*' => 'integer'
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $news = News::find($news_id);
        if ($news == null) {
            return ApiHelper::responseClientFail('The news doesn\'t exists');
        }
        $user_ids = $request->input('user_ids');
        foreach ($user_ids as $user_id) {
            $news->users()->sync($user_id, false);
        }
        return ApiHelper::responseSuccess('Attached Users', ['user_ids' => $user_ids]);
    }
    public function detachUsers(Request $request, $news_id)
    {
        $validator = Validator::make($request->all(), [
            'user_ids' => 'required',
            'user_ids.*' => 'integer'
        ]);

        if (!$validator->passes()) {
            return ApiHelper::responseClientFail('Error Validation', ['error' => $validator->errors()->all()]);
        }
        $news = News::find($news_id);
        if ($news == null) {
            return ApiHelper::responseClientFail('The news doesn\'t exists');
        }
        $user_ids = $request->input('user_ids');
        foreach ($user_ids as $user_id) {
            $news->users()->detach($user_id);
        }
        return ApiHelper::responseSuccess('Detached Users', ['user_ids' => $user_ids]);
    }
}
