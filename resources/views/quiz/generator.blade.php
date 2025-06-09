<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('AI Quiz Generator') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- File Info Card -->
            @if($uploadedFile)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-10 w-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900">File Ready for Processing</h3>
                            <p class="text-sm text-gray-600">{{ $uploadedFile['original_name'] }} ({{ number_format($uploadedFile['size'] / 1024, 2) }} KB)</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Generation Settings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Generate Quiz Questions</h3>
                    
                    <form id="generate-form" class="space-y-6">
                        @csrf
                        
                        <!-- Question Count Selection -->
                        <div>
                            <label for="question_count" class="block text-sm font-medium text-gray-700 mb-2">
                                Number of Questions
                            </label>
                            @if($user->isPremium())
                                <select id="question_count" name="question_count" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="10">10 Questions</option>
                                    <option value="20">20 Questions</option>
                                    <option value="30">30 Questions</option>
                                </select>
                                <p class="mt-1 text-sm text-green-600">✨ Premium: Choose your preferred question count</p>
                            @else
                                <input type="hidden" name="question_count" value="10">
                                <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-200 rounded-md">
                                    <span class="text-gray-900 font-medium">10 Questions</span>
                                    <span class="text-sm text-gray-500">(Free tier limit)</span>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Free users get 10 questions. 
                                    <a href="{{ route('tier.upgrade') }}" class="text-blue-600 hover:text-blue-500">Upgrade to Premium</a> 
                                    for up to 30 questions.
                                </p>
                            @endif
                        </div>

                        <!-- Attempts Info -->
                        @if($user->isFree())
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-800">
                                        <span class="font-medium">{{ $user->question_attempts }} AI generation{{ $user->question_attempts !== 1 ? 's' : '' }} remaining</span>
                                    </p>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        This will use 1 of your remaining attempts.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Generate Button -->
                        <div class="flex justify-end space-x-4">
                        <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Upload Different File
                           </a>
                           
                           <button type="submit" id="generate-btn" class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                               </svg>
                               Generate Questions
                           </button>
                       </div>
                   </form>

                   <!-- Loading State -->
                   <div id="loading-state" class="hidden mt-6 text-center">
                       <div class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-blue-500 bg-blue-100">
                           <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                               <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                               <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                           </svg>
                           Generating questions with AI...
                       </div>
                       <p class="text-sm text-gray-600 mt-2">This may take 30-60 seconds</p>
                   </div>

                   <!-- Error Display -->
                   <div id="error-display" class="hidden mt-6 p-4 bg-red-50 border border-red-200 rounded-md">
                       <div class="flex">
                           <svg class="h-5 w-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                               <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                           </svg>
                           <div>
                               <h3 class="text-sm font-medium text-red-800">Generation Failed</h3>
                               <p id="error-message" class="text-sm text-red-700 mt-1"></p>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const form = document.getElementById('generate-form');
           const generateBtn = document.getElementById('generate-btn');
           const loadingState = document.getElementById('loading-state');
           const errorDisplay = document.getElementById('error-display');
           const errorMessage = document.getElementById('error-message');

           form.addEventListener('submit', async function(e) {
               e.preventDefault();

               // Hide previous errors
               errorDisplay.classList.add('hidden');
               
               // Show loading state
               generateBtn.classList.add('hidden');
               loadingState.classList.remove('hidden');

               try {
                   const formData = new FormData(form);
                   
                   const response = await fetch('{{ route("quiz.generate") }}', {
                       method: 'POST',
                       body: formData,
                       headers: {
                           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                       }
                   });

                   const data = await response.json();

                   if (data.success) {
                       window.location.href = data.redirect;
                   } else {
                       throw new Error(data.message || 'Failed to generate questions');
                   }
               } catch (error) {
                   // Hide loading state
                   loadingState.classList.add('hidden');
                   generateBtn.classList.remove('hidden');
                   
                   // Show error
                   errorMessage.textContent = error.message;
                   errorDisplay.classList.remove('hidden');
               }
           });
       });
   </script>
</x-app-layout>