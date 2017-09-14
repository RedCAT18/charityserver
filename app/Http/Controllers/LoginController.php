<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    //
    public function authenticate(Request $request){
//        return $request->all();
        $credentials = $request->only('login_id', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch(JWTException $e){
            return response()->json(['error'=> 'Could not create token.']);
        }

        return response()->json(['success'=>1, 'token' => $token]);
    }
}
