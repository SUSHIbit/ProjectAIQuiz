<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Analytics Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Platform Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
                                <p class="text-2xl font-bold text-blue-600">{{ number_format($totalUsers) }}</p>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Active Users</h3>
                                <p class="text-2xl font-bold text-green-600">{{ number_format($activeUsers) }}</p>
                                <p class="text-xs text-gray-500">Last 30 days</p>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Quizzes</h3>
                                <p class="text-2xl font-bold text-yellow-600">{{ number_format($totalQuizzes) }}</p>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Total Attempts</h3>
                                <p class="text-2xl font-bold text-purple-600">{{ number_format($totalAttempts) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-red-100 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-medium text-gray-500">Platform Avg</h3>
                                <p class="text-2xl font-bold text-red-600">{{ number_format($averagePlatformScore, 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Growth Metrics Chart -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Platform Growth (Last 12 Months)</h3>
                    <div class="h-64">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- User Engagement -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Engagement</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Daily Active Users</span>
                                <span class="text-lg font-bold text-blue-600">{{ $userEngagement['daily_active_users'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Weekly Active Users</span>
                                <span class="text-lg font-bold text-green-600">{{ $userEngagement['weekly_active_users'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Monthly Active Users</span>
                                <span class="text-lg font-bold text-yellow-600">{{ $userEngagement['monthly_active_users'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-500">Avg Sessions/User</span>
                                <span class="text-lg font-bold text-purple-600">{{ number_format($userEngagement['average_sessions_per_user'], 1) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tier Performance Comparison -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Free vs Premium Performance</h3>
                        <div class="space-y-6">
                            <div class="border rounded-lg p-4 bg-blue-50">
                                <h4 class="font-medium text-blue-900 mb-2">Free Tier</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Users:</span>
                                        <span class="font-medium ml-2">{{ number_format($tierPerformance['free']['user_count']) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Avg Score:</span>
                                        <span class="font-medium ml-2">{{ number_format($tierPerformance['free']['average_score'], 1) }}%</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-gray-600">Total Attempts:</span>
                                        <span class="font-medium ml-2">{{ number_format($tierPerformance['free']['total_attempts']) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-lg p-4 bg-green-50">
                                <h4 class="font-medium text-green-900 mb-2">Premium Tier</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Users:</span>
                                        <span class="font-medium ml-2">{{ number_format($tierPerformance['premium']['user_count']) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Avg Score:</span>
                                        <span class="font-medium ml-2">{{ number_format($tierPerformance['premium']['average_score'], 1) }}%</span>
                                    </div>
                                    <div class="col-span-2">
                                        <span class="text-gray-600">Total Attempts:</span>
                                        <span class="font-medium ml-2">{{ number_format($tierPerformance['premium']['total_attempts']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Popular Subjects and Topics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Subjects</h3>
                        @if($popularSubjects->count() > 0)
                            <div class="space-y-3">
                                @foreach($popularSubjects->take(10) as $subject)
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $subject->subject }}</span>
                                    <span class="text-sm text-gray-600">{{ number_format($subject->attempt_count) }} attempts</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($subject->attempt_count / $popularSubjects->first()->attempt_count) * 100 }}%"></div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No subject data available.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Popular Topics</h3>
                        @if($popularTopics->count() > 0)
                            <div class="space-y-2">
                                @foreach($popularTopics->take(10) as $topic)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <div>
                                        <span class="text-sm font-medium text-gray-900">{{ $topic->topic }}</span>
                                        <span class="text-xs text-gray-500 block">{{ $topic->subject }}</span>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ number_format($topic->attempt_count) }}</span>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No topic data available.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quiz Creation Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quiz Creation Statistics</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ number_format($quizCreationStats['ai_generated']) }}</p>
                            <p class="text-sm text-gray-600">AI Generated Quizzes</p>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ number_format($quizCreationStats['manual_created']) }}</p>
                            <p class="text-sm text-gray-600">Manual Quizzes</p>
                        </div>
                        <div class="text-center p-4 bg-yellow-50 rounded-lg">
                            <p class="text-2xl font-bold text-yellow-600">{{ $quizCreationStats['average_questions_per_quiz'] }}</p>
                            <p class="text-sm text-gray-600">Avg Questions/Quiz</p>
                        </div>
                    </div>

                    <!-- Most Active Creators -->
                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-3">Most Active Quiz Creators</h4>
                        <div class="space-y-2">
                            @foreach($quizCreationStats['most_active_creators'] as $creator)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-900">{{ $creator->name }}</span>
                                <span class="text-sm text-gray-600">{{ $creator->quizzes_count }} quizzes</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Patterns -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hourly Usage Pattern</h3>
                        <div class="h-64">
                            <canvas id="hourlyUsageChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Usage Pattern</h3>
                        <div class="h-64">
                            <canvas id="weeklyUsageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Integration -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Growth Chart
        const growthCtx = document.getElementById('growthChart').getContext('2d');
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: @json(collect($growthMetrics)->pluck('month')),
                datasets: [{
                    label: 'New Users',
                    data: @json(collect($growthMetrics)->pluck('new_users')),
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    yAxisID: 'y'
                }, {
                    label: 'Quiz Attempts',
                    data: @json(collect($growthMetrics)->pluck('quiz_attempts')),
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    yAxisID: 'y1'
                }, {
                    label: 'Quizzes Created',
                    data: @json(collect($growthMetrics)->pluck('quizzes_created')),
                    borderColor: 'rgb(245, 158, 11)',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    yAxisID: 'y'
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
                            text: 'Users / Quizzes'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Attempts'
                        },
                        grid: {
                            drawOnChartArea: false,
                        }
                    }
                }
            }
        });

        // Hourly Usage Chart
        const hourlyCtx = document.getElementById('hourlyUsageChart').getContext('2d');
        const hourlyData = @json($usagePatterns['hourly']);
        const hourlyLabels = Array.from({length: 24}, (_, i) => i + ':00');
        const hourlyValues = hourlyLabels.map((_, i) => hourlyData[i] || 0);

        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: hourlyLabels,
                datasets: [{
                    label: 'Quiz Attempts',
                    data: hourlyValues,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Attempts'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Hour of Day'
                        }
                    }
                }
            }
        });

        // Weekly Usage Chart
        const weeklyCtx = document.getElementById('weeklyUsageChart').getContext('2d');
        const weeklyData = @json($usagePatterns['weekly']);
        const weeklyLabels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const weeklyValues = weeklyLabels.map((_, i) => weeklyData[i + 1] || 0);

        new Chart(weeklyCtx, {
            type: 'doughnut',
            data: {
                labels: weeklyLabels,
                datasets: [{
                    data: weeklyValues,
                    backgroundColor: [
                        '#EF4444', // Sunday - Red
                        '#3B82F6', // Monday - Blue
                        '#10B981', // Tuesday - Green
                        '#F59E0B', // Wednesday - Yellow
                        '#8B5CF6', // Thursday - Purple
                        '#F97316', // Friday - Orange
                        '#06B6D4'  // Saturday - Cyan
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