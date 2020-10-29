<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Illuminate\Routing\Route;

class ApiLoginController extends Controller
{
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //why do i even need a constructor here bruh?

    // public function __construct(Request $request){

    //     $oauth_client_id = env('PASSPORT_CLIENT_ID');
    //     // $oauth_client = OauthClients::findOrFail($oauth_client_id); need to make model for that
    //     $oauth_client = 3;

    //     $request->request->add([
    //         'email' => $request->username,
    //         'client_id' => $oauth_client_id,
    //         // 'client_secret' => $oauth_client->secret
    //         'client_secret' => 'b2jRzKUiDqYCWYc5ZFOAQZ1JMslsWKqSmS2PZSRv'
    //         ]);
    // }

        public function register(Request $request)
        {

            // $validatedData = $request->validate([
            //     'name' => 'required|max:55',
            //     'email' => 'email|required|unique:users',
            //     'password' => 'required|confirmed'
            // ]);

            $validatedData['password'] = bcrypt($request->password);
            $validatedData['name'] = $request->name;
            $validatedData['email'] = $request->email_address;

            $user = User::create($validatedData);

            $accessToken = $user->createToken('authToken')->accessToken;

            return response([ 'user' => $user, 'access_token' => $accessToken]);
        }

        public function login(Request $request)
        {

            // $loginData = $request->validate([
            //     'email' => 'email|required',
            //     'password' => 'required'
            // ]);

            $loginData['email'] = $request->email_address;
            $loginData['password'] = $request->password;

            if (!auth()->attempt($loginData)) {
                return response(['message' => 'Invalid Credentials']);
            }

            $accessToken = auth()->user()->createToken('authToken')->accessToken;

            return response(['user' => auth()->user(), 'access_token' => $accessToken]);

            $tokenRequest = Request::create(
                '/oauth/token',
                'post'
            );

            return app()->handle($tokenRequest);

        }
}
