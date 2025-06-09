<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Manual Quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('manual-quiz.store') }}" method="POST" class="space-y-6" id="manual-quiz-form">
                @csrf

                <!-- Quiz Metadata -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quiz Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter quiz title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., Mathematics, Science, History">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="topic" class="block text-sm font-medium text-gray-700 mb-2">Topic *</label>
                                <input type="text" id="topic" name="topic" value="{{ old('topic') }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., Algebra, Biology, World War II">
                                @error('topic')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                                <textarea id="description" name="description" rows="3" 
                                          class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Brief description of the quiz">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timer Settings (Premium Feature Preview) -->
                @if($user->isPremium())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-2 border-green-200">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Timer Settings</h3>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Premium Feature
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="enable_timer" name="enable_timer" value="1" 
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Enable Timer</span>
                                </label>
                            </div>
                            
                            <div id="timer_duration_container" class="hidden">
                                <label for="timer_duration" class="block text-sm font-medium text-gray-700 mb-2">Timer Duration (minutes)</label>
                                <select id="timer_duration" name="timer_duration" 
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="5">5 minutes</option>
                                    <option value="10">10 minutes</option>
                                    <option value="15">15 minutes</option>
                                    <option value="20">20 minutes</option>
                                    <option value="30">30 minutes</option>
                                    <option value="45">45 minutes</option>
                                    <option value="60">60 minutes</option>
                                </select>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-500 mt-2">
                            Set a custom timer for quiz takers. When enabled, the quiz will automatically submit when time runs out.
                        </p>
                    </div>
                </div>
                @endif

                <!-- Questions Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Questions</h3>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">Minimum: 5 questions</span>
                                <button type="button" onclick="addQuestion()" 
                                        class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Question
                                </button>
                            </div>
                        </div>

                        <div id="questions-container" class="space-y-8">
                            <!-- Initial 5 questions will be added by JavaScript -->
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('quiz.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Cancel
                    </a>
                    
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Create Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let questionCount = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Add initial 5 questions
            for (let i = 0; i < 5; i++) {
                addQuestion();
            }

            // Timer toggle functionality
            const enableTimer = document.getElementById('enable_timer');
            const timerContainer = document.getElementById('timer_duration_container');
            
            if (enableTimer && timerContainer) {
                enableTimer.addEventListener('change', function() {
                    if (this.checked) {
                        timerContainer.classList.remove('hidden');
                    } else {
                        timerContainer.classList.add('hidden');
                    }
                });
            }

            // Form validation
            const form = document.getElementById('manual-quiz-form');
            form.addEventListener('submit', function(e) {
                const questions = document.querySelectorAll('.question-item');
                if (questions.length < 5) {
                    e.preventDefault();
                    alert('Please add at least 5 questions.');
                    return;
                }

                // Validate each question
                let hasError = false;
                questions.forEach((question, index) => {
                    const questionText = question.querySelector(`[name="questions[${index}][question]"]`);
                    const options = question.querySelectorAll(`[name^="questions[${index}][option_"]`);
                    const correctAnswer = question.querySelector(`[name="questions[${index}][correct_answer]"]`);

                    if (!questionText.value.trim()) {
                        hasError = true;
                        questionText.classList.add('border-red-500');
                    } else {
                        questionText.classList.remove('border-red-500');
                    }

                    options.forEach(option => {
                        if (!option.value.trim()) {
                            hasError = true;
                            option.classList.add('border-red-500');
                        } else {
                            option.classList.remove('border-red-500');
                        }
                    });

                    if (!correctAnswer.value) {
                        hasError = true;
                        correctAnswer.classList.add('border-red-500');
                    } else {
                        correctAnswer.classList.remove('border-red-500');
                    }
                });

                if (hasError) {
                    e.preventDefault();
                    alert('Please fill in all required fields for each question.');
                }
            });
        });

        function addQuestion() {
            const container = document.getElementById('questions-container');
            const index = questionCount;
            
            const questionHTML = `
                <div class="question-item border border-gray-200 rounded-lg p-6" data-question-index="${index}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-md font-medium text-gray-900">Question ${index + 1}</h4>
                        <button type="button" class="text-red-600 hover:text-red-800 text-sm ${index < 5 ? 'hidden' : ''}" onclick="removeQuestion(${index})">
                            Remove
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Question Text -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                            <textarea name="questions[${index}][question]" rows="2" required 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Enter your question here..."></textarea>
                        </div>

                        <!-- Answer Options -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Option A *</label>
                                <input type="text" name="questions[${index}][option_1]" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter option A">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Option B *</label>
                                <input type="text" name="questions[${index}][option_2]" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter option B">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Option C *</label>
                                <input type="text" name="questions[${index}][option_3]" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter option C">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Option D *</label>
                                <input type="text" name="questions[${index}][option_4]" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter option D">
                            </div>
                        </div>

                        <!-- Correct Answer -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                            <select name="questions[${index}][correct_answer]" required 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select correct answer</option>
                                <option value="1">Option A</option>
                                <option value="2">Option B</option>
                                <option value="3">Option C</option>
                                <option value="4">Option D</option>
                            </select>
                        </div>

                        <!-- Explanation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                            <textarea name="questions[${index}][explanation]" rows="2" 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Explain why this answer is correct (optional)"></textarea>
                        </div>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', questionHTML);
            questionCount++;
        }

        function removeQuestion(index) {
            if (document.querySelectorAll('.question-item').length <= 5) {
                alert('You must have at least 5 questions.');
                return;
            }

            if (confirm('Are you sure you want to remove this question?')) {
                const questionDiv = document.querySelector(`[data-question-index="${index}"]`);
                if (questionDiv) {
                    questionDiv.remove();
                    updateQuestionNumbers();
                }
            }
        }

        function updateQuestionNumbers() {
            const questions = document.querySelectorAll('.question-item');
            questions.forEach((question, index) => {
                const header = question.querySelector('h4');
                if (header) {
                    header.textContent = `Question ${index + 1}`;
                }
            });
        }
    </script>
</x-app-layout>