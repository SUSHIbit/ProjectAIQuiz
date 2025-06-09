<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Quiz Results: {{ $attempt->quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Results Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center mb-6">
                        <div class="mx-auto w-24 h-24 rounded-full flex items-center justify-center mb-4
                            @if($attempt->score_percentage >= 80) bg-green-100
                            @elseif($attempt->score_percentage >= 60) bg-yellow-100
                            @else bg-red-100 @endif">
                            <span class="text-3xl font-bold
                                @if($attempt->score_percentage >= 80) text-green-600
                                @elseif($attempt->score_percentage >= 60) text-yellow-600
                                @else text-red-600 @endif">
                                {{ $attempt->grade }}
                            </span>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ $attempt->score_percentage }}%
                        </h1>
                        <p class="text-lg text-gray-600">
                            {{ $attempt->correct_answers }} out of {{ $attempt->total_questions }} questions correct
                        </p>
                        
                        @if($attempt->isCompleted())
                            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Completed
                            </div>
                        @elseif($attempt->isAbandoned())
                            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Abandoned / Timed Out
                            </div>
                        @endif
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-gray-900">{{ $attempt->total_questions }}</div>
                            <div class="text-sm text-gray-600">Total Questions</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">{{ $attempt->correct_answers }}</div>
                            <div class="text-sm text-gray-600">Correct Answers</div>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <div class="text-2xl font-bold text-red-600">{{ $attempt->total_questions - $attempt->correct_answers }}</div>
                            <div class="text-sm text-gray-600">Incorrect Answers</div>
                        </div>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">{{ $attempt->formatted_time }}</div>
                            <div class="text-sm text-gray-600">Time Taken</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Comparison -->
            @if($previousAttempts->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance History</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempt</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correct</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="bg-blue-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-900">Current</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 font-medium">{{ $attempt->score_percentage }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">{{ $attempt->correct_answers }}/{{ $attempt->total_questions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">{{ $attempt->formatted_time }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900">{{ $attempt->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @foreach($previousAttempts as $index => $prevAttempt)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $previousAttempts->count() - $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prevAttempt->score_percentage }}%</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prevAttempt->correct_answers }}/{{ $prevAttempt->total_questions }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $prevAttempt->formatted_time }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $prevAttempt->created_at->format('M d, Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Detailed Results -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Question-by-Question Review</h3>
                    <div class="space-y-6">
                        @foreach($attempt->quiz->quizItems as $index => $question)
                        @php
                            $answer = $attempt->answers->where('quiz_item_id', $question->id)->first();
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-md font-medium text-gray-900">Question {{ $index + 1 }}</h4>
                                @if($answer)
                                    @if($answer->is_correct)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Correct
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            Incorrect
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Not Answered
                                    </span>
                                @endif
                            </div>

                            <p class="text-gray-800 mb-4">{{ $question->question }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                                @foreach($question->options as $optionNumber => $optionText)
                                <div class="flex items-center p-3 border rounded-md
                                    @if($answer && $answer->selected_answer === $optionNumber && $answer->is_correct) border-green-500 bg-green-50
                                    @elseif($answer && $answer->selected_answer === $optionNumber && !$answer->is_correct) border-red-500 bg-red-50
                                    @elseif($question->correct_answer === $optionNumber) border-green-500 bg-green-50
                                    @else border-gray-200 @endif">
                                    
                                    <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-sm font-medium mr-3
                                        @if($answer && $answer->selected_answer === $optionNumber && $answer->is_correct) bg-green-100 text-green-800
                                        @elseif($answer && $answer->selected_answer === $optionNumber && !$answer->is_correct) bg-red-100 text-red-800
                                        @elseif($question->correct_answer === $optionNumber) bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ chr(64 + $optionNumber) }}
                                    </span>
                                    
                                    <span class="text-gray-800 flex-1">{{ $optionText }}</span>
                                    
                                    @if($answer && $answer->selected_answer === $optionNumber)
                                        <svg class="w-5 h-5 ml-2 {{ $answer->is_correct ? 'text-green-500' : 'text-red-500' }}" fill="currentColor" viewBox="0 0 20 20">
                                            @if($answer->is_correct)
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            @else
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                    @elseif($question->correct_answer === $optionNumber)
                                        <svg class="w-5 h-5 ml-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                                @endforeach
                            </div>

                            @if($answer)
                            <div class="text-sm text-gray-600 mb-3">
                                <strong>Your Answer:</strong> {{ $answer->selected_option_label }} - {{ $answer->selected_option_text }}
                                @if(!$answer->is_correct)
                                    <br><strong>Correct Answer:</strong> {{ $answer->correct_option_label }} - {{ $answer->correct_option_text }}
                                @endif
                                @if($answer->time_spent)
                                    <br><strong>Time Spent:</strong> {{ $answer->time_spent }} seconds
                                @endif
                            </div>
                            @endif

                            @if($question->explanation)
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h5 class="text-sm font-medium text-blue-800">Explanation</h5>
                                        <p class="text-sm text-blue-700 mt-1">{{ $question->explanation }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                <a href="{{ route('quiz.show', $attempt->quiz_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Quiz
                </a>
                
                <a href="{{ route('quiz.attempt.start', $attempt->quiz_id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Retake Quiz
                </a>
                
                <a href="{{ route('quiz.attempt.history', $attempt->quiz_id) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View History
                </a>
                
                <a href="{{ route('quiz.index') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    My Quizzes
                </a>
            </div>
        </div>
    </div>
</x-app-layout>