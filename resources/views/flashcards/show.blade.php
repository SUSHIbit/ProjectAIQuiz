<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Flashcard Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Flashcard Display -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $flashcard->title }}</h1>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $flashcard->source_badge_color }}">
                                    {{ $flashcard->isAiGenerated() ? 'AI Generated' : 'Manual' }}
                                </span>
                                @if($flashcard->category)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $flashcard->category }}
                                </span>
                                @endif
                                @if($flashcard->tags)
                                <span class="text-xs text-gray-500">
                                    Tags: {{ $flashcard->tags }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex space-x-2">
                            <a href="{{ route('flashcards.edit', $flashcard) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            
                            <form action="{{ route('flashcards.destroy', $flashcard) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this flashcard?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Interactive Flashcard -->
                    <div class="perspective-1000 mb-6">
                        <div id="flashcard-container" class="flashcard-flip bg-white shadow-lg rounded-xl cursor-pointer transform-style-preserve-3d transition-transform duration-700" style="height: 300px;">
                            <!-- Front Side -->
                            <div class="flashcard-front absolute inset-0 backface-hidden flex flex-col justify-center items-center p-8 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-100 border-2 border-blue-200">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 mb-4">Front</div>
                                    <div class="text-xl text-gray-800 leading-relaxed">{{ $flashcard->front_text }}</div>
                                </div>
                                <div class="absolute bottom-4 right-4 text-xs text-gray-400">
                                    Click to flip
                                </div>
                            </div>

                            <!-- Back Side -->
                            <div class="flashcard-back absolute inset-0 backface-hidden flex flex-col justify-center items-center p-8 rounded-xl bg-gradient-to-br from-green-50 to-emerald-100 border-2 border-green-200 rotate-y-180">
                                <div class="text-center">
                                    <div class="text-sm text-gray-500 mb-4">Back</div>
                                    <div class="text-xl text-gray-800 leading-relaxed">{{ $flashcard->back_text }}</div>
                                </div>
                                <div class="absolute bottom-4 right-4 text-xs text-gray-400">
                                    Click to flip
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Study Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-blue-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Times Studied</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $flashcard->study_count }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Last Studied</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $flashcard->last_studied_display }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 text-purple-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Created</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $flashcard->formatted_created_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4 mb-6">
                <button id="mark-studied-btn" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Mark as Studied
                </button>
                
                <a href="{{ route('flashcards.study') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Start Study Session
                </a>
                
                <a href="{{ route('flashcards.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                    </svg>
                    Back to Flashcards
                </a>
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
            const flashcardContainer = document.getElementById('flashcard-container');
            const markStudiedBtn = document.getElementById('mark-studied-btn');
            let isFlipped = false;

            function flipCard() {
                isFlipped = !isFlipped;
                flashcardContainer.classList.toggle('flipped');
            }

            function markAsStudied() {
                fetch(`{{ route('flashcards.mark-studied', $flashcard) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button text temporarily
                        const originalText = markStudiedBtn.innerHTML;
                        markStudiedBtn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Studied!';
                        markStudiedBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                        markStudiedBtn.classList.add('bg-green-400');
                        
                        setTimeout(() => {
                            markStudiedBtn.innerHTML = originalText;
                            markStudiedBtn.classList.remove('bg-green-400');
                            markStudiedBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                        }, 2000);
                        
                        // Could update study count display here
                        console.log('Study count:', data.study_count);
                    }
                })
                .catch(error => {
                    console.error('Error marking flashcard as studied:', error);
                });
            }

            // Event listeners
            flashcardContainer.addEventListener('click', flipCard);
            markStudiedBtn.addEventListener('click', markAsStudied);

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                switch(e.key) {
                    case ' ':
                        e.preventDefault();
                        flipCard();
                        break;
                    case 'Enter':
                        e.preventDefault();
                        markAsStudied();
                        break;
                }
            });
        });
    </script>
</x-app-layout>