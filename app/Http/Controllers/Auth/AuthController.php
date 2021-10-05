<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use JWTAuth;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Register
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param Request - User data
     * @throws Error - If user data is invalid
     * @return JSON -  User data
     */
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('name', 'email', 'password', 'password_confirmation');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
        	'name' => $request->name,
        	'email' => $request->email,
        	'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
    }

    /**
     * Authenticate
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param Request - Username and Password to authenticate
     * @return JSON - JWT token or Error
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        //Request is validated
        //Clean token
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json([
                	'success' => false,
                	'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
    	    return $credentials;
            return response()->json([
                	'success' => false,
                	'message' => 'Could not create token.',
                ], 500);
        }
    
 		//Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
            //'user' => 
        ]);
    }

    /**
     * Logout
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param Request - Token of authenticated user
     * @return JSON - return JSON about Logout
     */
    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

		//Request is validated, do logout        
        try {
            JWTAuth::invalidate($request->token);
 
            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get User
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param Request $request - token for authentication
     * @return JSON User - Return Authenticated User JSON object
     */
    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
 
        $user = JWTAuth::authenticate($request->token);
 
        return response()->json(['user' => $user]);
    }

    /**
     * Refresh Token
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param Request
     * @return JSON
     */
    public function refreshToken(Request $request){
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return response()->json(['token' => $token]);
    }

    /**
     * Redirect To Provider
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param String $provider - Provider for socialite (google,github,facebook...)
     * @return String - returns URL of provider for login 
     */
    public function redirectToProvider($provider){       
        $validated  = $this->validateProvider($provider);
        if(!is_null($validated)){
            return $validated;
        }
        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

    /**
     * Handle Provider Callback
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param String $provider - Provider for socialite (google,github,facebook...)
     * @throws Error - If provider is not found or if credentials are invalid
     * @return String $token - JWT token for authentication
     */
    public function handleProviderCallback($provider){
        
        $validated  = $this->validateProvider($provider);
        if(!is_null($validated)){
            return $validated;
        }
        try{
            $user = Socialite::driver($provider)->stateless()->user();
        }catch(ClientException $exception){
            return response()->json(['error' => 'Invalid credentials provided.']);
        }

        $userCreated = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],
            [
                'email_verified_at' => now(),
                'name' => $user->getName(),
                'status' => true
            ]
        );
        $userCreated->providers()->updateOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $user->getId()
            ],
            [
                'avatar' => $user->getAvatar()
            ]
        );

        $token = JWTAuth::fromUser($userCreated);
        return view('oauth',['token' => $token]);
    }


    /**
     * Validate Provider
     * @author Srdjan Kordic <srdjank90@gmail.com>
     * @param String - Provider to check
     * @throws Error - if provider is not found throws error
     */
    protected function validateProvider($provider){
        if(!in_array($provider,['facebook','github','google'])){
            return response()->json(['error' => 'Please login using facebook, github or google']);
        }
    }



}
