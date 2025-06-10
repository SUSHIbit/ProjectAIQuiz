<x-guest-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center">
            <h2 class="text-2xl font-bold text-slate-900">Create your account</h2>
            <p class="mt-2 text-sm text-slate-600">Get started with AI-powered quiz generation</p>
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

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Full name')" class="text-slate-700 font-medium" />
                <x-text-input id="name" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                    type="text" 
                    name="name" 
                    :value="old('name')" 
                    required 
                    autofocus 
                    autocomplete="name"
                    placeholder="Enter your full name" />
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <x-input-label for="email" :value="__('Email address')" class="text-slate-700 font-medium" />
                <x-text-input id="email" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white" 
                    type="email" 
                    name="email" 
                    :value="old('email')" 
                    required 
                    autocomplete="username"
                    placeholder="Enter your email address" />
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
                    autocomplete="new-password"
                    placeholder="Create a secure password" />
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Confirm password')" class="text-slate-700 font-medium" />
                <x-text-input id="password_confirmation" 
                    class="w-full px-4 py-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-slate-500 focus:border-slate-500 transition-all duration-200 bg-slate-50 focus:bg-white"
                    type="password"
                    name="password_confirmation" 
                    required 
                    autocomplete="new-password"
                    placeholder="Confirm your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <!-- Terms Notice -->
            <div class="text-xs text-slate-500 bg-slate-50 rounded-lg p-3">
                <p>By creating an account, you agree to our Terms of Service and Privacy Policy. You'll get 3 free AI quiz generations to start!</p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full bg-slate-900 hover:bg-slate-800 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                {{ __('Create account') }}
            </button>
        </form>

        <!-- Login Link -->
        <div class="text-center pt-4 border-t border-slate-200">
            <p class="text-sm text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="font-medium text-slate-900 hover:text-slate-700 hover:underline transition-colors">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>