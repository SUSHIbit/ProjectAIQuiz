<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function userDashboard()
    {
        $user = auth()->user();
        
        // Get user's quiz attempts with related data
        $attempts = QuizAttempt::with(['quiz'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate overall statistics
        $totalAttempts = $attempts->count();
        $averageScore = $attempts->avg('score') ?? 0;
        $bestScore = $attempts->max('score') ?? 0;
        $totalTimeSpent = $attempts->sum('time_taken') ?? 0;

        // Score progression over time (last 30 attempts or all if less)
        $recentAttempts = $attempts->take(30);
        $scoreProgression = $recentAttempts->map(function ($attempt) {
            return [
                'date' => $attempt->created_at->format('M d'),
                'score' => $attempt->score,
                'quiz_title' => $attempt->quiz->title,
                'created_at' => $attempt->created_at->toISOString(),
            ];
        })->reverse()->values();

        // Subject-wise performance
        $subjectPerformance = $attempts->groupBy('quiz.subject')->map(function ($subjectAttempts) {
            return [
                'subject' => $subjectAttempts->first()->quiz->subject,
                'total_attempts' => $subjectAttempts->count(),
                'average_score' => round($subjectAttempts->avg('score'), 1),
                'best_score' => $subjectAttempts->max('score'),
                'latest_attempt' => $subjectAttempts->first()->created_at,
            ];
        })->values();

        // Topic-wise performance (for detailed view)
        $topicPerformance = $attempts->groupBy('quiz.topic')->map(function ($topicAttempts) {
            return [
                'topic' => $topicAttempts->first()->quiz->topic,
                'subject' => $topicAttempts->first()->quiz->subject,
                'total_attempts' => $topicAttempts->count(),
                'average_score' => round($topicAttempts->avg('score'), 1),
                'best_score' => $topicAttempts->max('score'),
                'improvement' => $this->calculateImprovement($topicAttempts),
            ];
        })->values();

        // Weekly performance (last 12 weeks)
        $weeklyPerformance = $this->getWeeklyPerformance($user->id);

        // Grade distribution
        $gradeDistribution = $this->getGradeDistribution($attempts);

        // Time spent analytics
        $timeAnalytics = $this->getTimeAnalytics($attempts);

        return view('analytics.user-dashboard', compact(
            'user',
            'totalAttempts',
            'averageScore',
            'bestScore',
            'totalTimeSpent',
            'scoreProgression',
            'subjectPerformance',
            'topicPerformance',
            'weeklyPerformance',
            'gradeDistribution',
            'timeAnalytics'
        ));
    }

    public function subjectAnalytics(Request $request)
    {
        $user = auth()->user();
        $subject = $request->input('subject');

        $attempts = QuizAttempt::with(['quiz'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('quiz', function ($query) use ($subject) {
                $query->where('subject', $subject);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Subject-specific analytics
        $subjectStats = [
            'total_attempts' => $attempts->count(),
            'average_score' => round($attempts->avg('score'), 1),
            'best_score' => $attempts->max('score'),
            'worst_score' => $attempts->min('score'),
            'improvement_trend' => $this->calculateImprovementTrend($attempts),
            'consistency_score' => $this->calculateConsistency($attempts),
        ];

        // Topic breakdown within subject
        $topicBreakdown = $attempts->groupBy('quiz.topic')->map(function ($topicAttempts) {
            return [
                'topic' => $topicAttempts->first()->quiz->topic,
                'attempts' => $topicAttempts->count(),
                'average_score' => round($topicAttempts->avg('score'), 1),
                'best_score' => $topicAttempts->max('score'),
                'latest_score' => $topicAttempts->first()->score,
                'improvement' => $this->calculateImprovement($topicAttempts),
            ];
        })->values();

        // Difficulty analysis (based on average scores across all users)
        $difficultyAnalysis = $this->getDifficultyAnalysis($subject);

        return view('analytics.subject-detail', compact(
            'subject',
            'subjectStats',
            'topicBreakdown',
            'difficultyAnalysis',
            'attempts'
        ));
    }

    public function adminAnalytics()
    {
        // Global platform statistics
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('role', 'user')
            ->whereHas('quizAttempts', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDays(30));
            })->count();

        $totalQuizzes = Quiz::count();
        $totalAttempts = QuizAttempt::where('status', 'completed')->count();
        $averagePlatformScore = QuizAttempt::where('status', 'completed')->avg('score') ?? 0;

        // User engagement metrics
        $userEngagement = $this->getUserEngagementMetrics();

        // Popular subjects and topics
        $popularSubjects = $this->getPopularSubjects();
        $popularTopics = $this->getPopularTopics();

        // Platform growth metrics
        $growthMetrics = $this->getGrowthMetrics();

        // Quiz creation analytics
        $quizCreationStats = $this->getQuizCreationStats();

        // Performance analytics by tier
        $tierPerformance = $this->getTierPerformanceComparison();

        // Usage patterns
        $usagePatterns = $this->getUsagePatterns();

        return view('analytics.admin-dashboard', compact(
            'totalUsers',
            'activeUsers',
            'totalQuizzes',
            'totalAttempts',
            'averagePlatformScore',
            'userEngagement',
            'popularSubjects',
            'popularTopics',
            'growthMetrics',
            'quizCreationStats',
            'tierPerformance',
            'usagePatterns'
        ));
    }

    public function exportUserData()
    {
        $user = auth()->user();
        
        $attempts = QuizAttempt::with(['quiz'])
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = [
            'user_info' => [
                'name' => $user->name,
                'email' => $user->email,
                'tier' => $user->tier,
                'exported_at' => now()->toISOString(),
            ],
            'summary' => [
                'total_attempts' => $attempts->count(),
                'average_score' => round($attempts->avg('score'), 2),
                'best_score' => $attempts->max('score'),
                'total_time_spent_minutes' => round($attempts->sum('time_taken') / 60, 2),
            ],
            'attempts' => $attempts->map(function ($attempt) {
                return [
                    'quiz_title' => $attempt->quiz->title,
                    'subject' => $attempt->quiz->subject,
                    'topic' => $attempt->quiz->topic,
                    'score' => $attempt->score,
                    'correct_answers' => $attempt->correct_answers,
                    'total_questions' => $attempt->total_questions,
                    'time_taken_seconds' => $attempt->time_taken,
                    'grade' => $attempt->grade,
                    'date' => $attempt->created_at->toISOString(),
                ];
            }),
        ];

        return response()->json($data)
            ->header('Content-Disposition', 'attachment; filename="quiz_analytics_' . $user->id . '_' . now()->format('Y-m-d') . '.json"');
    }

    private function calculateImprovement($attempts)
    {
        if ($attempts->count() < 2) return 0;
        
        $sorted = $attempts->sortBy('created_at');
        $first = $sorted->first()->score;
        $last = $sorted->last()->score;
        
        return round($last - $first, 1);
    }

    private function calculateImprovementTrend($attempts)
    {
        if ($attempts->count() < 3) return 'insufficient_data';
        
        $scores = $attempts->sortBy('created_at')->pluck('score')->toArray();
        $n = count($scores);
        
        // Calculate linear regression slope
        $sumX = array_sum(range(1, $n));
        $sumY = array_sum($scores);
        $sumXY = 0;
        $sumX2 = 0;
        
        foreach ($scores as $i => $score) {
            $x = $i + 1;
            $sumXY += $x * $score;
            $sumX2 += $x * $x;
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        
        if ($slope > 1) return 'improving';
        if ($slope < -1) return 'declining';
        return 'stable';
    }

    private function calculateConsistency($attempts)
    {
        if ($attempts->count() < 2) return 100;
        
        $scores = $attempts->pluck('score');
        $mean = $scores->avg();
        $variance = $scores->map(function ($score) use ($mean) {
            return pow($score - $mean, 2);
        })->avg();
        
        $standardDeviation = sqrt($variance);
        
        // Convert to consistency score (lower deviation = higher consistency)
        return max(0, 100 - ($standardDeviation * 2));
    }

    private function getWeeklyPerformance($userId)
    {
        $weeks = [];
        for ($i = 11; $i >= 0; $i--) {
            $startDate = Carbon::now()->subWeeks($i)->startOfWeek();
            $endDate = Carbon::now()->subWeeks($i)->endOfWeek();
            
            $weekAttempts = QuizAttempt::where('user_id', $userId)
                ->where('status', 'completed')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
            
            $weeks[] = [
                'week' => $startDate->format('M d'),
                'attempts' => $weekAttempts->count(),
                'average_score' => $weekAttempts->count() > 0 ? round($weekAttempts->avg('score'), 1) : 0,
            ];
        }
        
        return $weeks;
    }

    private function getGradeDistribution($attempts)
    {
        $distribution = ['A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0];
        
        foreach ($attempts as $attempt) {
            $grade = $attempt->grade;
            if (isset($distribution[$grade])) {
                $distribution[$grade]++;
            }
        }
        
        return $distribution;
    }

    private function getTimeAnalytics($attempts)
    {
        $totalTime = $attempts->sum('time_taken');
        $averageTime = $attempts->avg('time_taken') ?? 0;
        
        return [
            'total_minutes' => round($totalTime / 60, 1),
            'average_minutes' => round($averageTime / 60, 1),
            'fastest_completion' => round($attempts->min('time_taken') / 60, 1),
            'slowest_completion' => round($attempts->max('time_taken') / 60, 1),
        ];
    }

    private function getDifficultyAnalysis($subject)
    {
        return DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->where('quizzes.subject', $subject)
            ->where('quiz_attempts.status', 'completed')
            ->select('quizzes.topic', DB::raw('AVG(quiz_attempts.score) as avg_score'), DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('quizzes.topic')
            ->having('attempt_count', '>=', 3)
            ->orderBy('avg_score', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'topic' => $item->topic,
                    'difficulty' => $item->avg_score < 60 ? 'Hard' : ($item->avg_score < 80 ? 'Medium' : 'Easy'),
                    'average_score' => round($item->avg_score, 1),
                    'attempt_count' => $item->attempt_count,
                ];
            });
    }

    private function getUserEngagementMetrics()
    {
        $last30Days = Carbon::now()->subDays(30);
        
        return [
            'daily_active_users' => User::whereHas('quizAttempts', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subDay());
            })->count(),
            'weekly_active_users' => User::whereHas('quizAttempts', function ($query) {
                $query->where('created_at', '>=', Carbon::now()->subWeek());
            })->count(),
            'monthly_active_users' => User::whereHas('quizAttempts', function ($query) use ($last30Days) {
                $query->where('created_at', '>=', $last30Days);
            })->count(),
            'average_sessions_per_user' => DB::table('quiz_attempts')
                ->where('created_at', '>=', $last30Days)
                ->where('status', 'completed')
                ->count() / max(1, User::whereHas('quizAttempts', function ($query) use ($last30Days) {
                    $query->where('created_at', '>=', $last30Days);
                })->count()),
        ];
    }

    private function getPopularSubjects()
    {
        return DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->where('quiz_attempts.status', 'completed')
            ->select('quizzes.subject', DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('quizzes.subject')
            ->orderBy('attempt_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getPopularTopics()
    {
        return DB::table('quiz_attempts')
            ->join('quizzes', 'quiz_attempts.quiz_id', '=', 'quizzes.id')
            ->where('quiz_attempts.status', 'completed')
            ->select('quizzes.topic', 'quizzes.subject', DB::raw('COUNT(*) as attempt_count'))
            ->groupBy('quizzes.topic', 'quizzes.subject')
            ->orderBy('attempt_count', 'desc')
            ->limit(15)
            ->get();
    }

    private function getGrowthMetrics()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $months[] = [
                'month' => $month->format('M Y'),
                'new_users' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'quiz_attempts' => QuizAttempt::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                'quizzes_created' => Quiz::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
            ];
        }
        
        return $months;
    }

    private function getQuizCreationStats()
    {
        return [
            'ai_generated' => Quiz::where('source_type', 'ai')->count(),
            'manual_created' => Quiz::where('source_type', 'manual')->count(),
            'average_questions_per_quiz' => round(Quiz::avg('total_questions'), 1),
            'most_active_creators' => User::withCount('quizzes')
                ->orderBy('quizzes_count', 'desc')
                ->limit(5)
                ->get(['name', 'quizzes_count']),
        ];
    }

    private function getTierPerformanceComparison()
    {
        return [
            'free' => [
                'user_count' => User::where('tier', 'free')->count(),
                'average_score' => DB::table('users')
                    ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
                    ->where('users.tier', 'free')
                    ->where('quiz_attempts.status', 'completed')
                    ->avg('quiz_attempts.score') ?? 0,
                'total_attempts' => DB::table('users')
                    ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
                    ->where('users.tier', 'free')
                    ->where('quiz_attempts.status', 'completed')
                    ->count(),
            ],
            'premium' => [
                'user_count' => User::where('tier', 'premium')->count(),
                'average_score' => DB::table('users')
                    ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
                    ->where('users.tier', 'premium')
                    ->where('quiz_attempts.status', 'completed')
                    ->avg('quiz_attempts.score') ?? 0,
                'total_attempts' => DB::table('users')
                    ->join('quiz_attempts', 'users.id', '=', 'quiz_attempts.user_id')
                    ->where('users.tier', 'premium')
                    ->where('quiz_attempts.status', 'completed')
                    ->count(),
            ],
        ];
    }

    private function getUsagePatterns()
    {
        // Hour of day usage pattern
        $hourlyUsage = DB::table('quiz_attempts')
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderBy('hour')
            ->pluck('count', 'hour')
            ->toArray();

        // Day of week usage pattern
        $weeklyUsage = DB::table('quiz_attempts')
            ->where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->select(DB::raw('DAYOFWEEK(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy(DB::raw('DAYOFWEEK(created_at)'))
            ->orderBy('day')
            ->pluck('count', 'day')
            ->toArray();

        return [
            'hourly' => $hourlyUsage,
            'weekly' => $weeklyUsage,
        ];
    }
}