<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Manual Flashcards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form id="flashcards-form" action="{{ route('flashcards.store') }}" method="POST">
                        @csrf
                        
                        <div id="flashcards-container">
                            <!-- Initial flashcard template will be loaded here -->
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <button type="button" id="add-flashcard" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Another Flashcard
                            </button>
                            
                            <div class="flex space-x-4">
                                <a href="{{ route('flashcards.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Create Flashcards
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Flashcard Template -->
    <template id="flashcard-template">
        <div class="flashcard-item bg-gray-50 rounded-lg p-6 mb-6 border-2 border-dashed border-gray-300">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Flashcard <span class="flashcard-number"></span></h3>
                <button type="button" class="remove-flashcard text-red-600 hover:text-red-800 p-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Front Side -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Front (Question/Term) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="flashcards[INDEX][front_text]" rows="4" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter the question, term, or concept..."></textarea>
                </div>

                <!-- Back Side -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Back (Answer/Definition) <span class="text-red-500">*</span>
                    </label>
                    <textarea name="flashcards[INDEX][back_text]" rows="4" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Enter the answer, definition, or explanation..."></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="flashcards[INDEX][title]" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Brief title...">
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <input type="text" name="flashcards[INDEX][category]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="e.g., Math, Science..." list="categories">
                    <datalist id="categories">
                        @foreach($categories as $category)
                        <option value="{{ $category }}">
                        @endforeach
                    </datalist>
                </div>

                <!-- Tags -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                    <input type="text" name="flashcards[INDEX][tags]" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="tag1, tag2, tag3...">
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('flashcards-container');
            const addButton = document.getElementById('add-flashcard');
            const template = document.getElementById('flashcard-template');
            let flashcardIndex = 0;

            function addFlashcard() {
                const templateContent = template.content.cloneNode(true);
                
                // Update all name attributes with current index
                templateContent.querySelectorAll('[name*="INDEX"]').forEach(input => {
                    input.name = input.name.replace('INDEX', flashcardIndex);
                });
                
                // Update flashcard number
                templateContent.querySelector('.flashcard-number').textContent = flashcardIndex + 1;
                
                // Add remove functionality
                const removeButton = templateContent.querySelector('.remove-flashcard');
                removeButton.addEventListener('click', function() {
                    this.closest('.flashcard-item').remove();
                    updateFlashcardNumbers();
                });
                
                container.appendChild(templateContent);
                flashcardIndex++;
                
                // Focus on the first input of the new flashcard
                const newFlashcard = container.lastElementChild;
                newFlashcard.querySelector('input[name*="[title]"]').focus();
            }

            function updateFlashcardNumbers() {
                const flashcards = container.querySelectorAll('.flashcard-item');
                flashcards.forEach((flashcard, index) => {
                    flashcard.querySelector('.flashcard-number').textContent = index + 1;
                    
                    // Update all name attributes
                    flashcard.querySelectorAll('[name*="flashcards["]').forEach(input => {
                        const nameAttr = input.name;
                        const newName = nameAttr.replace(/flashcards\[\d+\]/, `flashcards[${index}]`);
                        input.name = newName;
                    });
                });
            }

            // Add initial flashcard
            addFlashcard();

            // Add button click handler
            addButton.addEventListener('click', addFlashcard);

            // Form validation
            document.getElementById('flashcards-form').addEventListener('submit', function(e) {
                const flashcards = container.querySelectorAll('.flashcard-item');
                if (flashcards.length === 0) {
                    e.preventDefault();
                    alert('Please add at least one flashcard.');
                    return false;
                }
            });
        });
    </script>
</x-app-layout>