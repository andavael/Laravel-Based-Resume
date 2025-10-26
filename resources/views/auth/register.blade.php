@extends('layouts.app')

@section('title', 'User Registration')
@section('bodyClass', 'registration-page')
@section('footerClass', 'registration-footer')

@section('content')
<div class="registration-main">
    <div class="form-box">
        <img src="{{ asset('assets/logo.png') }}" alt="Logo" class="logo">
        <h2>USER REGISTRATION</h2>

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

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-grid">
                <input type="text" name="sr_code" placeholder="SR-Code" value="{{ old('sr_code') }}" required>
                <input type="text" name="username" placeholder="Username" value="{{ old('username') }}" required>
                <input type="email" name="email" placeholder="BatStateU G-Suite Account" value="{{ old('email') }}" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <button type="submit" class="btn full">Register</button>
            </div>
        </form>

        <div class="login-link">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </div>
    </div>
</div>
@endsection
