<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Start Quiz') }}: {{ $quiz->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Quiz Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                            <div class="space-y-2 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Subject: {{ $quiz->subject }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Topic: {{ $quiz->topic }}
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Questions: {{ $quiz->total_questions }}
                                </div>
                                @if($quiz->max_questions_allowed && $quiz->max_questions_allowed !== $quiz->total_questions)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Question Limit: {{ $quiz->max_questions_allowed }} (based on tier when created)
                                </div>
                                @endif
                            </div>
                            @if($quiz->description)
                                <p class="mt-3 text-gray-700">{{ $quiz->description }}</p>
                            @endif
                        </div>
                        
                        <div class="flex flex-col space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $quiz->source_badge_color }}">
                                {{ $quiz->isAiGenerated() ? 'AI Generated' : 'Manual' }}
                            </span>
                            @if($quiz->max_questions_allowed > 10)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Premium Quiz
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <form action="{{ route('quiz.attempt.create', $quiz->id) }}" method="POST" id="start-quiz-form">
                    @csrf
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quiz Settings</h3>
                        
                        <!-- Timer Settings (Premium Feature) -->
                        @if(auth()->user()->isPremium())
                        <div class="border border-green-200 rounded-lg p-4 bg-green-50 mb-6">
                            <div class="flex items-center mb-4">
                                <h4 class="text-md font-medium text-gray-900">Timer Settings</h4>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Premium Feature
                                </span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" id="enable_timer" name="enable_timer" value="1" 
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700">Enable Timer</span>
                                    </label>
                                </div>
                                
                                <div id="timer_settings" class="hidden">
                                    <label for="timer_duration" class="block text-sm font-medium text-gray-700 mb-2">Timer Duration</label>
                                    <select id="timer_duration" name="timer_duration" 
                                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 max-w-xs">
                                        <option value="5">5 minutes</option>
                                        <option value="10">10 minutes</option>
                                        <option value="15" selected>15 minutes</option>
                                        <option value="20">20 minutes</option>
                                        <option value="30">30 minutes</option>
                                        <option value="45">45 minutes</option>
                                        <option value="60">60 minutes</option>
                                    </select>
                                    <p class="text-sm text-gray-500 mt-1">
                                        The quiz will automatically submit when time runs out.
                                    </p>
                                </div>
                            </div>

                            <!-- Timer Benefits -->
                            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                <h5 class="text-sm font-medium text-blue-900 mb-2">Timer Benefits:</h5>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Simulate real exam conditions
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Improve time management skills
                                    </li>
                                    <li class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Track time spent per question
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @else
                        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mb-6">
                            <div class="flex items-center mb-2">
                                <svg class="w-5 h-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <h4 class="text-md font-medium text-gray-700">Timer Settings</h4>
                                <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-600">
                                    Premium Required
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">
                                Timer features are available for Premium users. 
                                <a href="{{ route('tier.upgrade') }}" class="text-blue-600 hover:text-blue-500">Upgrade now</a> 
                                to enable custom timers for your quizzes.
                            </p>
                            
                            <!-- Show what premium users get -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                <h5 class="text-sm font-medium text-yellow-900 mb-2">Premium Timer Features:</h5>
                                <ul class="text-sm text-yellow-800 space-y-1">
                                    <li>• Customizable timer (5-60 minutes)</li>
                                    <li>• Auto-submit when time expires</li>
                                    <li>• Time tracking per question</li>
                                    <li>• Real exam simulation</li>
                                </ul>
                            </div>
                        </div>
                        @endif

                        <!-- Quiz Instructions -->
                        <div class="mt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-3">Instructions</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Answer all {{ $quiz->total_questions }} questions to complete the quiz
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Each question has 4 multiple choice options (A, B, C, D)
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    You can navigate between questions using the Next/Previous buttons
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-blue-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   Submit the quiz when you're ready to see your results
                               </li>
                               @if(auth()->user()->isPremium())
                               <li class="flex items-start">
                                   <svg class="w-4 h-4 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   If timer is enabled, the quiz will auto-submit when time expires
                               </li>
                               @endif
                           </ul>
                       </div>

                       <!-- Previous Attempts -->
                       @if($quiz->hasBeenTakenBy(auth()->user()))
                       <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                           <h4 class="text-md font-medium text-blue-900 mb-2">Previous Attempts</h4>
                           @php
                               $bestAttempt = $quiz->getBestAttemptFor(auth()->user());
                               $attemptCount = $quiz->getAttemptCountFor(auth()->user());
                           @endphp
                           <div class="text-sm text-blue-800">
                               <p>You have taken this quiz {{ $attemptCount }} time{{ $attemptCount !== 1 ? 's' : '' }}.</p>
                               @if($bestAttempt)
                               <p>Your best score: <span class="font-medium">{{ $bestAttempt->score_percentage }}%</span> ({{ $bestAttempt->correct_answers }}/{{ $bestAttempt->total_questions }} correct)</p>
                               @endif
                               <a href="{{ route('quiz.attempt.history', $quiz->id) }}" class="text-blue-600 hover:text-blue-500">View attempt history →</a>
                           </div>
                       </div>
                       @endif
                   </div>

                   <!-- Action Buttons -->
                   <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between">
                       <a href="{{ route('quiz.show', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                           <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                           </svg>
                           Back to Quiz
                       </a>
                       
                       <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                           <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-3-4V5a1 1 0 011-1h1a1 1 0 011 1v2M7 7V4a1 1 0 011-1h8a1 1 0 011 1v3"></path>
                           </svg>
                           Start Quiz
                       </button>
                   </div>
               </form>
           </div>
       </div>
   </div>

   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const enableTimer = document.getElementById('enable_timer');
           const timerSettings = document.getElementById('timer_settings');
           
           if (enableTimer && timerSettings) {
               enableTimer.addEventListener('change', function() {
                   if (this.checked) {
                       timerSettings.classList.remove('hidden');
                   } else {
                       timerSettings.classList.add('hidden');
                   }
               });
           }
       });
   </script>
</x-app-layout>