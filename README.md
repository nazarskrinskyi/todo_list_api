# Todo List API

This is a RESTful API for managing tasks and subtasks. The API allows users to create tasks, edit them, mark them as completed, and delete them. Users can filter tasks based on status, priority, title, and description, and sort them by createdAt, completedAt, and priority. Tasks can have subtasks with unlimited nesting levels.
Features

Get Tasks:

    Retrieve a list of tasks based on specified filters, sorting, and search parameters.

Create Task:
    
    Create a new task with title, description, priority, and optional subtasks.

Edit Task:

    Modify the title, description, priority, or subtasks of an existing task.

Mark Task as Completed:
    
    Set a task's status to "done."

Delete Task:
    
    Delete a task and its subtasks.

Filtering:
    
    Filter tasks by status, priority, title, and description.

Sorting:

    Sort tasks by createdAt, completedAt, and priority in ascending or descending order.

Subtasks:

    Tasks can have subtasks with the same properties as tasks.
    
Validation:
    
    Input data is validated to ensure consistency and integrity.
    
Database:

    Use database seeding and indexes for efficient data retrieval.

## All steps by step

### API має надавати можливість:



## API Endpoints

Get Tasks

    GET /api/tasks?status=todo&priority=3&title=example&sort=priority desc,createdAt asc

Create Task

    POST /api/tasks
    {
    "title": "Example Task",
    "description": "Task description",
    "priority": 3,
    "subtasks": [
    {
    "title": "Subtask 1",
    "priority": 2
    }
    ]
    }

Edit Task

    PUT /api/tasks/{taskId}
    {
    "title": "Updated Task Title"
    }

Mark Task as Completed


    PUT /api/tasks/{taskId}/complete

Delete Task

    DELETE /api/tasks/{taskId}


## Packages that I used
composer require laravel/ui                                        
php artisan ui vue --auth
composer require laravel/sanctum


## Installation and Setup

Clone the repository:

    git clone https://github.com/nazarskrinskyi/todo_list_api

Navigate to the project directory:

    cd todo-list-api

Install dependencies:

    composer install

Build the Docker containers:

    ./vendor/bin/sail build

Run docker:

     ./vendor/bin/sail  up -d
     ./vendor/bin/sail  bash

Run migrations:

    php artisan migrate
    
Seed the database:
    
    php artisan db:seed

Start the development server:
    
    npm run dev



The API will be accessible at http://localhost.