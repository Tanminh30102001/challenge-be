<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'fullname',
        'email',
        'password',
        'role',
        'approve',
        'username',
        'phone',
        'address',
        'position',
        'description'
    ];
    protected $hidden = ['password'];
    public $timestamps=true;
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

     public function tasks()
     {
         return $this->hasManyThrough(Task::class, Assignment::class);
     }
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
    public function task(){
        return $this->belongsTo(Task::class, Assignment::class);
    }
    
}
