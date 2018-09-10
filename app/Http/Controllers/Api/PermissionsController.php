<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiHelper;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use DB;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function index()
//    {
//        //
//        $permission = Permission::all();
//
//        return ApiHelper::responseSuccess('List Permission', ['permissions' => $permission]);
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function store(Request $request)
//    {
//        //
//        $permission = new Permission();
//        $permission->fill($request->all());
//
//        if ($permission->save()) {
//            return ApiHelper::responseSuccess('Created Permission', ['permission' => $permission]);
//        }
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param  int $id
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function show($id)
//    {
//        //
//        $permission = Permission::find($id);
//
//        return ApiHelper::responseSuccess('Permission Infomation', ['permission' => $permission]);
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request $request
//     * @param  int $id
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function update(Request $request, $id)
//    {
//        //
//        $permission = Permission::find($id);
//        $permission->fill($request->all());
//
//        if ($permission->save()) {
//            return ApiHelper::responseSuccess('Updated Permission', ['permission' => $permission]);
//        }
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int $id
//     * @return \Illuminate\Http\JsonResponse
//     * @throws \Exception
//     */
//    public function destroy($id)
//    {
//        //
//        try {
//            DB::beginTransaction();
//            $permission = Permission::find($id);
//            $permission->delete();
//            DB::commit();
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return ApiHelper::responseFail($e->getMessage(), $e->getTrace());
//        }
//        return ApiHelper::responseSuccess('Deleted Permission', ['permission_id' => $permission->id]);
//    }
}
