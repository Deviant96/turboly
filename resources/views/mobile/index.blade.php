@extends('layouts.layout')

@section('styles')
<style>
    .close-btn {
        position: absolute;
        right: 0;
        top: 0;
        transform: translate(-125%, 100%);
        border: none;
        background: transparent;
        color: gray;
        cursor: pointer;
    }

    .close-btn:hover {
        color: #000;
    }
</style>
@endsection

@section('content')
<div id="alertDueToday" class="alert-due-today" style="display: none;">
    You have tasks due today!
</div>
<div class="container task-container">
    <div id="overlay" class="hidden">
        <div id="formContainer" class="form-hidden">
            <button id="closeFormBtn" class="close-btn">
                <img src="{{ asset('images/close.png') }}" alt="" width="20px" />
            </button>
            <h2>Tasks</h2>
            <form id="taskForm" method="POST" action="{{ route('tasks.store') }}">
                @csrf
                <x-input type="text" name="description" id="description" label="Description"
                    value="{{ old('description') }}" icon="fa fa-check" required />

                <x-input type="date" name="due_date" id="due_date" label="Due Date" value="{{ old('due_date') }}"
                    required style="padding-right: 4px" />

                <div class="form-group">
                    <select class="form-control" id="priority" name="priority" required>
                        <option value="Low" selected>Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                    <label for="priority" class="placeholder">Priority:</label>
                </div>

                <button class="button" type="submit">Add Task</button>
            </form>
        </div>
    </div>

    <div class="task-list">
        <header class="task-list-header">
            <h3>My Tasks</h3>
            <div id="taskMenuAction" class="task-menu-actions">
                <button id="clearCompleted" class="clear-completed">Clear All Completed</button>
            </div>
        </header>

        <main>
            <div class="sort-controls">
                <select id="sortTasks">
                    <option value="due-date-asc">Due Date (⬆)</option>
                    <option value="due-date-desc">Due Date (⬇)</option>
                    <option value="description-asc">Description (A-Z)</option>
                    <option value="description-desc">Description (Z-A)</option>
                    <option value="priority-asc">Priority (low-high)</option>
                    <option value="priority-desc">Priority (high-low)</option>
                </select>
            </div>
            @if ($tasks->isEmpty())
                <div class="empty-task" style="font-style: italic; color: gray">
                    <img src="{{ asset('images/check.png') }}" alt="" />
                    <p>Looks like you have completed all the tasks!</p>
                    <small>Tap the button on the right bottom side to add new task</small>
                </div>
            @endif
            <ul id="taskList">
                @foreach ($tasks as $task)
                    <li class="task-item {{ \Carbon\Carbon::parse($task->due_date)->lessThanOrEqualTo(\Carbon\Carbon::today()) ? 'due-today' : '' }} {{ $task->is_completed ? 'completed' : '' }}"
                        data-due-date="{{ $task->due_date }}" data-description="{{ $task->description }}"
                        data-priority="{{ $task->priority }}">
                        <div class="task-content">
                            <h4 class="task-description">{{ $task->description }}</h4>
                            <p class="task-priority">{{ $task->priority }}</p>
                            <p class="task-due">{{ $task->due_date }}</p>
                        </div>
                        <div class="task-actions">
                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="delete-form"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="icon-button" title="Delete Task">
                                    <i class="fa fa-trash-o"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('tasks.update', $task->id) }}" style="display: inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="icon-button"
                                    title="{{ $task->is_completed ? 'Mark Incomplete' : 'Mark Complete' }}">
                                    <i class="fas {{ $task->is_completed ? 'fa-undo' : 'fa-check' }}"></i>
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </main>
    </div>
</div>

<button id="newTaskBtn" class="new-task">
    <img src="{{ asset('images/plus.svg') }}" width="20px" alt="" />
</button>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const clearCompletedButton = document.getElementById('clearCompleted');
        const completedTasks = document.querySelectorAll('#taskList li.completed');
        const dueTodayTasks = document.querySelectorAll('#taskList li.due-today:not(.completed)');
        const alertDueToday = document.getElementById('alertDueToday');
        const sortTasksSelect = document.getElementById('sortTasks');

        if (completedTasks.length === 0) {
            clearCompletedButton.disabled = true;
        }

        if (dueTodayTasks.length > 0) {
            alertDueToday.style.display = 'block';
        }

        clearCompletedButton.addEventListener('click', function (event) {
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
            form.addEventListener('submit', function (event) {
                if (!confirm('Are you sure you want to delete this task?')) {
                    event.preventDefault();
                }
            });
        });

        const buttonNewTask = document.getElementById('newTaskBtn');
        const buttonCloseForm = document.getElementById('closeFormBtn');
        const formContainer = document.getElementById('formContainer');
        const overlay = document.getElementById('overlay');
        buttonNewTask.addEventListener('click', function () {
            overlay.classList.remove('hidden');
            formContainer.classList.toggle('show');
            // document.body.style.filter = 'blur(5px)';
        });

        buttonCloseForm.addEventListener('click', function () {
            closeForm();
        });

        overlay.addEventListener('click', function (event) {
            if (event.target === this) {
                closeForm();
            }
        });

        function closeForm() {
            overlay.classList.add('hidden');
            formContainer.classList.remove('show');
            document.getElementById('taskForm').reset();
        }

        sortTasksSelect.addEventListener('change', function () {
            const value = this.value;
            let attribute, order;

            if (value.includes('due-date')) {
                attribute = 'data-due-date';
            } else if (value.includes('description')) {
                attribute = 'data-description';
            } else if (value.includes('priority')) {
                attribute = 'data-priority';
            }

            order = value.includes('asc') ? 'asc' : 'desc';

            sortTasks(attribute, order);
        });

        function sortTasks(attribute, order) {
            const taskList = document.getElementById('taskList');
            const tasks = Array.from(taskList.querySelectorAll('li'));

            tasks.sort((a, b) => {
                if (attribute === 'data-due-date') {
                    return order === 'asc'
                        ? new Date(a.getAttribute(attribute)) - new Date(b.getAttribute(attribute))
                        : new Date(b.getAttribute(attribute)) - new Date(a.getAttribute(attribute));
                } else if (attribute === 'data-priority') {
                    const priorityOrder = { 'Low': 1, 'Medium': 2, 'High': 3 };
                    return order === 'asc'
                        ? priorityOrder[a.getAttribute(attribute)] - priorityOrder[b.getAttribute(attribute)]
                        : priorityOrder[b.getAttribute(attribute)] - priorityOrder[a.getAttribute(attribute)];
                } else {
                    return order === 'asc'
                        ? a.getAttribute(attribute).localeCompare(b.getAttribute(attribute))
                        : b.getAttribute(attribute).localeCompare(a.getAttribute(attribute));
                }
            });

            taskList.innerHTML = '';
            tasks.forEach(task => taskList.appendChild(task));
        }
    });
</script>
@endsection