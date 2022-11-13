<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Task;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function validator(array $request)
    {
        return Validator::make($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
    }

    public function index()
    {
        return Task::with(['user:id,name', 'creater:id,name'])->where('user_id', '=', Auth::user()->id)->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $this->validator($request->all())->validate();

        if (!$request->has('user_id')) {
            $request->request->add(['user_id' => Auth::user()->id]);
        }
        if (Auth::user()->createdTasks()->Create($request->all())) {
            return $this->index();
        }
    }

    public function show(Task $task)
    {
        if ($task->user_id != Auth::user()->id)
            abort(403);
        return $this->index();
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id != Auth::user()->id)
            abort(403);
        $taskRequest = $request->only($task->getFillable());
        $task->fill($taskRequest)->save();
        return $this->index();
    }

    public function destroy(Task $task)
    {
        if ($task->user_id != Auth::user()->id)
            abort(403);
        $task->delete();
        return $this->index();
    }
}
