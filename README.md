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

## API must follow these requirements:

## 1.Retrieve a List of Tasks Based on Filters:

#### [For this task I created Flexible Filter Builder that handle filtering for any field]
### Code Implementation:


- The filtering logic is implemented in the TaskFilter
class located in the App\Http\Filters namespace.
This class extends the AbstractFilter
class and defines specific filter methods for each parameter (title,
description, status, priority, created_at, completed_at).

- These methods manipulate the query builder instance to apply the corresponding filters.

### Example Usage:
```http request
POST /tasks?title=query&status=todo&priority=desc&created_at=asc
```
#### Response:
```json
[
{
"id": 1,
"title": "Sample Task",
"description": "Task Description",
"status": "todo",
"priority": 3,
"created_at": "2023-11-08T12:00:00Z",
"updated_at": "2023-11-08T13:00:00Z",
"completed_at": null,
"user_id": 1,
"parent_id": null
}
]
```
## 2.Create task Functionality: 

The API allows creating new tasks by sending a POST request to the /tasks/store endpoint. Include the task details such as title, description, status, priority, user_id, and optionally, parent_id for subtasks. Validation rules ensure the request contains valid data.

## Code Implementation:

- The CreateTaskRequest class in the App\Http\Requests namespace defines the validation rules for creating a task. It ensures the request contains necessary fields such as title, description, user_id, status, and priority.

- The TaskController class in the App\Http\Controllers namespace handles the creation logic. When a valid request is received, it creates a TaskDTO object, passing the received data, and calls the createTask method of the TaskService class.

### Example Request:

```http request
POST /tasks/store
Content-Type: application/json

```
```json
{
"title": "New Task",
"description": "Task Description",
"status": "todo",
"priority": 2,
"user_id": 1,
"parent_id": null
}
```
### Response:

```json
{
"id": 2,
"title": "New Task",
"description": "Task Description",
"status": "todo",
"priority": 2,
"created_at": "2023-11-08T14:00:00Z",
"updated_at": null,
"completed_at": null,
"user_id": 1,
"parent_id": null
}
```
## 3.Edit Task Functionality:

Existing tasks can be updated using a PUT or PATCH request to the /tasks/{id} endpoint. Provide the id of the task to be updated and the updated task details. Only the task owner can edit the task.

### Code Implementation:

- The UpdateTaskRequest class in the App\Http\Requests namespace defines the validation rules for updating a task. It ensures the request contains necessary fields such as title, description, user_id, status, and priority.

- The TaskController class in the App\Http\Controllers namespace handles the update logic. When a valid request is received, it creates a TaskDTO object, passing the received data, and calls the updateTask method of the TaskService class.

### Example Request:

```http request
PUT /tasks/2
Content-Type: application/json
```

```json
{
"title": "Updated Task",
"description": "Updated Description",
"status": "done",
"priority": 1,
"user_id": 1
}

```

### Response:

```json
{
"id": 2,
"title": "Updated Task",
"description": "Updated Description",
"status": "done",
"priority": 1,
"created_at": "2023-11-08T14:00:00Z",
"updated_at": "2023-11-08T15:00:00Z",
"completed_at": "2023-11-08T15:00:00Z",
"user_id": 1,
"parent_task_id": null
}
```

## 4.Mark as Done Task Functionality:

A task can be marked as done by sending a PUT or PATCH request to the /tasks/{id}/done endpoint. The task status will be updated to done, and the completed_at timestamp will be set to the current time. Only tasks without uncompleted subtasks can be marked as done.

### Code Implementation:

- The TaskService class contains the markTaskAsDone method. This method checks if the task has uncompleted subtasks before marking it as done. If the task has uncompleted subtasks, an exception is thrown.
- The TaskController class in the App\Http\Controllers namespace handles the marking as done logic. When a valid request is received, it calls the markTaskAsDone method of the TaskService class.

### Example Request:

```http request
PUT /tasks/2/done
```

### Response:

```json
{
  "id": 2,
  "title": "Updated Task",
  "description": "Updated Description",
  "status": "done",
  "priority": 1,
  "created_at": "2023-11-08T14:00:00Z",
  "updated_at": "2023-11-08T15:00:00Z",
  "completed_at": "2023-11-08T15:00:00Z",
  "user_id": 1,
  "parent_task_id": null
}

```


## 5.Mark as Done Task Functionality:

A task can be deleted by sending a DELETE request to the /tasks/{id} endpoint. Only tasks that are not done can be deleted. If the task is done, an exception is thrown.

### Code Implementation:

- The TaskService class contains the deleteTask method. This method checks if the task is done before allowing deletion. If the task is done, an exception is thrown
- The TaskController class in the App\Http\Controllers namespace handles the deletion logic. When a valid request is received, it calls the deleteTask method of the TaskService class.

### Example Request:

```http request
DELETE /tasks/2
Content-Type: application/json

{
    "user_id": 1
}
```

### Response:

```json
{
  "message": "Task deleted successfully."
}

```

## API Endpoints

Get Tasks

    POSt /api/tasks?status=todo&priority=3&title=example&sort=priority desc,createdAt asc

Create Task

    POST /api/tasks/store

Edit Task

    PUT /api/tasks/{taskId}
    

Mark Task as Completed

    PUT /api/tasks/{taskId}/done

Delete Task

    DELETE /api/tasks/{taskId}


## Packages that I used
```bash 
composer require laravel/ui                                        
composer require laravel/sanctum
```

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