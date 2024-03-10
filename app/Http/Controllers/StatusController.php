<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\Status;
use App\Models\TypeofTask;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function getAllStatus(){
        return  Status::all();
    }
    public function getAllType(){
        return TypeofTask::all();
    }
    public function getAllPriority(){
        return Priority::all();
    }
}
