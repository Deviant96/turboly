<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->orderBy('due_date')->get();

        if (request('isMobile')) {
            return view('mobile.index', compact('tasks'));
        }
        return view('desktop.index', compact('tasks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|string',
        ]);

        Auth::user()->tasks()->create($request->all());

        return redirect()->route('tasks.index');
    }

    public function update(Task $task)
    {
        $task->update(['is_completed' => !$task->is_completed]);
        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index');
    }

    public function clearCompleted()
    {
        Auth::user()->tasks()->where('is_completed', true)->delete();
        return response()->json(['success' => true]);
    }
}
