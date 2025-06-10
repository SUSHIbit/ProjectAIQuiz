<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-slate-900">Welcome back</h2>
            <p class="mt-2 text-sm text-slate-600">Sign in to your account to continue</p>
        </div>

        <!-- Back to Homepage Link -->
        <div class="text-center">
            <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-700 transition-colors group">
                <svg class="w-4 h-4 mr-1 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to homepage
            </a>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email address')" class="text-slate-700 font-medium" />
                <x-text-input id="email" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Enter your email" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-medium" />
                <x-text-input id="password" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="Enter your password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" 
                        class="rounded border-slate-300 text-slate-600 shadow-sm focus:ring-slate-500 focus:ring-offset-0" 
                        name="remember">
                    <span class="ml-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-slate-600 hover:text-slate-900 hover:underline transition-colors" 
                       href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                {{ __('Sign in') }}
            </button>
        </form>

        <!-- Register Link -->
        <div class="text-center pt-4 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-slate-900 hover:text-slate-700 hover:underline transition-colors">
                    Register here
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>