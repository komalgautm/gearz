<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>\Hash::make($request->password)
        ]);
        $token = $user->createToken('Token')->accessToken;
        return response()->json(['token'=>$token,'user'=>$user],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function login(Request $request)
    {
        $data=[
            "email"=>$request->email,
            "password"=>$request->password
        ];
        if(auth()->attempt($data)){
            $token = auth()->user()->createToken('Token')->accessToken;
            return response()->json(['token'=>$token],200);
        }else{
            return response()->json(['error'=>"Unauthorize"],401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function userInfo(Request $request)
    {
        $user = auth()->user();
        return response()->json(['user'=>$user],200);
    }

    
}
