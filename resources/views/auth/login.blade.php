@extends('layouts.app')

@section('title', 'User Login')
@section('bodyClass', 'login-page')
@section('footerClass', 'login-footer')

@section('content')
<div class="login-main">
    <div class="form-box">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
        <h2>USER LOGIN</h2>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        {{-- Errors --}}
        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-grid">
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="btn full">Login</button>
            </div>
        </form>

        <div class="register-link">
            Don’t have an account yet? <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</div>
@endsection
