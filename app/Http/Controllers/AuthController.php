<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthController extends Controller
{
    public function login(Request $request){
        $field = filter_var($request->input('email'), FILTER_VALIDATE_EMAIL)
                ? "email" : "phone";

        $credentials = [
            $field => $request->email,
            'password' => $request->password,
        ];

        if(Auth::once($credentials)){
            $user = Auth::user();
            $user->load('roles');
            // return new UserResource($user);
            return response()->json([ 
                    'data' => new UserResource($user),
                    'access_token' => $user->createToken($user->name)->plainTextToken,
                    'token_type'   => 'Bearer'
                ]);                     
        }
        throw new HttpException(422, "Tên đăng nhập hoặc mật khẩu không chính xác!");
    }

    public function logout(){
            
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'data' => 'logout success'
        ]);
    }
}
