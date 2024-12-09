<?php

namespace App\Http\Controllers;

use App\apiResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    //
    use apiResponseTrait;
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return response()->json([
            'data' => $user,
            'msg' => 'user registration successful',
            'status' => 200,
        ]);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if(!$token = auth()->attempt($credentials)){
            return response()->json(['msg' => 'Unauthorized'], 401);
        }
        

        $success = $this->responseWithToken($token);

        return $this->apiResponse($success, 'user login successful', 200);
    }

    public function logout(){
        auth()->logout();

        return response()->json(['msg' => 'user logout successful'], 200);
    }

    public function refresh(){
        return $this->responseWithToken(auth()->refresh());
    }

    public function profile(){
        $user = auth()->user();

        return response()->json([
            'data' => $user,
            'msg' => 'user profile',
            'status' => 200,
        ]);
    }


    public function responseWithToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    public function test(RegisterRequest $request){

    }
}
