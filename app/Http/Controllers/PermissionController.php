<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $permissions = Permission::all()->pluck('name');
        return response()->json($permissions,200);
    }

    public function permissionIdsFromNames(Request $request){
        $permissionNames = json_decode($request->names);
        $permissionIds = Permission::whereIn('name',$permissionNames)->get()->pluck('id');
        return response()->json($permissionIds,200);
    }

    public function permissionNamesFromIds(Request $request){
        $permissionIds = explode(",",$request->ids);
        $permissionNames = Permission::whereIn('id',$permissionIds)->get()->pluck('name');
        return response()->json($permissionNames,200);
    }
}
