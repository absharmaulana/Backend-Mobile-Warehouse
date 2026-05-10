<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        $commonStats = [
            'items_total' => Item::query()->count(),
            'invoices_total' => Invoice::query()->count(),
            'active_users_total' => User::query()->where('is_active', true)->count(),
        ];

        $roleWidgets = match ($user->role) {
            User::ROLE_SUPER_ADMIN => ['user_overview', 'inventory_health', 'invoice_summary'],
            User::ROLE_ADMIN => ['inventory_health', 'invoice_summary'],
            User::ROLE_FINANCE => ['invoice_summary'],
            User::ROLE_PROJECT_MANAGER => ['inventory_health', 'project_stock_alert'],
            default => [],
        };

        return $this->successResponse([
            'role' => $user->role,
            'dashboard_key' => $user->dashboardKey(),
            'widgets' => $roleWidgets,
            'stats' => $commonStats,
        ], 'Dashboard data loaded');
    }
}