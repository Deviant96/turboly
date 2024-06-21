@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/mobile/page/auth.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="hero">
        <img class="hero-img" src="{{ asset('/images/login-hero.svg') }}" alt="" />
    </div>
    <div class="content">
        <h1>Login</h1>
        <div class="form-container">
            <form class="form" id="loginForm">
                @csrf
                <div id="error-message">
                    <div class="err-title">Oops! Something went wrong.</div>
                    <div class="err-message"></div>
                </div>
                <x-input type="text" name="username" id="username" label="Username" value="{{ old('username') }}"
                    icon="fa fa-user" />
                <x-input type="password" name="password" id="password" label="Password" value="{{ old('password') }}"
                    icon="fa fa-lock" />
                <button type="submit" class="form-button">Login</button>
            </form>
            <p class="register-link">Don't have account? <a href="{{ route('registerView') }}">Sign Up</a></p>
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
                    const errorText = errorMessage.querySelector('.err-message');
                    errorMessage.style.display = "none"; 
                    errorText.textContent = '';

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
                                errorMessage.style.display = "block"; 
                                errorText.textContent = data.message;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
    </script>
@endsection
