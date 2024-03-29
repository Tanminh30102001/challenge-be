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
        return User::orderByDesc('id')->get();
    }
    public function approveUser($id){
        return $this->userRepository->approveUser($id);
    }
    public function blockUser($id){
        return $this->userRepository->blockUser($id);
    }
    public function searchUser(Request $request){
        
        return $this->userRepository->searchUsers($request->search);
    }
    public function changePassword(Request $request,$id){
        $validator=UserValidator::changePass($request->all());
        if($validator->fails()){
            return response()->json(['error'=>$validator->errors()],400);
        }
        return $this->userRepository->changePass($request->all(),$id);
    }
    public function updateInfoUser(Request $request,$id){

        return $this->userRepository->updateUser($request->all(),$id);
    }
    public function getDetailsUser($id){
        return $this->userRepository->getDetailsUser($id);
    }
}
