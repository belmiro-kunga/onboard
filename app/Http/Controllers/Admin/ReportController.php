<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseAdminController;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends BaseAdminController
{
    /**
     * Exibe o dashboard de relatórios.
     */
    public function index(Request $request): View
    {
        $period = $request->get('period', 'month');
        
        // Validate period
        if (!in_array($period, ['week', 'month', 'quarter', 'year'])) {
            $period = 'month';
        }
        
        // Mock data for now - replace with actual database queries
        $stats = [
            'total_users' => 150,
            'active_users' => 120,
            'total_quizzes' => 25,
            'avg_quiz_score' => 78.5,
        ];
        
        $reports = [
            'new_users' => 15,
            'quiz_attempts' => 245,
            'certificates_issued' => 89,
            'notifications_sent' => 1250,
            'user_activity' => collect([
                ['date' => '2025-07-15', 'count' => 5],
                ['date' => '2025-07-16', 'count' => 8],
                ['date' => '2025-07-17', 'count' => 12],
                ['date' => '2025-07-18', 'count' => 7],
                ['date' => '2025-07-19', 'count' => 15],
                ['date' => '2025-07-20', 'count' => 10],
                ['date' => '2025-07-21', 'count' => 9],
            ]),
            'quiz_performance' => collect([
                ['date' => '2025-07-15', 'avg_score' => 75.2],
                ['date' => '2025-07-16', 'avg_score' => 78.8],
                ['date' => '2025-07-17', 'avg_score' => 82.1],
                ['date' => '2025-07-18', 'avg_score' => 76.5],
                ['date' => '2025-07-19', 'avg_score' => 80.3],
                ['date' => '2025-07-20', 'avg_score' => 79.7],
                ['date' => '2025-07-21', 'avg_score' => 81.2],
            ]),
        ];
        
        $topPerformers = collect([
            (object) [
                'name' => 'João Silva',
                'email' => 'joao@example.com',
                'avg_score' => 95.5,
                'attempts_count' => 12,
                'is_active' => true,
            ],
            (object) [
                'name' => 'Maria Santos',
                'email' => 'maria@example.com',
                'avg_score' => 92.3,
                'attempts_count' => 8,
                'is_active' => true,
            ],
            (object) [
                'name' => 'Pedro Costa',
                'email' => 'pedro@example.com',
                'avg_score' => 89.7,
                'attempts_count' => 15,
                'is_active' => true,
            ],
        ]);
        
        return view('admin.reports.index', compact('period', 'stats', 'reports', 'topPerformers'));
    }
}