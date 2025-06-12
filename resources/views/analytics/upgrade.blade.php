<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Analytics - Premium Feature') }}
        </h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <!-- Upgrade Required Card -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8 text-center">
                <!-- Lock Icon -->
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-slate-100 mb-6">
                    <svg class="h-8 w-8 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>

                <!-- Heading -->
                <h1 class="text-3xl font-bold text-slate-900 mb-4">Analytics Requires Premium</h1>
                <p class="text-lg text-slate-600 mb-8">
                    Unlock detailed performance insights, progress tracking, and comprehensive analytics with a Premium subscription.
                </p>

                <!-- Feature List -->
                <div class="bg-slate-50 rounded-lg p-6 mb-8 text-left max-w-2xl mx-auto">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4 text-center">Premium Analytics Features:</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700">Detailed quiz performance tracking</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700">Subject and topic performance breakdown</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700">Score progression and improvement trends</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700">Time spent analytics and efficiency metrics</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700">Export analytics data for personal records</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-5 w-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-slate-700">Advanced charts and visual insights</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Plan Selection Cards -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Monthly Plan -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border border-gray-200">
                <div class="p-6">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Monthly Plan</h3>
                        <div class="mb-4">
                            <span class="text-3xl font-bold text-blue-600">RM15</span>
                            <span class="text-gray-500 ml-1">/month</span>
                        </div>
                        <p class="text-gray-600 mb-6">Perfect for trying analytics</p>
                    </div>
                    
                    <ul class="space-y-3 mb-6 text-sm">
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">All analytics features</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">Unlimited AI generations</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">AI-powered flashcards</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">Up to 30 questions per quiz</span>
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('tier.process-upgrade') }}" class="w-full">
                        @csrf
                        <input type="hidden" name="plan_type" value="monthly">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Subscribe Monthly - RM15
                        </button>
                    </form>
                </div>
            </div>

            <!-- Yearly Plan -->
            <div class="bg-white overflow-hidden shadow-lg rounded-2xl border-2 border-green-500 relative">
                <!-- Recommended Badge -->
                <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-500 text-white">
                        Best Value
                    </span>
                </div>

                <div class="p-6 pt-8">
                    <div class="text-center">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Yearly Plan</h3>
                        <div class="mb-2">
                            <span class="text-3xl font-bold text-green-600">RM120</span>
                            <span class="text-gray-500 ml-1">/year</span>
                        </div>
                        <div class="mb-4">
                            <span class="text-green-600 font-medium">Save RM60!</span>
                            <span class="text-sm text-gray-500 block">(RM10/month)</span>
                        </div>
                        <p class="text-gray-600 mb-6">Best value for data enthusiasts</p>
                    </div>
                    
                    <ul class="space-y-3 mb-6 text-sm">
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900 font-medium">Everything in Monthly</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900 font-medium">33% savings (RM60 off)</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">Priority support</span>
                        </li>
                        <li class="flex items-center">
                            <svg class="h-4 w-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-900">Early access to features</span>
                        </li>
                    </ul>

                    <form method="POST" action="{{ route('tier.process-upgrade') }}" class="w-full">
                        @csrf
                        <input type="hidden" name="plan_type" value="yearly">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                            Subscribe Yearly - RM120
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 text-center">
            <p class="text-sm text-slate-500 mb-4">Already have Premium? <a href="{{ route('analytics.dashboard') }}" class="text-slate-600 hover:text-slate-900 font-medium">Access Analytics →</a></p>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Dashboard
            </a>
        </div>

        <!-- Feature Preview -->
        <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-slate-900 mb-4">Analytics Preview</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-50 p-4 rounded-lg opacity-75">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-600">Total Attempts</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-400">••</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg opacity-75">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-600">Average Score</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-400">••%</div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-lg opacity-75">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-slate-600">Study Time</span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div class="text-2xl font-bold text-slate-400">••h</div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="text-sm text-slate-500">Unlock these insights and more with Premium</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>