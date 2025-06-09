<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Manual Quiz') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('manual-quiz.update', $quiz->id) }}" method="POST" class="space-y-6" id="manual-quiz-form">
                @csrf
                @method('PUT')

                <!-- Quiz Metadata -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quiz Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title *</label>
                                <input type="text" id="title" name="title" value="{{ old('title', $quiz->title) }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter quiz title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject', $quiz->subject) }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., Mathematics, Science, History">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="topic" class="block text-sm font-medium text-gray-700 mb-2">Topic *</label>
                                <input type="text" id="topic" name="topic" value="{{ old('topic', $quiz->topic) }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., Algebra, Biology, World War II">
                                @error('topic')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                                <textarea id="description" name="description" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                         placeholder="Brief description of the quiz">{{ old('description', $quiz->description) }}</textarea>
                               @error('description')
                                   <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                               @enderror
                           </div>
                       </div>
                   </div>
               </div>

               <!-- Questions Section -->
               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   <div class="p-6">
                       <div class="flex justify-between items-center mb-6">
                           <h3 class="text-lg font-semibold text-gray-900">Questions</h3>
                           <div class="flex items-center space-x-4">
                               <span class="text-sm text-gray-600">Current: {{ $quiz->quizItems->count() }} questions</span>
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
                           @foreach($quiz->quizItems as $index => $item)
                           <div class="question-item border border-gray-200 rounded-lg p-6" data-question-index="{{ $index }}">
                               <div class="flex justify-between items-center mb-4">
                                   <h4 class="text-md font-medium text-gray-900">Question {{ $index + 1 }}</h4>
                                   <button type="button" class="text-red-600 hover:text-red-800 text-sm {{ $index < 5 ? 'hidden' : '' }}" onclick="removeQuestion({{ $index }})">
                                       Remove
                                   </button>
                               </div>

                               <div class="space-y-4">
                                   <!-- Question Text -->
                                   <div>
                                       <label class="block text-sm font-medium text-gray-700 mb-2">Question *</label>
                                       <textarea name="questions[{{ $index }}][question]" rows="2" required 
                                                 class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                 placeholder="Enter your question here...">{{ old("questions.{$index}.question", $item->question) }}</textarea>
                                   </div>

                                   <!-- Answer Options -->
                                   <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                       <div>
                                           <label class="block text-sm font-medium text-gray-700 mb-2">Option A *</label>
                                           <input type="text" name="questions[{{ $index }}][option_1]" required 
                                                  value="{{ old("questions.{$index}.option_1", $item->option_1) }}"
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Enter option A">
                                       </div>
                                       <div>
                                           <label class="block text-sm font-medium text-gray-700 mb-2">Option B *</label>
                                           <input type="text" name="questions[{{ $index }}][option_2]" required 
                                                  value="{{ old("questions.{$index}.option_2", $item->option_2) }}"
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Enter option B">
                                       </div>
                                       <div>
                                           <label class="block text-sm font-medium text-gray-700 mb-2">Option C *</label>
                                           <input type="text" name="questions[{{ $index }}][option_3]" required 
                                                  value="{{ old("questions.{$index}.option_3", $item->option_3) }}"
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Enter option C">
                                       </div>
                                       <div>
                                           <label class="block text-sm font-medium text-gray-700 mb-2">Option D *</label>
                                           <input type="text" name="questions[{{ $index }}][option_4]" required 
                                                  value="{{ old("questions.{$index}.option_4", $item->option_4) }}"
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Enter option D">
                                       </div>
                                   </div>

                                   <!-- Correct Answer -->
                                   <div>
                                       <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer *</label>
                                       <select name="questions[{{ $index }}][correct_answer]" required 
                                               class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                           <option value="">Select correct answer</option>
                                           <option value="1" {{ old("questions.{$index}.correct_answer", $item->correct_answer) == 1 ? 'selected' : '' }}>Option A</option>
                                           <option value="2" {{ old("questions.{$index}.correct_answer", $item->correct_answer) == 2 ? 'selected' : '' }}>Option B</option>
                                           <option value="3" {{ old("questions.{$index}.correct_answer", $item->correct_answer) == 3 ? 'selected' : '' }}>Option C</option>
                                           <option value="4" {{ old("questions.{$index}.correct_answer", $item->correct_answer) == 4 ? 'selected' : '' }}>Option D</option>
                                       </select>
                                   </div>

                                   <!-- Explanation -->
                                   <div>
                                       <label class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                                       <textarea name="questions[{{ $index }}][explanation]" rows="2" 
                                                 class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                 placeholder="Explain why this answer is correct (optional)">{{ old("questions.{$index}.explanation", $item->explanation) }}</textarea>
                                   </div>
                               </div>
                           </div>
                           @endforeach
                       </div>
                   </div>
               </div>

               <!-- Action Buttons -->
               <div class="flex justify-between">
                   <div class="space-x-2">
                       <a href="{{ route('quiz.show', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                           Cancel
                       </a>
                       
                       <form action="{{ route('manual-quiz.destroy', $quiz->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.')">
                           @csrf
                           @method('DELETE')
                           <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Delete Quiz
                           </button>
                       </form>
                   </div>
                   
                   <button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                       <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                       </svg>
                       Update Quiz
                   </button>
               </div>
           </form>
       </div>
   </div>

   <script>
       let questionCount = {{ $quiz->quizItems->count() }};

       document.addEventListener('DOMContentLoaded', function() {
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

                   if (!questionText || !questionText.value.trim()) {
                       hasError = true;
                       if (questionText) questionText.classList.add('border-red-500');
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

                   if (!correctAnswer || !correctAnswer.value) {
                       hasError = true;
                       if (correctAnswer) correctAnswer.classList.add('border-red-500');
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
                       <button type="button" class="text-red-600 hover:text-red-800 text-sm" onclick="removeQuestion(${index})">
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
           updateQuestionNumbers();
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
               
               // Update data attribute
               question.setAttribute('data-question-index', index);
               
               // Update name attributes for form inputs
               const inputs = question.querySelectorAll('input, textarea, select');
               inputs.forEach(input => {
                   const name = input.getAttribute('name');
                   if (name && name.includes('questions[')) {
                       const newName = name.replace(/questions\[\d+\]/, `questions[${index}]`);
                       input.setAttribute('name', newName);
                   }
               });
           });
       }
   </script>
</x-app-layout>