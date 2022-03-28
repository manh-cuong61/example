<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CaptchaController extends Controller
{
    public function index(Request $request){
        $src = $request->img;
     
        return view('captcha');
    }
}
