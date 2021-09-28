<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request): Response{
        $credentials = $request->only('email','password');
       
        if(Auth::attempt($credentials)){
            return response(Auth::user(),200);
        }

        abort(401);
    }

    public function logout(){
        Auth::logout();
        return response(null,200);
    }

    public function redirectToProvider($provider){
        Log::info($provider);
        /* return response()->json([
            'url' => Socialite::driver($provider)->stateless()->redirect()->getTargetUrl(),
        ]); */
       
        $validated  = $this->validateProvider($provider);
        if(!is_null($validated)){
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect()->getTargetUrl();
    }

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

        $avatar = '';
        $avatar = $user->getAvatar();

        $token = $userCreated->createToken('token-name')->plainTextToken;

        //return response()->json($userCreated,200,['Access-Token' => $token]);
        return view('oauth',['user' => $userCreated,'avatar' => $avatar,'token' => $token]);
    }

    protected function validateProvider($provider){
        if(!in_array($provider,['facebook','github','google'])){
            return response()->json(['error' => 'Please login using facebook, github or google']);
        }
    }
}
