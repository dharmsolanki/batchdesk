@extends('layouts.app')
@section('title', 'Sign in — BatchDesk')
@section('content')
<div class="max-w-md mx-auto mt-8">
    <h1 class="font-bold text-2xl tracking-tight mb-5">Sign in</h1>
    <form method="POST" action="{{ route('login') }}" class="space-y-4 card p-5">
        @csrf
        <div>
            <label class="label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="field">
        </div>
        <div>
            <label class="label">Password</label>
            <input type="password" name="password" required class="field">
        </div>
        <button class="btn-primary w-full">Sign in</button>
        <p class="text-center text-sm text-muted">New company? <a href="{{ route('register') }}" class="text-brand font-semibold">Create an account</a></p>
    </form>
</div>
@endsection
