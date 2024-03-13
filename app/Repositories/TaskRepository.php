<?php

namespace App\Repositories;

use App\Events\UserRegistered;
use App\Models\Assignment;
use App\Models\Task;
use App\Models\User;
use App\Repositories\RepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TaskRepository implements TaskRepositoryInterface
{
    public function addTask($arr)
    {

        $task = new Task();
        $task->task_code = $this->generateRandomNumber();
        $task->name = $arr['name'];
        $task->desc = $arr['desc'] ?? null;
        $task->deadline = $arr['deadline'];
        $task->created_id = $arr['created_id'];
        $task->type_id = $arr['type'];
        $task->status_id = $arr['status'];
        $task->priority_id = $arr['priority'];
        $task->save();

        foreach ($arr['assigned_to'] as $userID) {
            Assignment::create([
                'user_id' => $userID,
                'task_id' => $task->id,
            ]);
        }
        return $task;
    }
    protected function generateRandomNumber()
    {
        return mt_rand(100000, 999999); // Sinh số ngẫu nhiên 6 chữ số
    }
    public function getDetails($id)
    {
        $task = Task::with('assignments')->find($id);
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        $userIds = [];
        foreach ($task->assignments as $assignment) {
            $userIds[] = $assignment->user_id;
        }
        $users = User::whereIn('id', $userIds)->get(['id', 'fullname']);
        $fullNames = [];
        foreach ($users as $user) {
            $fullNames[] = $user->fullname;
        }
        return response()->json([
            'task' => $task,
            'assginee' => $fullNames
        ]);
    }
    public function getAllTassk()
    {
        $tasksWithUsersArray = Task::leftJoin('assignments', 'tasks.id', '=', 'assignments.task_id')
            ->groupBy(
                'tasks.id',
                'type_id',
                'priority_id',
                'created_id',
                'task_code',
                'name',
                'desc',
                'deadline',
                'Assigned',
                'status_id',
                'created_at',
                'updated_at'
            )
            ->select('tasks.*', DB::raw('GROUP_CONCAT(assignments.user_id) as user_ids'))->orderByDesc('tasks.id')
            ->get()
            ->map(function ($task) {
                $task['user_ids'] = explode(',', $task['user_ids']);
                $userFullnames = [];
                foreach ($task['user_ids'] as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $userFullnames[] = ['name' => $user->fullname];
                    }
                }

                $task['user_fullnames'] = $userFullnames;

                return $task;
            });
        return $tasksWithUsersArray;
    }
    public function updateTask($id, $request)
    {
        $task = Task::findOrFail($id);
        if ($task) {
            $task->update($request);
            return response()->json(['message' => 'Task update success'], 200);
        }
        return response()->json(['message' => 'Task not found'], 400);
    }
    public function showByUser($id)
    {
        $tasksWithUsersArray = Task::leftJoin('assignments', 'tasks.id', '=', 'assignments.task_id')
            ->where('assignments.user_id', $id)
            ->groupBy(
                'tasks.id',
                'type_id',
                'priority_id',
                'created_id',
                'task_code',
                'name',
                'desc',
                'deadline',
                'Assigned',
                'status_id',
                'created_at',
                'updated_at'
            )
            ->select('tasks.*', DB::raw('GROUP_CONCAT(assignments.user_id) as user_ids'))->orderByDesc('tasks.id')
            ->get()
            ->map(function ($task) {
                // Chuyển chuỗi user_ids thành mảng các user_id
                $task['user_ids'] = explode(',', $task['user_ids']);

                // Lấy thông tin fullname từ bảng users
                $userFullnames = [];
                foreach ($task['user_ids'] as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $userFullnames[] = ['name' => $user->fullname];
                    }
                }

                $task['user_fullnames'] = $userFullnames;

                return $task;
            });
        return $tasksWithUsersArray;
    }
    public function filterAll($request)
    {
        $query = $this->getAllTassk();
        if (isset($request['status_id'])) {
            $query = $query->where('status_id', $request['status_id']);
        }
        if (isset($request['priority_id'])) {
            $query = $query->where('priority_id', $request['priority_id']);
        }
        if (isset($request['type_id'])) {
            $query = $query->where('type_id', $request['type_id']);
        }
        $result = [];
        foreach ($query as $item) {
            $result[] = $item;
        }
        return $result;
    }
    public function fillterWithUser($id, $request)
    {
        $query = $this->showByUser($id);

        if (isset($request['status_id'])) {
            $query = $query->where('status_id', $request['status_id']);
        }

        if (isset($request['priority_id'])) {
            $query = $query->where('priority_id', $request['priority_id']);
        }

        if (isset($request['type_id'])) {
            $query = $query->where('type_id', $request['type_id']);
        }
        $result = [];
        foreach ($query as $item) {
            $result[] = $item;
        }

        return $result;
    }
    public function serchTask($keyword)
    {
        $keyword = strtolower($keyword);
        $tasksWithUsersArray = Task::leftJoin('assignments', 'tasks.id', '=', 'assignments.task_id')
            ->where(function ($query) use ($keyword) {
                $query->whereRaw('LOWER(tasks.name) like ?', '%' . $keyword . '%') // Tìm kiếm không phân biệt chữ hoa chữ thường
                    ->orWhere('tasks.task_code', 'like', '%' . $keyword . '%'); // Tìm kiếm theo task code
            })
            ->groupBy(
                'tasks.id',
                'type_id',
                'priority_id',
                'created_id',
                'task_code',
                'name',
                'desc',
                'deadline',
                'Assigned',
                'status_id',
                'created_at',
                'updated_at'
            )
            ->select('tasks.*', DB::raw('GROUP_CONCAT(assignments.user_id) as user_ids'))
            ->get()
            ->map(function ($task) {
                $task['user_ids'] = explode(',', $task['user_ids']);
                $userFullnames = [];
                foreach ($task['user_ids'] as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $userFullnames[] = ['name' => $user->fullname];
                    }
                }

                $task['user_fullnames'] = $userFullnames;

                return $task;
            });

        return $tasksWithUsersArray;
    }
    public function serchTaskWithUser($keyword, $id)
    {
        $keyword = strtolower($keyword);
        $tasksWithUsersArray = Task::leftJoin('assignments', 'tasks.id', '=', 'assignments.task_id')->where('assignments.user_id', $id)
            ->where(function ($query) use ($keyword) {
                $query->whereRaw('LOWER(tasks.name) like ?', '%' . $keyword . '%')
                    ->orWhere('tasks.task_code', 'like', '%' . $keyword . '%');
            })
            ->groupBy(
                'tasks.id',
                'type_id',
                'priority_id',
                'created_id',
                'task_code',
                'name',
                'desc',
                'deadline',
                'Assigned',
                'status_id',
                'created_at',
                'updated_at'
            )
            ->select('tasks.*', DB::raw('GROUP_CONCAT(assignments.user_id) as user_ids'))
            ->get()
            ->map(function ($task) {
                $task['user_ids'] = explode(',', $task['user_ids']);
                $userFullnames = [];
                foreach ($task['user_ids'] as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $userFullnames[] = ['name' => $user->fullname];
                    }
                }

                $task['user_fullnames'] = $userFullnames;

                return $task;
            });

        return $tasksWithUsersArray;
    }
    public function reportTask()
    {
        $totalTasks = Task::count();
        $currentDate = Carbon::now()->toDateString();


        $totalTasksWithStatus1 = Task::where('status_id', 1)->count();
        $totalTasksWithStatus2 = Task::where('status_id', 2)->count();
        $totalTasksWithStatus3 = Task::where('status_id', 3)->count();
        $totalTasksWithStatus4 = Task::where('status_id', 4)->count();
        $totalTasksWithStatusBUG = Task::where('status_id', 4)->count();
        $overdueTasksCount = Task::where('deadline', '<', $currentDate)->count();
        return response()->json([
            'total_tasks' => $totalTasks,
            'total_tasks_with_status_1' => $totalTasksWithStatus1,
            'total_tasks_with_status_2' => $totalTasksWithStatus2,
            'total_tasks_with_status_3' => $totalTasksWithStatus3,
            'total_tasks_with_status_4' => $totalTasksWithStatus4,
            'overdue_tasks' => $overdueTasksCount,
        ]);
    }
    public function getDetailTask($id)
    {
        $tasksWithUsersArray = Task::leftJoin('assignments', 'tasks.id', '=', 'assignments.task_id')->where('tasks.id', $id)
            ->groupBy(
                'tasks.id',
                'type_id',
                'priority_id',
                'created_id',
                'task_code',
                'name',
                'desc',
                'deadline',
                'Assigned',
                'status_id',
                'created_at',
                'updated_at'
            )
            ->select('tasks.*', DB::raw('GROUP_CONCAT(assignments.user_id) as user_ids'))->orderByDesc('tasks.id')
            ->get()
            ->map(function ($task) {
                $task['user_ids'] = explode(',', $task['user_ids']);
                $userFullnames = [];
                foreach ($task['user_ids'] as $userId) {
                    $user = User::find($userId);
                    if ($user) {
                        $userFullnames[] = ['name' => $user->fullname];
                    }
                }

                $task['user_fullnames'] = $userFullnames;

                return $task;
            });
        return $tasksWithUsersArray;
    }
    public function assigneUser($taskID, $request)
    {

        $task = Assignment::where('task_id', $taskID)->first();
        foreach ($request['users'] as $userID) {
            $as = new Assignment;
            $as->task_id = $taskID;
            $as->user_id = $userID;
            $as->save();
        }
        return $task;
    }
}
