<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class TaskController extends Controller
{
    protected $taskRepo;
    public function __construct(TaskRepositoryInterface $taskRepo)
    {
        $this->taskRepo = $taskRepo;

    }
    public function store(Request $request){
       return $this->taskRepo->addTask($request->all());
    }
    public function index(){
       $data=Task::all();
       return  $this->taskRepo->getAllTassk();
    }
    public function getDetailsTask($id){
        return $this->taskRepo->getDetails($id);
    }
  
}
