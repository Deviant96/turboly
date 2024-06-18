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
                <x-input type="text" name="username" id="username" label="Username" value="{{ old('username') }}"
                    icon="fa fa-user" required />
                <x-input type="password" name="password" id="password" label="Password" value="{{ old('password') }}"
                    icon="fa fa-lock" required />
                <x-input type="password" name="password_confirmation" id="password_confirmation" label="Confirm Password"
                    icon="fa fa-lock" required />
                <div id="error-message" style="color: red;"></div>
                <button type="submit" class="form-button">Sign Up</button>
                <div></div>
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
                errorMessage.textContent = '';
                if (password !== password_confirmation) {
                    errorMessage.textContent = 'Passwords do not match.';
                    return;
                }
                console.log("Fetch section")
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
                        console.log("data");
                        if (data.success) {
                            window.location.href = '/login';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    </script>
@endsection
