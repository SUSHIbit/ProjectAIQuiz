<!-- resources/views/quiz/generator.blade.php -->
<x-app-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           {{ __('Quiz Generator') }}
       </h2>
   </x-slot>

   <div class="py-12">
       <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               <div class="p-6 text-gray-900">
                   <div class="text-center">
                       <div class="mb-6">
                           <svg class="mx-auto h-16 w-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                           </svg>
                       </div>
                       
                       <h3 class="text-2xl font-bold text-gray-900 mb-4">Quiz Generator</h3>
                       
                       @if(session('uploaded_file'))
                           <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                               <div class="flex items-center">
                                   <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                   </svg>
                                   <span class="text-green-800 font-medium">File Ready:</span>
                                   <span class="text-green-700 ml-1">{{ session('uploaded_file.original_name') }}</span>
                               </div>
                           </div>
                       @endif

                       <p class="text-gray-600 mb-8">
                           This feature will be implemented in Phase 4. 
                           You'll be able to generate AI-powered quizzes from your uploaded documents here.
                       </p>

                       <div class="space-y-4">
                           <a href="{{ route('welcome') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Upload Another File
                           </a>
                           
                           <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-4">
                               Back to Dashboard
                           </a>
                       </div>

                       @if(session('uploaded_file'))
                           <div class="mt-8 bg-gray-50 rounded-lg p-6">
                               <h4 class="text-lg font-medium text-gray-900 mb-4">File Information</h4>
                               <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                   <div class="flex justify-between">
                                       <span class="text-gray-500">Original Name:</span>
                                       <span class="text-gray-900">{{ session('uploaded_file.original_name') }}</span>
                                   </div>
                                   <div class="flex justify-between">
                                       <span class="text-gray-500">File Size:</span>
                                       <span class="text-gray-900">{{ number_format(session('uploaded_file.size') / 1024, 2) }} KB</span>
                                   </div>
                                   <div class="flex justify-between">
                                       <span class="text-gray-500">File Type:</span>
                                       <span class="text-gray-900 uppercase">{{ session('uploaded_file.type') }}</span>
                                   </div>
                                   <div class="flex justify-between">
                                       <span class="text-gray-500">Uploaded At:</span>
                                       <span class="text-gray-900">{{ \Carbon\Carbon::parse(session('uploaded_file.uploaded_at'))->format('M d, Y H:i') }}</span>
                                   </div>
                               </div>
                           </div>
                       @endif
                   </div>
               </div>
           </div>
       </div>
   </div>
</x-app-layout>