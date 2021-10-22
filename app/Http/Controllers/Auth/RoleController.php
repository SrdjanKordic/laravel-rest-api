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
        if (! Gate::allows('ROLE_ACCESS')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'ROLE_ACCESS'], 403);
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
        if (! Gate::allows('ROLE_CREATE')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'ROLE_CREATE'], 403);
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
        return response()->json($role, 200);
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
        if (! Gate::allows('ROLE_UPDATE')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'ROLE_UPDATE'], 403);
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
        if (! Gate::allows('ROLE_DELETE')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'ROLE_DELETE'], 403);
        }

        $role = Role::find($id);
        $role->delete();
        return response()->json($role, 200);
    }

    
}
