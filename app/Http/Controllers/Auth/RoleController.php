<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('role-access')) {
            return response()->json(['message' => "You don't have permission",'permission' => 'role-access'], 403);
        }

        $users = Role::with('permissions')->get();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('role-create')) {
            return response()->json(['message' => "You don't have permission",'permission' => 'role-create'], 403);
        }
        //Validate data
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $role = Role::create([
        	'name' => $request->name,
        	'description' => $request->description,
            'timestamps' => false
        ]);

        $permissionsArray = explode(",",$request->perms);
        $permissionsIds = Permission::whereIn('name',$permissionsArray)->get()->pluck('id');
        $role->permissions()->sync($permissionsIds);
        $role->timestamps = false;
        $role->save();

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'Role created successfully',
            'data' => $role
        ], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        return response()->json($role, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! Gate::allows('role-update')) {
            return response()->json(['message' => "You don't have permission",'permission' => 'role-update'], 403);
        }

        $data = $request->only('name');

        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $role = Role::find($id);
        $permissionsArray = explode(",",$request->perms);
        $permissionsIds = Permission::whereIn('name',$permissionsArray)->get()->pluck('id');
        $role->permissions()->sync($permissionsIds);
        $role->timestamps = false;
        $role->update([ 'name' => $request->name, 'description' => $request->description]);
        return response()->json($role, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('role-delete')) {
            return response()->json(['message' => "You don't have permission",'permission' => 'role-update'], 403);
        }

        $role = Role::find($id);
        $role->delete();
        return response()->json($role, 200);
    }

    
}
