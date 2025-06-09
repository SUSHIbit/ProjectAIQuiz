<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Taking Quiz: {{ $attempt->quiz->title }}
            </h2>
            @if($attempt->time_limit)
            <div id="timer-display" class="text-lg font-bold text-red-600">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span id="time-remaining">{{ $attempt->formatted_time_remaining }}</span>
                </div>
            </div>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Progress Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm text-gray-500">
                            Question {{ $currentIndex + 1 }} of {{ $attempt->quiz->total_questions }}
                        </span>
                    </div>
                    @php
                        $progress = (($attempt->answers->count()) / $attempt->quiz->total_questions) * 100;
                    @endphp
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progress }}%"></div>
                    </div>
                    
                    <!-- Timer Progress Bar (if timer enabled) -->
                    @if($attempt->time_limit)
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-xs font-medium text-gray-600">Time Remaining</span>
                            <span class="text-xs text-gray-500">
                                {{ $attempt->timer_duration_in_minutes }} minute{{ $attempt->timer_duration_in_minutes !== 1 ? 's' : '' }} total
                            </span>
                        </div>
                        @php
                            $timeProgress = $attempt->time_remaining ? (($attempt->time_remaining / $attempt->time_limit) * 100) : 0;
                        @endphp
                        <div class="w-full bg-red-200 rounded-full h-1">
                            <div id="timer-progress" class="bg-red-500 h-1 rounded-full transition-all duration-1000" style="width: {{ $timeProgress }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Question Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Question {{ $currentIndex + 1 }}</h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            @if($attempt->time_limit)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Timed Quiz</span>
                            @endif
                        </div>
                    </div>
                    
                    <p class="text-gray-800 mb-6 text-lg leading-relaxed">{{ $currentQuestion->question }}</p>
                    
                    <form id="answer-form" class="space-y-4">
                        @csrf
                        <input type="hidden" name="quiz_item_id" value="{{ $currentQuestion->id }}">
                        <input type="hidden" name="question_start_time" value="{{ time() }}">
                        
                        @foreach($currentQuestion->options as $optionNumber => $optionText)
                        <label class="flex items-start p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 hover:bg-blue-50 transition-colors answer-option">
                            <input type="radio" name="answer" value="{{ $optionNumber }}" class="mt-1 mr-4 text-blue-600 focus:ring-blue-500">
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <span class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center text-sm font-medium mr-3">
                                        {{ chr(64 + $optionNumber) }}
                                    </span>
                                    <span class="text-gray-800">{{ $optionText }}</span>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </form>
                </div>
            </div>

            <!-- Navigation & Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div class="flex space-x-3">
                        @if($currentIndex > 0)
                        <button id="prev-btn" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Previous
                        </button>
                        @endif
                        
                        <button id="abandon-btn" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Abandon Quiz
                        </button>
                    </div>

                    <div class="flex space-x-3">
                        @if($attempt->answers->count() >= $attempt->quiz->total_questions - 1)
                        <button id="submit-quiz-btn" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Submit Quiz
                        </button>
                        @else
                        <button id="next-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150" disabled>
                            Next
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Question Overview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h4 class="text-md font-medium text-gray-900 mb-4">Question Overview</h4>
                   <div class="grid grid-cols-10 gap-2">
                       @foreach($attempt->quiz->quizItems as $index => $item)
                       @php
                           $isAnswered = $attempt->answers->where('quiz_item_id', $item->id)->count() > 0;
                           $isCurrent = $item->id === $currentQuestion->id;
                       @endphp
                       <button class="question-nav-btn w-10 h-10 text-sm font-medium rounded-lg border-2 transition-colors
                           @if($isCurrent) border-blue-500 bg-blue-500 text-white
                           @elseif($isAnswered) border-green-500 bg-green-100 text-green-800
                           @else border-gray-300 bg-white text-gray-600 hover:border-gray-400 @endif"
                           data-question-id="{{ $item->id }}"
                           data-question-index="{{ $index }}">
                           {{ $index + 1 }}
                       </button>
                       @endforeach
                   </div>
                   <div class="flex items-center justify-center space-x-6 mt-4 text-sm">
                       <div class="flex items-center">
                           <div class="w-4 h-4 bg-green-100 border-2 border-green-500 rounded mr-2"></div>
                           <span class="text-gray-600">Answered</span>
                       </div>
                       <div class="flex items-center">
                           <div class="w-4 h-4 bg-blue-500 border-2 border-blue-500 rounded mr-2"></div>
                           <span class="text-gray-600">Current</span>
                       </div>
                       <div class="flex items-center">
                           <div class="w-4 h-4 bg-white border-2 border-gray-300 rounded mr-2"></div>
                           <span class="text-gray-600">Unanswered</span>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <!-- Loading Overlay -->
   <div id="loading-overlay" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
       <div class="flex items-center justify-center min-h-screen">
           <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
               <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
               <p class="text-center text-gray-600">Submitting answer...</p>
           </div>
       </div>
   </div>

   <!-- Timer Warning Modal -->
   @if($attempt->time_limit)
   <div id="timer-warning-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
       <div class="flex items-center justify-center min-h-screen">
           <div class="bg-white rounded-lg p-6 max-w-md mx-auto">
               <div class="flex items-center mb-4">
                   <svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                   </svg>
                   <h3 class="text-lg font-medium text-gray-900">Time Warning!</h3>
               </div>
               <p class="text-gray-600 mb-4">You have less than 2 minutes remaining. The quiz will auto-submit when time expires.</p>
               <div class="flex justify-end">
                   <button id="close-warning" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                       Continue Quiz
                   </button>
               </div>
           </div>
       </div>
   </div>
   @endif

   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const attemptId = {{ $attempt->id }};
           const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
           let questionStartTime = Date.now();
           let warningShown = false;
           
           // Timer functionality
           @if($attempt->time_limit)
           let timeRemaining = {{ $attempt->time_remaining ?? 0 }};
           const timerDisplay = document.getElementById('time-remaining');
           const timerProgress = document.getElementById('timer-progress');
           const timerWarningModal = document.getElementById('timer-warning-modal');
           const closeWarning = document.getElementById('close-warning');
           
           function updateTimer() {
               if (timeRemaining <= 0) {
                   // Time's up - auto submit
                   autoSubmitQuiz();
                   return;
               }
               
               const minutes = Math.floor(timeRemaining / 60);
               const seconds = timeRemaining % 60;
               timerDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
               
               // Update progress bar
               const progressPercent = (timeRemaining / {{ $attempt->time_limit }}) * 100;
               timerProgress.style.width = progressPercent + '%';
               
               // Change color when time is running low
               if (timeRemaining <= 120) { // 2 minutes
                   timerDisplay.className = 'text-lg font-bold text-red-600 animate-pulse';
                   timerProgress.className = 'bg-red-600 h-1 rounded-full transition-all duration-1000';
                   
                   // Show warning modal once
                   if (!warningShown && timeRemaining <= 120) {
                       warningShown = true;
                       timerWarningModal.classList.remove('hidden');
                   }
               } else if (timeRemaining <= 300) { // 5 minutes
                   timerDisplay.className = 'text-lg font-bold text-orange-600';
                   timerProgress.className = 'bg-orange-500 h-1 rounded-full transition-all duration-1000';
               } else {
                   timerDisplay.className = 'text-lg font-bold text-green-600';
                   timerProgress.className = 'bg-green-500 h-1 rounded-full transition-all duration-1000';
               }
               
               timeRemaining--;
           }
           
           // Update timer every second
           updateTimer();
           const timerInterval = setInterval(updateTimer, 1000);
           
           // Close warning modal
           if (closeWarning) {
               closeWarning.addEventListener('click', function() {
                   timerWarningModal.classList.add('hidden');
               });
           }
           
           function autoSubmitQuiz() {
               clearInterval(timerInterval);
               alert('Time\'s up! Quiz will be submitted automatically.');
               
               fetch(`/quiz-attempt/${attemptId}/submit`, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': csrfToken
                   }
               })
               .then(response => response.json())
               .then(data => {
                   window.location.href = `/quiz-attempt/${attemptId}/result`;
               })
               .catch(error => {
                   console.error('Auto-submit error:', error);
                   window.location.href = `/quiz-attempt/${attemptId}/result`;
               });
           }
           @endif

           // Answer selection handling
           const answerOptions = document.querySelectorAll('input[name="answer"]');
           const nextBtn = document.getElementById('next-btn');
           const submitQuizBtn = document.getElementById('submit-quiz-btn');
           
           answerOptions.forEach(option => {
               option.addEventListener('change', function() {
                   // Enable next/submit button
                   if (nextBtn) nextBtn.disabled = false;
                   if (submitQuizBtn) submitQuizBtn.disabled = false;
                   
                   // Highlight selected option
                   document.querySelectorAll('.answer-option').forEach(opt => {
                       opt.classList.remove('border-blue-500', 'bg-blue-50');
                       opt.classList.add('border-gray-200');
                   });
                   
                   this.closest('.answer-option').classList.remove('border-gray-200');
                   this.closest('.answer-option').classList.add('border-blue-500', 'bg-blue-50');
               });
           });

           // Next button handler
           if (nextBtn) {
               nextBtn.addEventListener('click', function() {
                   const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                   if (!selectedAnswer) {
                       alert('Please select an answer before proceeding.');
                       return;
                   }
                   
                   submitAnswer(selectedAnswer.value);
               });
           }

           // Submit quiz button handler
           if (submitQuizBtn) {
               submitQuizBtn.addEventListener('click', function() {
                   const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                   if (selectedAnswer) {
                       submitAnswer(selectedAnswer.value, true);
                   } else {
                       // Submit without answering current question
                       if (confirm('You haven\'t answered this question. Submit quiz anyway?')) {
                           submitQuiz();
                       }
                   }
               });
           }

           // Abandon quiz button
           document.getElementById('abandon-btn').addEventListener('click', function() {
               if (confirm('Are you sure you want to abandon this quiz? Your progress will be lost.')) {
                   @if($attempt->time_limit)
                   clearInterval(timerInterval);
                   @endif
                   
                   fetch(`/quiz-attempt/${attemptId}/abandon`, {
                       method: 'POST',
                       headers: {
                           'Content-Type': 'application/json',
                           'X-CSRF-TOKEN': csrfToken
                       }
                   })
                   .then(response => {
                       if (response.ok) {
                           window.location.href = '/quiz/{{ $attempt->quiz_id }}';
                       }
                   });
               }
           });

           // Question navigation
           document.querySelectorAll('.question-nav-btn').forEach(btn => {
               btn.addEventListener('click', function() {
                   const questionIndex = parseInt(this.dataset.questionIndex);
                   const currentIndex = {{ $currentIndex }};
                   
                   if (questionIndex === currentIndex) return;
                   
                   // Save current answer if selected
                   const selectedAnswer = document.querySelector('input[name="answer"]:checked');
                   if (selectedAnswer) {
                       submitAnswer(selectedAnswer.value, false, questionIndex);
                   } else {
                       // Navigate without saving
                       navigateToQuestion(questionIndex);
                   }
               });
           });

           function submitAnswer(answer, isSubmitting = false, navigateToIndex = null) {
               const loadingOverlay = document.getElementById('loading-overlay');
               loadingOverlay.classList.remove('hidden');
               
               const timeSpent = Math.floor((Date.now() - questionStartTime) / 1000);
               const quizItemId = document.querySelector('input[name="quiz_item_id"]').value;
               
               fetch(`/quiz-attempt/${attemptId}/answer`, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': csrfToken
                   },
                   body: JSON.stringify({
                       quiz_item_id: quizItemId,
                       answer: parseInt(answer),
                       time_spent: timeSpent
                   })
               })
               .then(response => response.json())
               .then(data => {
                   loadingOverlay.classList.add('hidden');
                   
                   if (data.success) {
                       if (data.completed || isSubmitting) {
                           @if($attempt->time_limit)
                           clearInterval(timerInterval);
                           @endif
                           window.location.href = data.redirect || `/quiz-attempt/${attemptId}/result`;
                       } else if (navigateToIndex !== null) {
                           navigateToQuestion(navigateToIndex);
                       } else {
                           // Move to next question
                           const nextIndex = {{ $currentIndex }} + 1;
                           if (nextIndex < {{ $attempt->quiz->total_questions }}) {
                               navigateToQuestion(nextIndex);
                           }
                       }
                       
                       // Update time remaining if provided
                       if (data.time_remaining !== undefined) {
                           timeRemaining = data.time_remaining;
                       }
                   } else {
                       if (data.redirect) {
                           @if($attempt->time_limit)
                           clearInterval(timerInterval);
                           @endif
                           window.location.href = data.redirect;
                       } else {
                           alert('Error submitting answer: ' + data.message);
                       }
                   }
               })
               .catch(error => {
                   loadingOverlay.classList.add('hidden');
                   console.error('Error:', error);
                   alert('An error occurred while submitting your answer. Please try again.');
               });
           }

           function submitQuiz() {
               @if($attempt->time_limit)
               clearInterval(timerInterval);
               @endif
               
               fetch(`/quiz-attempt/${attemptId}/submit`, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': csrfToken
                   }
               })
               .then(response => {
                   if (response.ok) {
                       window.location.href = `/quiz-attempt/${attemptId}/result`;
                   }
               });
           }

           function navigateToQuestion(index) {
               const questionButtons = document.querySelectorAll('.question-nav-btn');
               const questionId = questionButtons[index].dataset.questionId;
               
               // Update URL to show different question
               window.location.href = `/quiz-attempt/${attemptId}/take?question=${index}`;
           }

           // Auto-save on page unload
           window.addEventListener('beforeunload', function(e) {
               const selectedAnswer = document.querySelector('input[name="answer"]:checked');
               if (selectedAnswer) {
                   // Use sendBeacon for reliable sending on page unload
                   const timeSpent = Math.floor((Date.now() - questionStartTime) / 1000);
                   const quizItemId = document.querySelector('input[name="quiz_item_id"]').value;
                   
                   navigator.sendBeacon(`/quiz-attempt/${attemptId}/answer`, JSON.stringify({
                       quiz_item_id: quizItemId,
                       answer: parseInt(selectedAnswer.value),
                       time_spent: timeSpent,
                       _token: csrfToken
                   }));
               }
           });
       });
   </script>
</x-app-layout>