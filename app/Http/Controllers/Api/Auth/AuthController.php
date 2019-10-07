<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\ApiTokenController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($request->password, $user->password)) {

            $token = Str::random(60);
//            $user->api_token =  $token;
            $user->api_token = hash('sha256', $token);
            $user->save();
            return response()->json([
                'token' => $user->api_token,
                'message' => 'خوش آمدید'
            ], 200);
        }

    }

    public function store(Request $request)
    {
            $user = new User();
            $user->name = $request->name;
            $user->email=$request->email;
            $user->password = Hash::make($request->password);

            $user->save();

            return response()->json([
                'message'=>'ثبت نام موفقیت امیز بود b'
            ]);
    }


}








