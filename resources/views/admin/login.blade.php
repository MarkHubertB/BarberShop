@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <section class="flex min-h-[calc(100vh-5rem)] items-center bg-[#0e0e0e] py-16">
        <div class="mx-auto w-full max-w-md px-5">
            <form method="POST" action="{{ route('admin.login') }}" class="border border-[#2a2a2a] bg-[#161616] p-6 sm:p-8">
                @csrf
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-[#8a6f32]">Admin</p>
                <h1 class="mt-3 font-serif text-4xl font-bold text-[#f0ece4]">Sign In</h1>

                <label class="mt-8 block">
                    <span class="text-sm text-[#f0ece4]">Email</span>
                    <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" required autofocus class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                    @error('email') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
                </label>

                <label class="mt-4 block">
                    <span class="text-sm text-[#f0ece4]">Password</span>
                    <input type="password" name="password" autocomplete="current-password" required class="mt-2 h-12 w-full border border-[#2a2a2a] bg-[#1f1f1f] px-3 text-[#f0ece4] outline-none transition duration-200 focus:border-[#c9a84c]">
                    @error('password') <span class="mt-1 block text-sm text-[#b94a4a]">{{ $message }}</span> @enderror
                </label>

                <label class="mt-5 flex items-center gap-3 text-sm text-[#f0ece4]/75">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 border-[#2a2a2a] bg-[#1f1f1f] text-[#c9a84c]">
                    Remember this session
                </label>

                <button type="submit" class="mt-6 h-12 w-full bg-[#c9a84c] px-5 font-bold uppercase tracking-wide text-[#0e0e0e] transition duration-200 hover:bg-[#8a6f32]">
                    Login
                </button>
            </form>
        </div>
    </section>
@endsection
