<!-- resources/views/dashboard.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tier Status Alert for Free Users with Low Attempts -->
            @if($user->isFree() && $user->question_attempts <= 1)
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                {{ $user->question_attempts === 0 ? 'No AI generations remaining!' : 'Only 1 AI generation remaining!' }}
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Upgrade to Premium for unlimited AI quiz generations and exclusive features.</p>
                            </div>
                            <div class="mt-4">
                                <div class="-mx-2 -my-1.5 flex">
                                    <a href="{{ route('tier.upgrade') }}" class="bg-yellow-50 px-2 py-1.5 rounded-md text-sm font-medium text-yellow-800 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-yellow-50 focus:ring-yellow-600">
                                        Upgrade Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- User Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold">Welcome, {{ $user->name }}!</h3>
                            <p class="text-gray-600 mt-1">
                                Account Type: 
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->tier_badge_color }}">
                                    {{ ucfirst($user->tier) }}
                                </span>
                            </p>
                            @if($user->isFree())
                                <p class="text-sm text-gray-500 mt-2">
                                    Remaining AI Generations: 
                                    <span class="font-medium {{ $user->question_attempts > 0 ? 'text-blue-600' : 'text-red-600' }}">
                                        {{ $user->attempts_display }}
                                    </span>
                                </p>
                            @else
                                <p class="text-sm text-green-600 mt-2 font-medium">
                                    ✨ Enjoy unlimited AI generations and premium features!
                                </p>
                            @endif
                        </div>
                        @if($user->isFree())
                        <div>
                            <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                Upgrade to Premium
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Manual Quiz Creator -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Manual Quiz Creator</h3>
                                <p class="text-sm text-gray-500">Create custom quizzes manually</p>
                                <p class="text-xs text-green-600 mt-1">Available for all users</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('manual-quiz.create') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                                Create Quiz
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- AI Quiz Generator -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ $user->isFree() && $user->question_attempts <= 0 ? 'opacity-60' : '' }}">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">AI Quiz Generator</h3>
                                <p class="text-sm text-gray-500">Generate quizzes from uploaded documents</p>
                                @if($user->isFree())
                                    <p class="text-xs {{ $user->question_attempts > 0 ? 'text-blue-600' : 'text-red-600' }} mt-1">
                                        {{ $user->question_attempts }} attempts remaining
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-4">
                            @if($user->canGenerateQuestions())
                                <a href="{{ route('welcome') }}" class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-500">
                                    Start Generating
                                    <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            @else
                                <div class="flex flex-col space-y-2">
                                    <span class="text-sm text-red-600 font-medium">No attempts remaining</span>
                                    <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                                        Upgrade for Unlimited
                                        <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- My Quizzes -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">My Quizzes</h3>
                                <p class="text-sm text-gray-500">View and manage your quizzes</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('quiz.index') }}" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-500">
                                View Quizzes
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                @if($user->isPremium())
                <!-- Flashcards (Premium only) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-200">
                    <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="bg-yellow-100 p-3 rounded-full">
                                   <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V4a1 1 0 011-1h2a1 1 0 011 1v3"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <h3 class="text-lg font-medium text-gray-900">Flashcards</h3>
                               <p class="text-sm text-gray-500">Create and study with flashcards</p>
                               <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                   Premium Only
                               </span>
                           </div>
                       </div>
                       <div class="mt-4">
                           <a href="#" class="inline-flex items-center text-sm font-medium text-yellow-600 hover:text-yellow-500">
                               Create Flashcards
                               <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                               </svg>
                           </a>
                       </div>
                   </div>
               </div>
               @else
               <!-- Flashcards (Locked for Free users) -->
               <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg border-2 border-gray-200 opacity-75">
                   <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="bg-gray-200 p-3 rounded-full">
                                   <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <h3 class="text-lg font-medium text-gray-500">Flashcards</h3>
                               <p class="text-sm text-gray-400">Create and study with flashcards</p>
                               <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-200 text-gray-600 mt-1">
                                   Premium Required
                               </span>
                           </div>
                       </div>
                       <div class="mt-4">
                           <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500">
                               Upgrade to Unlock
                               <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                               </svg>
                           </a>
                       </div>
                   </div>
               </div>
               @endif

               <!-- Analytics -->
               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="bg-indigo-100 p-3 rounded-full">
                                   <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <h3 class="text-lg font-medium text-gray-900">Analytics</h3>
                               <p class="text-sm text-gray-500">Track your quiz performance</p>
                               @if($user->isPremium())
                                   <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                                       Enhanced for Premium
                                   </span>
                               @endif
                           </div>
                       </div>
                       <div class="mt-4">
                           <a href="#" class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-500">
                               View Analytics
                               <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                               </svg>
                           </a>
                       </div>
                   </div>
               </div>

               <!-- Tier Comparison -->
               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="bg-orange-100 p-3 rounded-full">
                                   <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <h3 class="text-lg font-medium text-gray-900">Compare Plans</h3>
                               <p class="text-sm text-gray-500">See all features and pricing</p>
                           </div>
                       </div>
                       <div class="mt-4">
                           <a href="{{ route('tier.compare') }}" class="inline-flex items-center text-sm font-medium text-orange-600 hover:text-orange-500">
                               View Comparison
                               <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                               </svg>
                           </a>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Premium Features Showcase for Free Users -->
           @if($user->isFree())
           <div class="mt-8 bg-gradient-to-r from-green-400 to-blue-500 rounded-lg shadow-sm">
               <div class="p-6 text-white">
                   <div class="flex items-center justify-between">
                       <div>
                           <h3 class="text-xl font-bold">Unlock Premium Features</h3>
                           <p class="mt-2 text-green-100">
                               Get unlimited AI generations, flashcards, custom timers, and advanced analytics for just RM5 one-time!
                           </p>
                           <ul class="mt-4 space-y-1 text-sm text-green-100">
                               <li class="flex items-center">
                                   <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   Unlimited AI quiz generations
                               </li>
                               <li class="flex items-center">
                                   <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   AI-powered flashcards
                               </li>
                               <li class="flex items-center">
                                   <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   Custom quiz timers
                               </li>
                               <li class="flex items-center">
                                   <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   Up to 30 questions per quiz
                               </li>
                           </ul>
                       </div>
                       <div class="ml-6">
                           <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-6 py-3 bg-white border border-transparent rounded-md font-semibold text-sm text-green-600 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                               </svg>
                               Upgrade Now
                           </a>
                       </div>
                   </div>
               </div>
           </div>
           @endif
       </div>
   </div>
</x-app-layout>