<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUserDatabase
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        $users = $event->user;
        $signup = User::create([
            'username'=>$users['username'],
            'password' => Hash::make($users['password']),
            'email'=>$users['email'],
            'fullname'=>$users['fullname'],
            'role'=>1,
            'approve'=>0
        ]);
       Log::info($signup);
    }
}
