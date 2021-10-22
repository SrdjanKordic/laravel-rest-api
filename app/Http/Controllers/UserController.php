<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Image;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('USER_ACCESS')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'USER_ACCESS'], 403);
        }

        $users = User::with('role')->get();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (! Gate::allows('USER_CREATE')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'USER_CREATE'], 403);
        }
        //Validate data
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password),
            'role_id' => 2
        ]);

        //User created, return success response
        return response()->json($user, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::with('role')->find($id);
        return response()->json($user, 200);
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
       

        if (! Gate::allows('USER_UPDATE')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'USER_UPDATE'], 403);
        }

        $data = $request->only('name', 'sex','dob','country','state','city','address','phone','instagram','facebook','twitter','linkedin','github','youtube');

        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::find($id);
        $user->update($request->all());
        return response()->json($user, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('USER_DELETE')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'USER_DELETE'], 403);
        }
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json($user, 200);
    }

    /**
     * Change password
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request){
        $request->validate([
            'current_password' => ['required', 'current_password' ],
            'new_password' => ['required','min:6'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
        return response()->json('',200);
    }

    /**
     * Upload Avatar
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function uploadAvatar(Request $request){
        $validator = Validator::make($request->all(),[ 
            'avatar' => 'required|image|max:2048',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::findOrFail($request->id);
        if(Storage::disk('public')->exists($user->avatar)){
            Storage::disk('public')->delete($user->avatar);
        }

        $img = Image::make($request->file('avatar')->getRealPath());


        $file = $request->file('avatar');
        $name = '/uploads/avatars/' . $request->id . '-' .uniqid() . '-avatar-name.' . $file->extension();
        $file->storePubliclyAs('public', $name);

        Image::make(storage_path('app/public/' . $name))
        ->fit(150, 150)
        ->save(storage_path('app/public/' . $name));

        
        $user->avatar = $name;
        $user->save();
            
        return response()->json($name, 200);
    }
}
