<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeofTask extends Model
{
    use HasFactory;
    protected $table='typeoftask';
    protected $fillable = [
        'name',
        'desc',
    ];
    public $timestamps=false;
    public function task(){
        return $this->belongsTo(Task::class);
    }
}
