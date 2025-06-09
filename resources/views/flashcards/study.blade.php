<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Study Mode') }}
            @if($category)
            <span class="text-sm text-gray-600">- {{ $category }}</span>
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Study Controls -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600">
                                <span id="current-card">1</span> of <span id="total-cards">{{ $flashcards->count() }}</span>
                            </span>
                            <div class="w-64 bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $flashcards->count() > 0 ? (1 / $flashcards->count()) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button id="shuffle-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Shuffle
                            </button>
                            <a href="{{ route('flashcards.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Exit Study
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flashcard Display -->
            <div class="perspective-1000">
                <div id="flashcard-container" class="flashcard-flip bg-white shadow-lg rounded-xl cursor-pointer transform-style-preserve-3d transition-transform duration-700" style="height: 400px;">
                    <!-- Front Side -->
                    <div class="flashcard-front absolute inset-0 backface-hidden flex flex-col justify-center items-center p-8 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-100 border-2 border-blue-200">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-4">Front</div>
                            <div id="front-content" class="text-xl text-gray-800 leading-relaxed"></div>
                        </div>
                        <div class="absolute bottom-4 right-4 text-xs text-gray-400">
                            Click to flip
                        </div>
                    </div>

                    <!-- Back Side -->
                    <div class="flashcard-back absolute inset-0 backface-hidden flex flex-col justify-center items-center p-8 rounded-xl bg-gradient-to-br from-green-50 to-emerald-100 border-2 border-green-200 rotate-y-180">
                        <div class="text-center">
                            <div class="text-sm text-gray-500 mb-4">Back</div>
                            <div id="back-content" class="text-xl text-gray-800 leading-relaxed"></div>
                        </div>
                        <div class="absolute bottom-4 right-4 text-xs text-gray-400">
                            Click to flip
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <div class="mt-8 flex justify-between items-center">
                <button id="prev-btn" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                   </svg>
                   Previous
               </button>

               <div class="flex space-x-4">
                   <button id="mark-difficult" class="inline-flex items-center px-3 py-2 bg-red-100 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                       </svg>
                       Mark Difficult
                   </button>
                   
                   <button id="mark-easy" class="inline-flex items-center px-3 py-2 bg-green-100 border border-green-300 rounded-md text-sm font-medium text-green-700 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                       <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                       </svg>
                       Mark Easy
                   </button>
               </div>

               <button id="next-btn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   Next
                   <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                   </svg>
               </button>
           </div>

           <!-- Study Completion Modal -->
           <div id="completion-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
               <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                   <div class="mt-3 text-center">
                       <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                           <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                           </svg>
                       </div>
                       <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Study Session Complete!</h3>
                       <div class="mt-2 px-7 py-3">
                           <p class="text-sm text-gray-500">
                               Great job! You've completed studying <span id="studied-count"></span> flashcards.
                           </p>
                       </div>
                       <div class="items-center px-4 py-3">
                           <button id="restart-study" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300">
                               Study Again
                           </button>
                           <a href="{{ route('flashcards.index') }}" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                               Back to List
                           </a>
                       </div>
                   </div>
               </div>
           </div>
       </div>
   </div>

   <style>
       .perspective-1000 {
           perspective: 1000px;
       }
       
       .transform-style-preserve-3d {
           transform-style: preserve-3d;
       }
       
       .backface-hidden {
           backface-visibility: hidden;
       }
       
       .rotate-y-180 {
           transform: rotateY(180deg);
       }
       
       .flashcard-flip.flipped {
           transform: rotateY(180deg);
       }
       
       .flashcard-front, .flashcard-back {
           transition: all 0.3s ease;
       }
   </style>

   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const flashcards = @json($flashcards->values());
           let currentIndex = 0;
           let isFlipped = false;
           
           const flashcardContainer = document.getElementById('flashcard-container');
           const frontContent = document.getElementById('front-content');
           const backContent = document.getElementById('back-content');
           const currentCardSpan = document.getElementById('current-card');
           const totalCardsSpan = document.getElementById('total-cards');
           const progressBar = document.getElementById('progress-bar');
           const prevBtn = document.getElementById('prev-btn');
           const nextBtn = document.getElementById('next-btn');
           const shuffleBtn = document.getElementById('shuffle-btn');
           const markDifficultBtn = document.getElementById('mark-difficult');
           const markEasyBtn = document.getElementById('mark-easy');
           const completionModal = document.getElementById('completion-modal');
           const restartStudyBtn = document.getElementById('restart-study');
           const studiedCountSpan = document.getElementById('studied-count');

           function loadFlashcard(index) {
               if (index < 0 || index >= flashcards.length) return;
               
               const flashcard = flashcards[index];
               frontContent.textContent = flashcard.front_text;
               backContent.textContent = flashcard.back_text;
               
               currentCardSpan.textContent = index + 1;
               totalCardsSpan.textContent = flashcards.length;
               
               // Update progress bar
               const progress = ((index + 1) / flashcards.length) * 100;
               progressBar.style.width = progress + '%';
               
               // Reset flip state
               isFlipped = false;
               flashcardContainer.classList.remove('flipped');
               
               // Update navigation buttons
               prevBtn.disabled = index === 0;
               nextBtn.disabled = index === flashcards.length - 1;
               
               if (prevBtn.disabled) {
                   prevBtn.classList.add('opacity-50', 'cursor-not-allowed');
               } else {
                   prevBtn.classList.remove('opacity-50', 'cursor-not-allowed');
               }
               
               if (nextBtn.disabled) {
                   nextBtn.textContent = 'Finish';
               } else {
                   nextBtn.innerHTML = 'Next <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>';
               }
           }

           function flipCard() {
               isFlipped = !isFlipped;
               flashcardContainer.classList.toggle('flipped');
           }

           function markAsStudied(flashcardId) {
               fetch(`/flashcards/${flashcardId}/mark-studied`, {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                   }
               }).catch(error => {
                   console.error('Error marking flashcard as studied:', error);
               });
           }

           function shuffleArray(array) {
               for (let i = array.length - 1; i > 0; i--) {
                   const j = Math.floor(Math.random() * (i + 1));
                   [array[i], array[j]] = [array[j], array[i]];
               }
               return array;
           }

           function showCompletionModal() {
               studiedCountSpan.textContent = flashcards.length;
               completionModal.classList.remove('hidden');
           }

           // Event listeners
           flashcardContainer.addEventListener('click', flipCard);

           prevBtn.addEventListener('click', function() {
               if (currentIndex > 0) {
                   currentIndex--;
                   loadFlashcard(currentIndex);
               }
           });

           nextBtn.addEventListener('click', function() {
               // Mark current flashcard as studied
               markAsStudied(flashcards[currentIndex].id);
               
               if (currentIndex < flashcards.length - 1) {
                   currentIndex++;
                   loadFlashcard(currentIndex);
               } else {
                   // Study session complete
                   showCompletionModal();
               }
           });

           shuffleBtn.addEventListener('click', function() {
               shuffleArray(flashcards);
               currentIndex = 0;
               loadFlashcard(currentIndex);
           });

           markDifficultBtn.addEventListener('click', function() {
               // Could implement difficulty tracking in future
               this.classList.add('bg-red-200');
               setTimeout(() => {
                   this.classList.remove('bg-red-200');
               }, 500);
           });

           markEasyBtn.addEventListener('click', function() {
               // Could implement difficulty tracking in future
               this.classList.add('bg-green-200');
               setTimeout(() => {
                   this.classList.remove('bg-green-200');
               }, 500);
           });

           restartStudyBtn.addEventListener('click', function() {
               currentIndex = 0;
               loadFlashcard(currentIndex);
               completionModal.classList.add('hidden');
           });

           // Close modal when clicking outside
           completionModal.addEventListener('click', function(e) {
               if (e.target === this) {
                   this.classList.add('hidden');
               }
           });

           // Keyboard navigation
           document.addEventListener('keydown', function(e) {
               if (completionModal.classList.contains('hidden')) {
                   switch(e.key) {
                       case 'ArrowLeft':
                           e.preventDefault();
                           if (currentIndex > 0) {
                               currentIndex--;
                               loadFlashcard(currentIndex);
                           }
                           break;
                       case 'ArrowRight':
                           e.preventDefault();
                           if (currentIndex < flashcards.length - 1) {
                               markAsStudied(flashcards[currentIndex].id);
                               currentIndex++;
                               loadFlashcard(currentIndex);
                           } else {
                               showCompletionModal();
                           }
                           break;
                       case ' ':
                           e.preventDefault();
                           flipCard();
                           break;
                   }
               }
           });

           // Initialize
           if (flashcards.length > 0) {
               loadFlashcard(0);
           }
       });
   </script>
</x-app-layout>