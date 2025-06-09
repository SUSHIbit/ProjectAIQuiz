<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Flashcards') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <p class="text-sm font-medium text-gray-500">Total Flashcards</p>
                               <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_flashcards'] }}</p>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                   <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <p class="text-sm font-medium text-gray-500">Studied</p>
                               <p class="text-2xl font-semibold text-gray-900">{{ $stats['studied_flashcards'] }}</p>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                   <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <p class="text-sm font-medium text-gray-500">Study Sessions</p>
                               <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_study_sessions'] }}</p>
                           </div>
                       </div>
                   </div>
               </div>

               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                   <div class="p-6">
                       <div class="flex items-center">
                           <div class="flex-shrink-0">
                               <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                   <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                   </svg>
                               </div>
                           </div>
                           <div class="ml-4">
                               <p class="text-sm font-medium text-gray-500">Categories</p>
                               <p class="text-2xl font-semibold text-gray-900">{{ $stats['categories'] }}</p>
                           </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Action Buttons -->
           <div class="flex flex-col sm:flex-row gap-4 mb-8">
               <a href="{{ route('flashcards.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                   </svg>
                   Create Manual Flashcards
               </a>
               
               <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                   </svg>
                   Generate AI Flashcards
               </a>

               @if($flashcards->count() > 0)
               <a href="{{ route('flashcards.study') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150">
                   <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                   </svg>
                   Study Mode
               </a>
               @endif
           </div>

           <!-- Flashcards Grid -->
           @if($flashcards->count() > 0)
           <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
               @foreach($flashcards as $flashcard)
               <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                   <div class="p-6">
                       <div class="flex items-start justify-between mb-4">
                           <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $flashcard->title }}</h3>
                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $flashcard->source_badge_color }}">
                               {{ $flashcard->isAiGenerated() ? 'AI' : 'Manual' }}
                           </span>
                       </div>
                       
                       <div class="space-y-3">
                           <div>
                               <p class="text-sm font-medium text-gray-500 mb-1">Front:</p>
                               <p class="text-sm text-gray-700">{{ $flashcard->short_front_text }}</p>
                           </div>
                           
                           <div>
                               <p class="text-sm font-medium text-gray-500 mb-1">Back:</p>
                               <p class="text-sm text-gray-700">{{ $flashcard->short_back_text }}</p>
                           </div>
                       </div>

                       <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                           <div class="flex items-center space-x-4">
                               @if($flashcard->category)
                               <span class="inline-flex items-center">
                                   <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                   </svg>
                                   {{ $flashcard->category }}
                               </span>
                               @endif
                               
                               <span class="inline-flex items-center">
                                   <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                   </svg>
                                   {{ $flashcard->study_count }} studies
                               </span>
                           </div>
                           
                           <span>{{ $flashcard->formatted_created_at }}</span>
                       </div>

                       <div class="mt-4 flex space-x-2">
                           <a href="{{ route('flashcards.show', $flashcard) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                               View
                           </a>
                           <a href="{{ route('flashcards.edit', $flashcard) }}" class="flex-1 inline-flex justify-center items-center px-3 py-2 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                               Edit
                           </a>
                       </div>
                   </div>
               </div>
               @endforeach
           </div>

           <!-- Pagination -->
           <div class="mt-8">
               {{ $flashcards->links() }}
           </div>
           @else
           <!-- Empty State -->
           <div class="text-center py-12">
               <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
               </svg>
               <h3 class="mt-4 text-lg font-medium text-gray-900">No flashcards yet</h3>
               <p class="mt-2 text-gray-500">Get started by creating your first flashcard set.</p>
               <div class="mt-6 flex justify-center space-x-4">
                   <a href="{{ route('flashcards.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                       Create Manual Flashcards
                   </a>
                   <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                       Generate AI Flashcards
                   </a>
               </div>
           </div>
           @endif
       </div>
   </div>
</x-app-layout>