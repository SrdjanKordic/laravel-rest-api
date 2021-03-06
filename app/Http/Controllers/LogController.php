<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! Gate::allows('LOGS_ACCESS')) {
            return response()->json(['message' => "You don't have permissions to access this route",'permission' => 'USER_ACCESS'], 403);
        }
        $orderBy = $request->input('orderBy') ? $request->input('orderBy') : 'id';
        $direction = $request->input('direction') ? $request->input('direction') : 'asc';
        $logs = Log::with('user')->orderBy($orderBy,$direction)->paginate(10);
        return $logs;
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
        //Validate data
        
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'operation' => 'required|string',
            'description' => 'required|string',
            'subject_type' => 'required|string',
            'subject_id' => 'required|integer',
            'causer_type' => 'required|string',
            'causer_id' => 'required|integer',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        //Request is valid, create new user
        $log = Log::create($request->all());

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
