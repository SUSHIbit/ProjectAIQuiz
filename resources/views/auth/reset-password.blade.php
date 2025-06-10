<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-slate-900">Set new password</h2>
            <p class="mt-2 text-sm text-slate-600">Enter your new password to complete the reset process</p>
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

        <!-- Reset Form -->
        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email address')" class="text-slate-700 font-medium" />
                <x-text-input id="email" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                    type="email" 
                    name="email" 
                    :value="old('email', $request->email)" 
                    required 
                    autofocus 
                    autocomplete="username"
                    placeholder="Enter your email address" />
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('New password')" class="text-slate-700 font-medium" />
                <x-text-input id="password" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="new-password"
                    placeholder="Enter your new password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Confirm new password')" class="text-slate-700 font-medium" />
                <x-text-input id="password_confirmation" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Confirm your new password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                {{ __('Reset Password') }}
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                Remember your password?
                <a href="{{ route('login') }}" class="font-medium text-slate-900 hover:text-slate-700 hover:underline transition-colors">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>