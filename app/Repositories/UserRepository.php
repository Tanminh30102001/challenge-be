<?php

namespace App\Repositories;

use App\Events\UserRegistered;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements RepositoryInterface
{
    public function register($users){
        event(new UserRegistered($users));
        return response()->json( ['message'=>'Register success'], 200);
    }
    public function login( $request) {
        // dd($request['password']);
        $user=User::where('username',$request['username'])->first();
       
        if( Hash::check($request['password'], $user->password) ){
            if($user->approve===1){
                return response()->json( ['user'=>$user], 200);
            }
            return response()->json( ['message'=>'Your account is not active'], 401);
        }
        return  response()->json(['message'=>'Wrong username or password'],401);
 
    }
}