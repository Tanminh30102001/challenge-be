<?php

namespace App\Repositories;

interface RepositoryInterface{
    public function register($users);
    public function login( $request);
}