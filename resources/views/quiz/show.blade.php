<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Quiz Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Subject: {{ $quiz->subject }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Topic: {{ $quiz->topic }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $quiz->total_questions }} Questions
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quiz->source_badge_color }}">
                                    {{ $quiz->isAiGenerated() ? 'AI Generated' : 'Manual' }}
                                </span>
                            </div>
                            @if($quiz->description)
                                <p class="mt-3 text-gray-700">{{ $quiz->description }}</p>
                            @endif
                        </div>
                        
                        <div class="flex space-x-2">
                            <!-- UPDATED: Add Take Quiz Button -->
                            <a href="{{ route('quiz.attempt.start', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-3-4V5a1 1 0 011-1h1a1 1 0 011 1v2M7 7V4a1 1 0 011-1h8a1 1 0 011 1v3"></path>
                                </svg>
                                Take Quiz
                            </a>
                            @if($quiz->isManual())
                                <a href="{{ route('manual-quiz.edit', $quiz->id) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Quiz
                                </a>
                            @endif
                            <button class="inline-flex items-center px-3 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Export PDF
                            </button>
                        </div>
                    </div>

                    <!-- NEW: Add Quiz Statistics Section -->
                    @if($quiz->hasBeenTakenBy(auth()->user()))
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-md font-medium text-gray-900 mb-3">Your Performance</h3>
                        @php
                            $bestAttempt = $quiz->getBestAttemptFor(auth()->user());
                            $latestAttempt = $quiz->getLatestAttemptFor(auth()->user());
                            $attemptCount = $quiz->getAttemptCountFor(auth()->user());
                        @endphp
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <div class="text-lg font-bold text-blue-600">{{ $attemptCount }}</div>
                                <div class="text-xs text-gray-600">Attempts</div>
                            </div>
                            @if($bestAttempt)
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <div class="text-lg font-bold text-green-600">{{ $bestAttempt->score_percentage }}%</div>
                                <div class="text-xs text-gray-600">Best Score</div>
                            </div>
                            @endif
                            @if($latestAttempt)
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-lg font-bold text-purple-600">{{ $latestAttempt->score_percentage }}%</div>
                                <div class="text-xs text-gray-600">Latest Score</div>
                            </div>
                            @endif
                        </div>
                        <div class="mt-3 text-center">
                            <a href="{{ route('quiz.attempt.history', $quiz->id) }}" class="text-sm text-blue-600 hover:text-blue-500">
                                View full attempt history →
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quiz Questions -->
            <div class="space-y-6">
                @foreach($quiz->quizItems as $index => $item)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Question {{ $index + 1 }}</h3>
                            <span class="text-sm text-gray-500">{{ $item->order }}</span>
                        </div>
                        
                        <p class="text-gray-800 mb-4">{{ $item->question }}</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                            @foreach($item->options as $optionNumber => $optionText)
                            <div class="flex items-center p-3 border rounded-md {{ $item->correct_answer === $optionNumber ? 'border-green-500 bg-green-50' : 'border-gray-200' }}">
                                <span class="flex-shrink-0 w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                                    {{ chr(64 + $optionNumber) }}
                                </span>
                                <span class="text-gray-800">{{ $optionText }}</span>
                                @if($item->correct_answer === $optionNumber)
                                    <svg class="ml-auto w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        
                        @if($item->explanation)
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-blue-800">Explanation</h4>
                                    <p class="text-sm text-blue-700 mt-1">{{ $item->explanation }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Back Button -->
            <div class="mt-6 text-center">
                <a href="{{ route('quiz.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to My Quizzes
                </a>
            </div>
        </div>
    </div>
</x-app-layout>