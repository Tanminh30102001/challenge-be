<?php

namespace App\Http\Controllers;

use App\Events\UserRegistered;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use App\Validators\UserValidator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;
    public function __construct(RepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;

    }
    public  function register(Request $request) {
        $validator = UserValidator::register($request->all());
        if ($validator->fails()) {
            return response()->json( $validator->errors(), 400);
        }
        return $this->userRepository->register($request->all());
    }
    public function login (Request $request){
        $validator = UserValidator::login($request->all());
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        return  $this->userRepository->login($request->all());
    }
    public function index(){
        return User::all();
    }
    public function approveUser($id){
        return $this->userRepository->approveUser($id);
    }

}
