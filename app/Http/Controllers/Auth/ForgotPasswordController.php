<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Send Password Reset Email
     * @param $request - Email to send change password
     * @throws Error
     * @return JSON
     */
    public function sendPasswordResetEmail(Request $request){
        // If email does not exist
        if(!$this->validEmail($request->email)) {
            return response()->json([
                'message' => 'Email "'.$request->email.'" does not exist in our database.'
            ], Response::HTTP_NOT_FOUND);
        } else {
            // If email exists
            $this->sendMail($request->email);
            return response()->json([
                'message' => 'Check your inbox, we have sent a link to reset your password.'
            ], Response::HTTP_OK);
        }
    }

    /**
     * Send Email
     * Send reset password email
     * @param $email
     */
    public function sendMail($email){
        $token = $this->generateToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token,$email));
    }

    /**
     * Valid Email
     * Check is email registered in database
     * @param String $email
     * @return User 
     */
    public function validEmail($email) {
       return !!User::where('email', $email)->first();
    }

    /**
     * Generate Token
     * @param String $email
     * @return String $token
     */
    public function generateToken($email){
      $isOtherToken = DB::table('password_resets')->where('email', $email)->first();

      if($isOtherToken) {
        return $isOtherToken->token;
      }
      
      $token = Str::random(80);
      $this->storeToken($token, $email);
      return $token;
    }

    /**
     * Store Token
     * @param String $token
     * @param String $email
     */
    public function storeToken($token, $email){
        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()            
        ]);
    }
}
