<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ChangePasswordController extends Controller
{
    public function passwordResetProcess(UpdatePasswordRequest $request){
        return $this->updatePasswordRow($request)->count() > 0 ? $this->resetPassword($request) : $this->tokenNotFoundError();
    }

    /**
     * Update Password Row
     * Verify is token valid
     * @param request
     * @return password_resets
     */
    private function updatePasswordRow($request){
        return DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->passwordToken
        ]);
    }

    /**
     * Token Not Found Error
     * @throws Error
     * @return JSON
     */
    private function tokenNotFoundError() {
        return response()->json([
            'error' => 'Either your email or token is wrong.'
        ],Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Reset Password
     * Update password for user in database
     * @param $request
     */
    private function resetPassword($request) {
        // find email
        $userData = User::where('email',$request->email)->first();
        // update password
        $userData->update([
            'password'=>bcrypt($request->password)
        ]);
        // remove verification data from db
        $this->updatePasswordRow($request)->delete();

        // reset password response
        return response()->json([
            'message'=>'Password has been updated.'
        ],Response::HTTP_CREATED);
    }    
}
