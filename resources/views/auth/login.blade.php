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
    <form action="" method="POST">
        @csrf
        <div class="login-container">
            <form class="login-form">
                <x-input type="text" name="username" id="username" label="Username" value="{{ old('username') }}"
                    icon="fa fa-user" />
                <x-input type="password" name="password" id="password" label="Password" value="{{ old('password') }}"
                    icon="fa fa-lock" />
                <button type="submit" class="form-button">Login</button>
                <div></div>
            </form>
            <p class="register-link">Don't have an account? <a href="register.html">Register here</a></p>
        </div>
    </form>
    </div>
@endsection
