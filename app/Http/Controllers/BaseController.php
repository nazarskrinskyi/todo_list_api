<?php

namespace App\Http\Controllers;

use App\Services\TaskService;

class BaseController extends Controller
{
    public TaskService $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

}
