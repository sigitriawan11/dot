<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $rules = [
        "email" => "required|email",
        "password" => "required"
    ];

    public function login(Request $request){
        if($request->user()){
            return 'y';
        }
        $validator = Validator::make($request->all(), $this->rules);

        if($validator->fails()){
            return response()->json([
                'status' =>  false,
                'message' => $validator->errors()
            ]);
        }

        $validated = $validator->validated();

        $user = User::where('email', $validated['email'])->first();

        if(!$user){
            return response()->json([
                'status' =>  false,
                'message' => 'Email atau password salah'
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user
        ]);
    }
}
