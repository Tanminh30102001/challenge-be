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
    public function store(Request $request)
    {
        return $this->taskRepo->addTask($request->all());
    }
    public function index()
    {
        return  $this->taskRepo->getAllTassk();
    }
    public function getDetailsTask($id)
    {
        return $this->taskRepo->getDetails($id);
    }
    public function update($id, Request $request)
    {
        return $this->taskRepo->updateTask($id, $request->all());
    }
    public function show($id)
    {
        return $this->taskRepo->showByUser($id);
    }
    public function filterAllTask(Request $request)
    {
        $req = $request->all();
        return $this->taskRepo->filterAll($req);
    }
    public function fillterWithUser(Request $request, $id)
    {
        $req = $request->all();
        return $this->taskRepo->fillterWithUser($id, $req);
    }
    public function search(Request $request)
    {
        return $this->taskRepo->serchTask($request->search);
    }
}
