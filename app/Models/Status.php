<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $table='statuses';
    protected $fillable = [
        'name',
        'desc',
    ];
    public $timestamps=true;
    public function task(){
        return $this->belongsTo(Task::class);
    }
}
