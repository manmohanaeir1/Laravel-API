<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
 use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;





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
}

     

