<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Generated Questions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('quiz.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Quiz Metadata -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quiz Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Quiz Title</label>
                                <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Enter quiz title">
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required 
                                       class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., Mathematics, Science, History">
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="topic" class="block text-sm font-medium text-gray-700 mb-2">Topic</label>
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

                <!-- Generated Questions -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Generated Questions</h3>
                            <span class="text-sm text-gray-600">{{ count($generatedQuestions) }} questions</span>
                        </div>

                        <div class="space-y-8">
                            @foreach($generatedQuestions as $index => $question)
                            <div class="border border-gray-200 rounded-lg p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h4 class="text-md font-medium text-gray-900">Question {{ $index + 1 }}</h4>
                                    <button type="button" class="text-red-600 hover:text-red-800 text-sm" onclick="removeQuestion({{ $index }})">
                                        Remove
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <!-- Question Text -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                                        <textarea name="questions[{{ $index }}][question]" rows="2" required 
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old("questions.{$index}.question", $question['question']) }}</textarea>
                                    </div>

                                    <!-- Answer Options -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @for($i = 1; $i <= 4; $i++)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Option {{ chr(64 + $i) }}
                                                @if($question['correct_answer'] == $i)
                                                    <span class="text-green-600 font-medium">(Correct)</span>
                                                @endif
                                            </label>
                                            <input type="text" name="questions[{{ $index }}][option_{{ $i }}]" required 
                                                   value="{{ old("questions.{$index}.option_{$i}", $question["option_{$i}"]) }}"
                                                   class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        @endfor
                                    </div>

                                    <!-- Correct Answer -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                                        <select name="questions[{{ $index }}][correct_answer]" required 
                                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            @for($i = 1; $i <= 4; $i++)
                                            <option value="{{ $i }}" {{ old("questions.{$index}.correct_answer", $question['correct_answer']) == $i ? 'selected' : '' }}>
                                                Option {{ chr(64 + $i) }}
                                            </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <!-- Explanation -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                                        <textarea name="questions[{{ $index }}][explanation]" rows="2" 
                                                  class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Explain why this answer is correct">{{ old("questions.{$index}.explanation", $question['explanation']) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between">
                    <a href="{{ route('quiz.generator') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Back to Generator
                    </a>
                    
                    <div class="space-x-4">
                        <button type="button" onclick="addQuestion()" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Add Question
                        </button>
                        
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Save Quiz
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let questionCount = {{ count($generatedQuestions) }};

        function removeQuestion(index) {
            if (confirm('Are you sure you want to remove this question?')) {
                const questionDiv = document.querySelector(`[data-question-index="${index}"]`)?.closest('.border');
                if (questionDiv) {
                    questionDiv.remove();
                }
            }
        }

        function addQuestion() {
            const questionsContainer = document.querySelector('.space-y-8');
            const newQuestionHTML = `
                <div class="border border-gray-200 rounded-lg p-6" data-question-index="${questionCount}">
                    <div class="flex justify-between items-start mb-4">
                        <h4 class="text-md font-medium text-gray-900">Question ${questionCount + 1}</h4>
                        <button type="button" class="text-red-600 hover:text-red-800 text-sm" onclick="removeQuestion(${questionCount})">
                            Remove
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Question</label>
                            <textarea name="questions[${questionCount}][question]" rows="2" required 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            ${[1,2,3,4].map(i => `
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Option ${String.fromCharCode(64 + i)}</label>
                                    <input type="text" name="questions[${questionCount}][option_${i}]" required 
                                           class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            `).join('')}
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Correct Answer</label>
                            <select name="questions[${questionCount}][correct_answer]" required 
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                ${[1,2,3,4].map(i => `<option value="${i}">Option ${String.fromCharCode(64 + i)}</option>`).join('')}
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Explanation (Optional)</label>
                            <textarea name="questions[${questionCount}][explanation]" rows="2" 
                                      class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Explain why this answer is correct"></textarea>
                        </div>
                    </div>
                </div>
            `;

            questionsContainer.insertAdjacentHTML('beforeend', newQuestionHTML);
            questionCount++;
        }
    </script>
</x-app-layout>