<?php 
// app/Validators/UserValidator.php
namespace App\Validators;

use Illuminate\Support\Facades\Validator;

class UserValidator
{
    public static function register(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255','unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }
    public static function login(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255','exists:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
    }
    

    // Các phương thức validate khác
}
