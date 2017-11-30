<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function allfriends(Request $request){
        $credentials = $request->only(['email', 'password']);
        if (Auth::once($credentials)) {
            $email = $request->get("email");
            $dataArray = User::where("email", $email)
                ->first()
                ->friends()
                ->join("users", "friends.receiver_id", "=", "users.id")
                ->get();
            return response()->json($dataArray, 200);
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }
}
