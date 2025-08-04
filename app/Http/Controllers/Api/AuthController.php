<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function signup(Request $request){
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]
        );
        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validateUser->errors()->all()
            ], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user' => $user
        ], 200);
    }

    public function login(Request $request){
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]
        );

        if($validateUser->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Authentication Fail',
                'errors' => $validateUser->errors()->all()
            ], 404);
        }

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            $authUser = auth()->user();
            return response()->json([
                'status' => true,
                'message' => 'User Logged in successfully',
                'token' => $authUser->createToken("API Token")->plainTextToken,
                'token_type' => 'bearer'
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Email and password does not matched.',
            ], 401);
        }
    }

    public function logout(Request $request){
        $user = $request->user();
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'user' => $user,
            'message' => 'You logged out successfully.',
        ], 200);
    }
}
