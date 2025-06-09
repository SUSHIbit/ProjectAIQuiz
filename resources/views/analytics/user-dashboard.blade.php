<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Total Attempts</h3>
                                <p class="text-3xl font-bold text-blue-600">{{ $totalAttempts }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Average Score</h3>
                                <p class="text-3xl font-bold text-green-600">{{ number_format($averageScore, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-yellow-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Best Score</h3>
                                <p class="text-3xl font-bold text-yellow-600">{{ number_format($bestScore, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-purple-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Time Spent</h3>
                                <p class="text-3xl font-bold text-purple-600">{{ number_format($totalTimeSpent / 3600, 1) }}h</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Score Progression Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Score Progression</h3>
                    <div class="h-64">
                        <canvas id="scoreProgressionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Weekly Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Performance (Last 12 Weeks)</h3>
                    <div class="h-64">
                        <canvas id="weeklyPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Subject Performance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Subject Performance</h3>
                        @if($subjectPerformance->count() > 0)
                            <div class="space-y-4">
                                @foreach($subjectPerformance as $subject)
                                <div class="border-l-4 border-blue-500 pl-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $subject['subject'] }}</h4>
                                            <p class="text-sm text-gray-600">{{ $subject['total_attempts'] }} attempts</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-blue-600">{{ $subject['average_score'] }}%</p>
                                            <p class="text-xs text-gray-500">Best: {{ $subject['best_score'] }}%</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('analytics.subject', ['subject' => $subject['subject']]) }}" 
                                       class="text-sm text-blue-600 hover:text-blue-800">View details →</a>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No quiz attempts yet. Start taking quizzes to see your performance!</p>
                        @endif
                    </div>
                </div>

                <!-- Grade Distribution -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Grade Distribution</h3>
                        <div class="h-64">
                            <canvas id="gradeDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Time Analytics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Time Analytics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ $timeAnalytics['total_minutes'] }}</p>
                            <p class="text-sm text-gray-600">Total Minutes</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ $timeAnalytics['average_minutes'] }}</p>
                            <p class="text-sm text-gray-600">Avg per Quiz</p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <p class="text-2xl font-bold text-yellow-600">{{ $timeAnalytics['fastest_completion'] }}</p>
                            <p class="text-sm text-gray-600">Fastest Quiz</p>
                        </div>
                        <div class="text-center p-4 bg-red-50 rounded-lg">
                            <p class="text-2xl font-bold text-red-600">{{ $timeAnalytics['slowest_completion'] }}</p>
                            <p class="text-sm text-gray-600">Slowest Quiz</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Topic Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Topic Performance</h3>
                    @if($topicPerformance->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempts</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Best Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Improvement</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($topicPerformance as $topic)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $topic['topic'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $topic['subject'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $topic['total_attempts'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $topic['average_score'] }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $topic['best_score'] }}%
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($topic['improvement'] > 5)
                                                <span class="text-green-600 font-medium">+{{ $topic['improvement'] }}%</span>
                                            @elseif($topic['improvement'] < -5)
                                                <span class="text-red-600 font-medium">{{ $topic['improvement'] }}%</span>
                                            @else
                                                <span class="text-gray-600">{{ $topic['improvement'] }}%</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500">No topic data available yet.</p>
                    @endif
                </div>
            </div>

            <!-- Export Data -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Your Data</h3>
                    <p class="text-gray-600 mb-4">Download your complete quiz performance data for personal records or further analysis.</p>
                    <a href="{{ route('analytics.export') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export Analytics Data
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Integration -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Score Progression Chart
        const scoreProgressionCtx = document.getElementById('scoreProgressionChart').getContext('2d');
        new Chart(scoreProgressionCtx, {
            type: 'line',
            data: {
                labels: @json($scoreProgression->pluck('date')),
                datasets: [{
                    label: 'Quiz Score',
                    data: @json($scoreProgression->pluck('score')),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        title: {
                            display: true,
                            text: 'Score (%)'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Recent Quiz Performance'
                    }
                }
            }
        });

        // Weekly Performance Chart
        const weeklyCtx = document.getElementById('weeklyPerformanceChart').getContext('2d');
        new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: @json(collect($weeklyPerformance)->pluck('week')),
                datasets: [{
                    label: 'Quiz Attempts',
                    data: @json(collect($weeklyPerformance)->pluck('attempts')),
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    yAxisID: 'y'
                }, {
                    label: 'Average Score',
                    data: @json(collect($weeklyPerformance)->pluck('average_score')),
                    type: 'line',
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Number of Attempts'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Average Score (%)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                        max: 100
                    }
                }
            }
        });

        // Grade Distribution Chart
        const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
        new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: ['A', 'B', 'C', 'D', 'F'],
                datasets: [{
                    data: @json(array_values($gradeDistribution)),
                    backgroundColor: [
                        '#10B981', // Green for A
                        '#3B82F6', // Blue for B  
                        '#F59E0B', // Yellow for C
                        '#F97316', // Orange for D
                        '#EF4444'  // Red for F
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</x-app-layout>