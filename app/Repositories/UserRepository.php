<?php

namespace App\Repositories;

use App\Events\UserRegistered;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements RepositoryInterface
{
    public function register($users){
        
        dd($users);
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
    public function approveUser($id){
        $user = User::findOrFail($id);
        $user->update([ 'approve'=>1 ]);
        return  $user;
    }
    public function searchUsers($keyword){
        
        $user=User::where('username','like','%'. $keyword. '%')->get();
        return $user;
    }
    public function changePass($request,$id){
        $user=User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        if (!Hash::check($request['current_password'], $user->password)) {
            return response()->json(['message' => 'curent pasword not same new password'], 400);
        }
        $user->password = Hash::make($request['new_password']);
        $user->save();
        return response()->json(['message' => 'Change password successfully'], 200);
        
    }
}