<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
 use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;





class AuthController extends Controller
{
    public function register(Request $request){
        /* $validate = $request->validate(rules:[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email| max:255 | unique:users',
            'password' => 'required|string| min:6 | confirmed' */

            $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email| max:255 | unique:users',
            'password' => 'required|string| min:6 | confirmed'
        ]);

        if($validate->fails()){
            return response()->json(data: ['error' => $validate->errors()], status: 403);
        }   
        try{
            $user = User::create(attributes: [
                'name' => $request->name,
                'email' => $request->email,    
                'password' => Hash::make(value: $request->password),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(data: [
                'access_token' => $token,
                'user' => $user
            ], status: 200);    
        } 
       
        catch(\Exception $e){
            return response()->json(data: ['error' => $e->getMessage()], status: 422);
        }
    }

    // login 

    public function login(Request $request){
         $validate = Validator::make($request->all(),[
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if($validate->fails()){
            return response()->json(data: ['error' => $validate->errors()], status: 403);
        }   

        try {
            $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)){
                return response()->json(data: ['error' => 'The provided credentials are incorrect'], status: 403);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json(data: [
                'access_token' => $token,
                'user' => $user
            ], status: 200);    
        } 
       
        catch(\Exception $e){
            return response()->json(data: ['error' => $e->getMessage()], status: 422);
        }

    }

}

     

