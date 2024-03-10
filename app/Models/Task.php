<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'desc',
        'deadline',
        'created_id',
        'status_id',
        'priority_id',
        'task_code',

    ];
    protected $hidden = ['status','type','priority','create'];
    public $timestamps=true;
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
    public function users()
    {
        return $this->hasManyThrough(User::class, Assignment::class);
    }
    public function priority(){
        return $this->belongsTo(Priority::class);
    }
    public function create(){
        return $this->belongsTo(User::class,'created_id');
    }
    public function type(){
        return $this->belongsTo(TypeofTask::class);
    }
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id'); // Đảm bảo rằng 'status_id' là trường tham chiếu đến bảng statuses
    }
    protected $appends = ['status_task', 'type_task','priority_task','created_user'];
    public function getCreatedUserAttribute(){
        if ($this->create) {
            return $this->create->fullname;
        } else {
            return null; // hoặc giá trị mặc định khác tùy theo nhu cầu của bạn
        }
    }
    public function getTypeTaskAttribute(){
        return  $this->type->name ;
    }
    public function getPriorityTaskAttribute(){
        return   $this->priority->name;
    }
    public function getStatusTaskAttribute(){
        return  $this->status->name ;
    }
}
