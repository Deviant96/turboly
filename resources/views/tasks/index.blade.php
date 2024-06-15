@extends('layouts.layout')

@section('styles')
    <style>
        h2,
        h3 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            margin-bottom: 30px;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            /* border: 1px solid #ddd; */
            /* border-radius: 5px; */
            font-size: 16px;
            color: #555;
            background-color: #f9f9f9;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        .clear-completed {
            background: #dc3545;
            margin-bottom: 20px;
        }

        .clear-completed:hover {
            background: #c82333;
        }

        .form-group select {
            width: 100%;
            padding: 0.5rem 2.5rem 0.5rem 0.5rem;
            border: none;
            border-bottom: 1px solid #ccc;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.3s;
        }

        .form-group select:focus {
            border-bottom: 2px solid var(--primary-color);
        }

        .form-group select+.placeholder {
            position: absolute;
            left: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            color: #aaa;
            transition: all 0.3s;
            pointer-events: none;

            top: 10px;
            left: 0.5rem;
            font-size: 0.75rem;
            color: var(--primary-color);
        }

        #taskList {
            list-style-type: none;
        }

        #taskList li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            margin-bottom: 10px;
            background: #f1f1f1;
            border-radius: 5px;
            border-left: 5px solid #007bff;
        }

        #taskList li.completed {
            text-decoration: line-through;
            color: #888;
            border-left-color: #28a745;
        }

        #taskList li form {
            display: inline;
        }

        .task-actions {
            display: flex;
            gap: 10px;
        }

        .icon-button {
            background: none;
            border: none;
            cursor: pointer;
            color: #007bff;
            font-size: 1.2em;
            transition: color 0.3s;
        }

        .icon-button:hover {
            color: #0056b3;
        }

        .icon-button .fa-trash {
            color: #dc3545;
        }

        .icon-button:hover .fa-trash {
            color: #c82333;
        }

        .clear-completed:disabled {
            background: #ddd;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h2>Tasks</h2>
        <form id="taskForm" method="POST" action="{{ route('tasks.store') }}">
            @csrf
            <x-input type="text" name="description" id="description" label="Description" value="{{ old('description') }}"
                icon="fa fa-user" required />

            <x-input type="date" name="due_date" id="due_date" label="Due Date" value="{{ old('due_date') }}" required
                style="padding-right: 4px" />

            <div class="form-group">
                <select class="form-control" id="priority" name="priority" required>
                    <option value="Low" selected>Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
                <label for="priority" class="placeholder">Priority:</label>
            </div>


            <button type="submit">Add Task</button>
        </form>

        <h3>My Tasks</h3>
        <button id="clearCompleted" class="clear-completed">Clear All Completed</button>
        <ul id="taskList">
            @foreach ($tasks as $task)
                <li class="{{ $task->is_completed ? 'completed' : '' }}">
                    <span>{{ $task->description }} - {{ $task->due_date }} - {{ $task->priority }}</span>
                    <div class="task-actions">
                        <form method="POST" action="{{ route('tasks.update', $task->id) }}" style="display: inline;">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="icon-button"
                                title="{{ $task->is_completed ? 'Mark Incomplete' : 'Mark Complete' }}">
                                <i class="fas {{ $task->is_completed ? 'fa-undo' : 'fa-check' }}"></i>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="delete-form"
                            style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-button" title="Delete Task">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const clearCompletedButton = document.getElementById('clearCompleted');
            const completedTasks = document.querySelectorAll('#taskList li.completed');

            if (completedTasks.length === 0) {
                clearCompletedButton.disabled = true;
            }

            clearCompletedButton.addEventListener('click', function(event) {
                event.preventDefault();

                if (confirm('Are you sure you want to clear all completed tasks?')) {
                    fetch('/tasks/clear-completed', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(event) {
                    if (!confirm('Are you sure you want to delete this task?')) {
                        event.preventDefault();
                    }
                });
            });
        });
    </script>
@endsection
