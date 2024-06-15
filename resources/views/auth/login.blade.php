@extends('layouts.layout')

@section('styles')
    <style>
        .register-link {
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .register-link a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')
    <h1>Login</h1>
    <div class="form-container">
        <form class="form" id="loginForm">
            @csrf
            <x-input type="text" name="username" id="username" label="Username" value="{{ old('username') }}"
                icon="fa fa-user" />
            <x-input type="password" name="password" id="password" label="Password" value="{{ old('password') }}"
                icon="fa fa-lock" />
            <div id="error-message" style="color: red;"></div>
            <button type="submit" class="form-button">Login</button>
        </form>
        <p class="register-link">Don't have an account? <a href="{{ route('registerView') }}">Register here</a></p>
    </div>
    </div>
@endsection

@section('scripts')
    <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.getElementById('loginForm').addEventListener('submit', function(event) {
                    event.preventDefault();

                    const username = document.getElementById('username').value;
                    const password = document.getElementById('password').value;

                    const errorMessage = document.getElementById('error-message');
                    errorMessage.textContent = '';

                    fetch('/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute(
                                        'content')
                            },
                            body: JSON.stringify({
                                username,
                                password
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.href = '/dashboard';
                            } else {
                                errorMessage.textContent = data.message;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
    </script>
@endsection
