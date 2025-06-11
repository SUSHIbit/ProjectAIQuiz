<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Analytics - Premium Feature') }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
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

                <!-- Pricing Info -->
                <div class="bg-gradient-to-r from-slate-600 to-slate-800 rounded-lg p-6 text-white mb-8">
                    <h3 class="text-xl font-semibold mb-2">Premium Subscription</h3>
                    <div class="text-3xl font-bold mb-2">RM5 <span class="text-lg font-normal">one-time payment</span></div>
                    <p class="text-slate-200">Get lifetime access to all premium features</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('tier.upgrade') }}" class="inline-flex items-center px-6 py-3 bg-slate-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wide hover:bg-slate-700 focus:bg-slate-700 active:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        Upgrade to Premium
                    </a>
                    
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-white border border-slate-300 rounded-lg font-semibold text-sm text-slate-700 uppercase tracking-wide hover:bg-slate-50 focus:bg-slate-50 active:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>

                <!-- Additional Info -->
                <div class="mt-8 text-sm text-slate-500">
                    <p>Already have Premium? <a href="{{ route('analytics.dashboard') }}" class="text-slate-600 hover:text-slate-900 font-medium">Access Analytics →</a></p>
                </div>
            </div>
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