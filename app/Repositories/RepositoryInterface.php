<?php

namespace App\Repositories;

interface RepositoryInterface{
    public function register($users);
    public function login( $request);
    public function approveUser($id);
    public function blockUser($id);
    public function searchUsers($keyword);
    public function changePass($request,$id);
    public function updateUser($request, $id);
    public function getDetailsUser($id);
    
}