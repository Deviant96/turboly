@extends('layouts.layout')

@section('styles')
    <link href="{{ asset('css/mobile/page/auth.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="hero">
        <img class="hero-img" src="{{ asset('/images/register-hero.svg') }}" alt="" />
    </div>
    <div class="content">
        <h1>Sign Up</h1>
        <div class="form-container">
            <form class="form" id="registerForm">
                @csrf
                <div id="error-message">
                    <div class="err-title">Oops! Something went wrong.</div>
                    <div class="err-message"></div>
                </div>
                <x-input type="text" name="username" id="username" label="Username" value="{{ old('username') }}"
                    icon="fa fa-user" required />
                <x-input type="password" name="password" id="password" label="Password" value="{{ old('password') }}"
                    icon="fa fa-lock" required />
                <x-input type="password" name="password_confirmation" id="password_confirmation" label="Confirm Password"
                    icon="fa fa-lock" required />
                <button type="submit" class="form-button">Sign Up</button>
            </form>
            <p class="register-link">Already have an account? <a href="{{ route('loginView') }}">Sign in</a></p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('registerForm').addEventListener('submit', function(event) {
                event.preventDefault();

                const username = document.getElementById('username').value;
                const password = document.getElementById('password').value;
                const password_confirmation = document.getElementById('password_confirmation').value;

                const errorMessage = document.getElementById('error-message');
                const errorText = errorMessage.querySelector('.err-message');
                errorMessage.style.display = "none"; 
                errorText.textContent = '';

                if (password !== password_confirmation) {
                    errorText.textContent = 'Passwords do not match.';
                    errorMessage.style.display = "block"; 
                    return;
                }
                
                fetch('/register', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: JSON.stringify({
                            username,
                            password,
                            password_confirmation
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href = '/login';
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
