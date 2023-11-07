<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        $tasks = Task::all();

        return response()->json($tasks);
    }

    public function store()
    {
        // Store a new task based on the form data
    }

    public function update()
    {
        // Update the specified task based on the form data
    }

    public function markAsDone()
    {
        // Mark the specified task as done
    }

    public function destroy()
    {
        // Delete the specified task
    }
}
