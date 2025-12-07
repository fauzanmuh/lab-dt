<?php

namespace App\Controllers;

use App\Models\Member;
use Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Refresh stats (using Stored Procedure)
        // Note: In a real high-traffic app, this might be done via cron or background job
        $this->db()->execute("CALL refresh_dashboard_stats()");

        // Fetch stats (using Materialized View)
        $statsResult = $this->db()->query("SELECT * FROM mv_dashboard_stats");
        $stats = $statsResult[0] ?? [
            'total_users' => 0,
            'total_news' => 0,
            'total_gallery' => 0,
            'total_publications' => 0,
            'pending_approvals' => 0
        ];

        // Map stats to view expected keys
        $viewStats = [
            'users' => $stats['total_users'],
            'news' => $stats['total_news'],
            'gallery' => $stats['total_gallery'],
            'publications' => $stats['total_publications'],
            'pending_approvals' => $stats['pending_approvals']
        ];

        // Fetch Recent Activity (using View)
        $recentActivity = $this->db()->query("SELECT * FROM view_recent_activity ORDER BY activity_time DESC LIMIT 10");

        return $this->view('admin/dashboard', [
            'title' => 'Dashboard - Lab Admin',
            'layout' => 'layouts/admin',
            'pageTitle' => 'Ringkasan Dashboard',
            'stats' => $viewStats,
            'recentActivity' => $recentActivity,
        ]);
    }
}
