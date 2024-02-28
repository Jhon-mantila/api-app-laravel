<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    //
    public function login(Request $request){
        
        $this->validateLogin($request);

        
       $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return response()->json([
            'token'=>$user->createToken($request->name, ['*'], now()->addWeek())->plainTextToken,
            'expiration' => now()->addWeek(),
            'Message' => 'Succes'
        ]);
        
        

        /*$credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)){
            return response()->json([
                'token'=> $request->user()->createToken($request->name, ['*'], now()->addWeek())->plainTextToken,
                'expiration' => now()->addWeek(),
                'Message' => 'Succes'
            ]);
        }

        return response()->json([
            'message' => 'Unauthorized sadsdasd'
        ], 401);*/
      
    }

    public function validateLogin(Request $request){
        return  $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'name' => 'required',
        ]);
    }
}
