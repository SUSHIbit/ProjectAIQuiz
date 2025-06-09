<x-app-layout>
   <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 leading-tight">
           Attempt History: {{ $quiz->title }}
       </h2>
   </x-slot>

   <div class="py-12">
       <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
           <!-- Quiz Info -->
           <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
               <div class="p-6">
                   <div class="flex justify-between items-start">
                       <div>
                           <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $quiz->title }}</h1>
                           <div class="flex items-center space-x-4 text-sm text-gray-600">
                               <span>{{ $quiz->subject }} - {{ $quiz->topic }}</span>
                               <span>{{ $quiz->total_questions }} Questions</span>
                           </div>
                       </div>
                       <div class="flex space-x-2">
                           <a href="{{ route('quiz.attempt.start', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-3-4V5a1 1 0 011-1h1a1 1 0 011 1v2M7 7V4a1 1 0 011-1h8a1 1 0 011 1v3"></path>
                               </svg>
                               Take Quiz Again
                           </a>
                           <a href="{{ route('quiz.show', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               Back to Quiz
                           </a>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Statistics Summary -->
           @if($attempts->count() > 0)
           @php
               $completedAttempts = $attempts->where('status', 'completed');
               $bestScore = $completedAttempts->max('score_percentage') ?? 0;
               $averageScore = $completedAttempts->avg('score_percentage') ?? 0;
               $totalTime = $completedAttempts->sum('time_taken');
               $averageTime = $completedAttempts->count() > 0 ? $totalTime / $completedAttempts->count() : 0;
           @endphp
           <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
               <div class="p-6">
                   <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Statistics</h3>
                   <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                       <div class="text-center p-4 bg-blue-50 rounded-lg">
                           <div class="text-2xl font-bold text-blue-600">{{ $attempts->count() }}</div>
                           <div class="text-sm text-gray-600">Total Attempts</div>
                       </div>
                       <div class="text-center p-4 bg-green-50 rounded-lg">
                           <div class="text-2xl font-bold text-green-600">{{ number_format($bestScore, 1) }}%</div>
                           <div class="text-sm text-gray-600">Best Score</div>
                       </div>
                       <div class="text-center p-4 bg-yellow-50 rounded-lg">
                           <div class="text-2xl font-bold text-yellow-600">{{ number_format($averageScore, 1) }}%</div>
                           <div class="text-sm text-gray-600">Average Score</div>
                       </div>
                       <div class="text-center p-4 bg-purple-50 rounded-lg">
                           <div class="text-2xl font-bold text-purple-600">
                               @if($averageTime > 0)
                                   {{ sprintf('%02d:%02d', floor($averageTime / 60), $averageTime % 60) }}
                               @else
                                   N/A
                               @endif
                           </div>
                           <div class="text-sm text-gray-600">Avg. Time</div>
                       </div>
                   </div>
               </div>
           </div>
           @endif

           <!-- Attempts List -->
           <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
               <div class="p-6">
                   <h3 class="text-lg font-semibold text-gray-900 mb-6">All Attempts</h3>
                   
                   @if($attempts->count() > 0)
                       <div class="overflow-x-auto">
                           <table class="min-w-full divide-y divide-gray-200">
                               <thead class="bg-gray-50">
                                   <tr>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempt</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Correct</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                       <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                   </tr>
                               </thead>
                               <tbody class="bg-white divide-y divide-gray-200">
                                   @foreach($attempts as $index => $attempt)
                                   <tr class="hover:bg-gray-50">
                                       <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                           #{{ $attempts->total() - (($attempts->currentPage() - 1) * $attempts->perPage()) - $index }}
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                           <div class="flex items-center">
                                               <span class="text-lg font-bold
                                                   @if($attempt->score_percentage >= 80) text-green-600
                                                   @elseif($attempt->score_percentage >= 60) text-yellow-600
                                                   @else text-red-600 @endif">
                                                   {{ $attempt->score_percentage }}%
                                               </span>
                                           </div>
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap">
                                           <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                               @if($attempt->grade === 'A') bg-green-100 text-green-800
                                               @elseif($attempt->grade === 'B') bg-blue-100 text-blue-800
                                               @elseif($attempt->grade === 'C') bg-yellow-100 text-yellow-800
                                               @elseif($attempt->grade === 'D') bg-orange-100 text-orange-800
                                               @else bg-red-100 text-red-800 @endif">
                                               {{ $attempt->grade }}
                                           </span>
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                           {{ $attempt->correct_answers }}/{{ $attempt->total_questions }}
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                           {{ $attempt->formatted_time }}
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap">
                                           @if($attempt->isCompleted())
                                               <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                   <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                   </svg>
                                                   Completed
                                               </span>
                                           @elseif($attempt->isAbandoned())
                                               <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                   <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                   </svg>
                                                   Abandoned
                                               </span>
                                           @else
                                               <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                   <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                       <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                   </svg>
                                                   In Progress
                                               </span>
                                           @endif
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                           {{ $attempt->created_at->format('M d, Y') }}
                                           <br>
                                           <span class="text-xs">{{ $attempt->created_at->format('H:i') }}</span>
                                       </td>
                                       <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                           @if($attempt->isInProgress())
                                               <a href="{{ route('quiz.attempt.take', $attempt->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Resume</a>
                                           @else
                                               <a href="{{ route('quiz.attempt.result', $attempt->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">View Results</a>
                                           @endif
                                       </td>
                                   </tr>
                                   @endforeach
                               </tbody>
                           </table>
                       </div>

                       <!-- Pagination -->
                       <div class="mt-6">
                           {{ $attempts->links() }}
                       </div>
                   @else
                       <div class="text-center py-8">
                           <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                           </svg>
                           <h3 class="text-lg font-medium text-gray-900 mb-2">No attempts yet</h3>
                           <p class="text-gray-600 mb-4">You haven't taken this quiz yet. Start your first attempt!</p>
                           <a href="{{ route('quiz.attempt.start', $quiz->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                               <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h8m-3-4V5a1 1 0 011-1h1a1 1 0 011 1v2M7 7V4a1 1 0 011-1h8a1 1 0 011 1v3"></path>
                               </svg>
                               Take Quiz
                           </a>
                       </div>
                   @endif
               </div>
           </div>
       </div>
   </div>
</x-app-layout>