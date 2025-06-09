<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Subject Analytics: ') }} {{ $subject }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('analytics.dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Analytics Dashboard
                </a>
            </div>

            <!-- Subject Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">{{ $subject }} Performance Overview</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-3xl font-bold text-blue-600">{{ $subjectStats['total_attempts'] }}</p>
                            <p class="text-sm text-gray-600">Total Attempts</p>
                        </div>
                        
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-3xl font-bold text-green-600">{{ $subjectStats['average_score'] }}%</p>
                            <p class="text-sm text-gray-600">Average Score</p>
                        </div>
                        
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <p class="text-3xl font-bold text-yellow-600">{{ $subjectStats['best_score'] }}%</p>
                            <p class="text-sm text-gray-600">Best Score</p>
                        </div>
                        
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <p class="text-3xl font-bold text-purple-600">{{ number_format($subjectStats['consistency_score'], 1) }}</p>
                            <p class="text-sm text-gray-600">Consistency Score</p>
                        </div>
                    </div>

                    <!-- Improvement Trend -->
                    <div class="mt-6 p-4 rounded-lg {{ $subjectStats['improvement_trend'] === 'improving' ? 'bg-green-50 border border-green-200' : ($subjectStats['improvement_trend'] === 'declining' ? 'bg-red-50 border border-red-200' : 'bg-gray-50 border border-gray-200') }}">
                        <div class="flex items-center">
                            @if($subjectStats['improvement_trend'] === 'improving')
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                <span class="text-green-800 font-medium">Improving Trend</span>
                            @elseif($subjectStats['improvement_trend'] === 'declining')
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                <span class="text-red-800 font-medium">Declining Trend</span>
                            @else
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                                <span class="text-gray-800 font-medium">Stable Performance</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topic Breakdown -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Topic Performance Breakdown</h3>
                    
                    @if($topicBreakdown->count() > 0)
                        <div class="space-y-4">
                            @foreach($topicBreakdown as $topic)
                            <div class="border rounded-lg p-4 hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $topic['topic'] }}</h4>
                                        <div class="mt-2 flex items-center space-x-6 text-sm text-gray-600">
                                            <span>{{ $topic['attempts'] }} attempts</span>
                                            <span>Avg: {{ $topic['average_score'] }}%</span>
                                            <span>Best: {{ $topic['best_score'] }}%</span>
                                            <span>Latest: {{ $topic['latest_score'] }}%</span>
                                        </div>
                                    </div>
                                    <div class="ml-4 text-right">
                                        @if($topic['improvement'] > 5)
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                                </svg>
                                                +{{ $topic['improvement'] }}%
                                            </div>
                                        @elseif($topic['improvement'] < -5)
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                                </svg>
                                                {{ $topic['improvement'] }}%
                                            </div>
                                        @else
                                            <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $topic['improvement'] }}%
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Progress Bar -->
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>{{ $topic['average_score'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $topic['average_score'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No topic data available for {{ $subject }}.</p>
                    @endif
                </div>
            </div>

            <!-- Difficulty Analysis -->
            @if($difficultyAnalysis->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Difficulty Analysis</h3>
                    <p class="text-sm text-gray-600 mb-4">Based on community performance data</p>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Community Avg</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($difficultyAnalysis as $analysis)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $analysis['topic'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $analysis['difficulty'] === 'Hard' ? 'bg-red-100 text-red-800' : 
                                               ($analysis['difficulty'] === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                            {{ $analysis['difficulty'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $analysis['average_score'] }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $analysis['attempt_count'] }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Attempts -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent {{ $subject }} Attempts</h3>
                    
                    @if($attempts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quiz</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attempts->take(10) as $attempt)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ Str::limit($attempt->quiz->title, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attempt->quiz->topic }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div class="flex items-center">
                                                <span class="font-medium">{{ $attempt->score }}%</span>
                                                <span class="ml-2 text-xs text-gray-500">({{ $attempt->correct_answers }}/{{ $attempt->total_questions }})</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                {{ $attempt->grade === 'A' ? 'bg-green-100 text-green-800' : 
                                                   ($attempt->grade === 'B' ? 'bg-blue-100 text-blue-800' : 
                                                   ($attempt->grade === 'C' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($attempt->grade === 'D' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800'))) }}">
                                                {{ $attempt->grade }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attempt->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $attempt->time_taken ? gmdate('i:s', $attempt->time_taken) : 'N/A' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No attempts found for {{ $subject }}.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>