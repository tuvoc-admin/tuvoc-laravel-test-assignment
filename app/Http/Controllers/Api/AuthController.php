<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', '=', $email)->first();
        if (!$user) {
            return response()->json(['success'=>false, 'message' => 'Login Fail, please check email id']);
        }
        if (! \Hash::check($password, $user->password)) {
            return response()->json(['success'=>false, 'message' => 'Login Fail, pls check password']);
        }

        if(empty($user->uuid))
        {
            //add uuid
            $currentUser = $user;
            $currentUser->uuid = $currentUser->id.'-'.Uuid::generate(5, $user->id, Uuid::NS_DNS);
            $saved  = $currentUser->save();
            return response()->json(['success'=>true,'message'=>'success', 'data' => $currentUser]);
        }
        return response()->json(['success'=>true,'message'=>'success', 'data' => $user]);
    }
}
