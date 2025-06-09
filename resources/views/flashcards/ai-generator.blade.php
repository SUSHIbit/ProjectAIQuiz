<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Flashcard Generator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- File Info Display -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Uploaded File</h3>
                            <p class="text-gray-600 mt-1">{{ $uploadedFile['original_name'] ?? 'Unknown file' }}</p>
                            <p class="text-sm text-gray-500">Size: {{ number_format(($uploadedFile['size'] ?? 0) / 1024, 2) }} KB</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Ready for Processing
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generation Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Generation Settings</h3>
                    
                    <form id="generation-form">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Flashcard Count -->
                            <div>
                                <label for="flashcard_count" class="block text-sm font-medium text-gray-700 mb-2">
                                    Number of Flashcards <span class="text-red-500">*</span>
                                </label>
                                <select id="flashcard_count" name="flashcard_count" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="10" selected>10 flashcards</option>
                                    <option value="20">20 flashcards</option>
                                    <option value="30">30 flashcards</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Choose how many flashcards to generate from your content</p>
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                    Category (Optional)
                                </label>
                                <input type="text" id="category" name="category" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g., Biology, History, Programming...">
                                <p class="mt-1 text-xs text-gray-500">Help organize your flashcards by subject or topic</p>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-4">
                            <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Upload Different File
                            </a>
                            <button type="submit" id="generate-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Generate Flashcards
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="bg-white overflow-hidden shadow-sm sm:rounded-lg hidden">
                <div class="p-8 text-center">
                    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Generating Flashcards...</h3>
                    <p class="text-gray-600">Our AI is analyzing your content and creating educational flashcards. This may take a moment.</p>
                    <div class="mt-4">
                        <div class="bg-gray-200 rounded-full h-2 w-64 mx-auto">
                            <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-1000" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error State -->
            <div id="error-state" class="bg-red-50 border border-red-200 rounded-md p-4 hidden">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Generation Failed</h3>
                        <p id="error-message" class="mt-1 text-sm text-red-700"></p>
                        <div class="mt-4">
                            <button id="retry-btn" class="bg-red-100 px-3 py-2 rounded-md text-sm font-medium text-red-800 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Try Again
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success State -->
            <div id="success-state" class="bg-green-50 border border-green-200 rounded-md p-4 hidden">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Flashcards Generated Successfully!</h3>
                        <p id="success-message" class="mt-1 text-sm text-green-700"></p>
                        <div class="mt-4">
                            <a id="view-flashcards-btn" href="{{ route('flashcards.index') }}" class="bg-green-100 px-3 py-2 rounded-md text-sm font-medium text-green-800 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                View My Flashcards
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Premium Features Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mt-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Premium AI Flashcard Generation</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Generate unlimited flashcards from any PDF or DOCX file</li>
                                <li>Choose between 10, 20, or 30 flashcards per generation</li>
                                <li>AI automatically creates questions and answers from your content</li>
                                <li>Organize flashcards by category and tags</li>
                                <li>Interactive study mode with flip animations</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('generation-form');
            const generateBtn = document.getElementById('generate-btn');
            const loadingState = document.getElementById('loading-state');
            const errorState = document.getElementById('error-state');
            const successState = document.getElementById('success-state');
            const errorMessage = document.getElementById('error-message');
            const successMessage = document.getElementById('success-message');
            const retryBtn = document.getElementById('retry-btn');
            const progressBar = document.getElementById('progress-bar');

            function showLoading() {
                form.parentElement.classList.add('hidden');
                loadingState.classList.remove('hidden');
                errorState.classList.add('hidden');
                successState.classList.add('hidden');
                
                // Simulate progress
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';
                }, 500);
                
                setTimeout(() => {
                    clearInterval(interval);
                    progressBar.style.width = '100%';
                }, 5000);
            }

            function showError(message) {
                loadingState.classList.add('hidden');
                errorState.classList.remove('hidden');
                successState.classList.add('hidden');
                errorMessage.textContent = message;
            }

            function showSuccess(message) {
                loadingState.classList.add('hidden');
                errorState.classList.add('hidden');
                successState.classList.remove('hidden');
                successMessage.textContent = message;
            }

            function resetForm() {
                form.parentElement.classList.remove('hidden');
                loadingState.classList.add('hidden');
                errorState.classList.add('hidden');
                successState.classList.add('hidden');
                progressBar.style.width = '0%';
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                showLoading();
                
                const formData = new FormData(form);
                
                fetch('{{ route("flashcards.ai.generate") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess(data.message + ` Created ${data.flashcards_count} flashcards.`);
                    } else {
                        showError(data.message || 'An error occurred while generating flashcards.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showError('Network error occurred. Please check your connection and try again.');
                });
            });

            retryBtn.addEventListener('click', resetForm);
        });
    </script>
</x-app-layout>