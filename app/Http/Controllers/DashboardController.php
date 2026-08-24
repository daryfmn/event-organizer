<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Event;
use App\Models\Quotation;
use App\Models\Vendor;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalEvents = Event::count();
        $activeThisMonth = Event::where('start_date', '<=', now()->endOfMonth())
            ->where('end_date', '>=', now()->startOfMonth())
            ->count();

        $totalQuotations = Quotation::count();
        $awaitingApproval = Quotation::whereHas('event', fn ($q) => $q->where('status', 'sent'))->count();

        $totalClients = Client::count();
        $newClientsThisMonth = Client::where('created_at', '>=', now()->startOfMonth())->count();

        $totalVendors = Vendor::count();
        $vendorCategories = Vendor::whereNotNull('category')
            ->distinct('category')
            ->count('category');

        return view('dashboard', [
            'stats' => [
                'total_events' => $totalEvents,
                'active_this_month' => $activeThisMonth,
                'total_quotations' => $totalQuotations,
                'awaiting_approval' => $awaitingApproval,
                'total_clients' => $totalClients,
                'new_clients_this_month' => $newClientsThisMonth,
                'total_vendors' => $totalVendors,
                'vendor_categories' => $vendorCategories,
            ],
            'recentEvents' => Event::with(['client', 'eventType'])
                ->latest()
                ->limit(5)
                ->get(),
            'recentQuotations' => Quotation::with(['event'])
                ->latest()
                ->limit(5)
                ->get(),
        ]);
    }
}
