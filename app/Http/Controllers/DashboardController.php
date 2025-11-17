<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Booking;
use App\Models\Project;
use App\Models\Payment; 
use Carbon\Carbon;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filters from the request
        $filterType = $request->get('filter', 'yearly'); // Default to 'yearly'
        $year = $request->get('year', date('Y')); // Default to the current year
        $month = $request->get('month', date('m')); // Default to the current month

        // Fetch total services with status 'available'
        $totalServices = Service::where('status', 'available')->count();

        // Fetch total bookings
        $totalBookings = Booking::count();

        // Fetch total projects with specific statuses
        $totalProjects = Project::whereIn('project_status', ['pending', 'active', 'hold', 'cancel', 'finish'])->count();

        // Calculate total revenue
        $totalRevenue = Payment::sum('amount');

        // Prepare data for the revenue chart
        $revenues = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->when($filterType === 'monthly', function ($query) use ($year, $month) {
                return $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            })
            ->when($filterType === 'yearly', function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $chartData = [
            'labels' => array_map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)), range(1, 12)),
            'data' => array_map(fn($m) => $revenues->get($m, 0), range(1, 12)),
        ];

        // Booking status distribution
        $bookingStatusCounts = Booking::selectRaw('booking_status, COUNT(*) as count')
            ->groupBy('booking_status')
            ->pluck('count', 'booking_status');

        $bookingStatusData = [
            'labels' => ['Pending', 'Confirmed', 'Visited', 'Cancelled', 'Declined'],
            'data' => [
                $bookingStatusCounts->get('pending', 0),
                $bookingStatusCounts->get('confirmed', 0),
                $bookingStatusCounts->get('visited', 0),
                $bookingStatusCounts->get('cancelled', 0),
                $bookingStatusCounts->get('declined', 0),
            ]
        ];

        // Project status distribution
        $projectStatusCounts = Project::selectRaw('project_status, COUNT(*) as count')
            ->groupBy('project_status')
            ->pluck('count', 'project_status');

        $projectStatusData = [
            'labels' => ['Pending', 'Active', 'Hold', 'Cancelled', 'Finished'],
            'data' => [
                $projectStatusCounts->get('pending', 0),
                $projectStatusCounts->get('active', 0),
                $projectStatusCounts->get('hold', 0),
                $projectStatusCounts->get('cancel', 0),
                $projectStatusCounts->get('finish', 0),
            ]
        ];

        // Filter bookings, projects, and payments based on the selected filter
        $bookings = Booking::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->when($filterType === 'monthly', function ($query) use ($year, $month) {
                return $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            })
            ->when($filterType === 'yearly', function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $projects = Project::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->when($filterType === 'monthly', function ($query) use ($year, $month) {
                return $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            })
            ->when($filterType === 'yearly', function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $payments = Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->when($filterType === 'monthly', function ($query) use ($year, $month) {
                return $query->whereYear('created_at', $year)->whereMonth('created_at', $month);
            })
            ->when($filterType === 'yearly', function ($query) use ($year) {
                return $query->whereYear('created_at', $year);
            })
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $bookingsPaymentsData = [
            'labels' => array_map(fn($m) => date('F', mktime(0, 0, 0, $m, 1)), range(1, 12)),
            'bookings' => array_map(fn($m) => $bookings->get($m, 0), range(1, 12)),
            'projects' => array_map(fn($m) => $projects->get($m, 0), range(1, 12)),
            'payments' => array_map(fn($m) => $payments->get($m, 0), range(1, 12)),
        ];

        // Service popularity
        $servicePopularity = Service::withCount('projects')
            ->orderByDesc('projects_count')
            ->get();

        $filteredServices = $servicePopularity->filter(fn($service) => $service->projects_count > 0);

        $serviceNames = $filteredServices->pluck('name');
        $projectCounts = $filteredServices->pluck('projects_count');

        return view('dashboard', compact(
            'serviceNames',
            'projectCounts',
            'totalServices',
            'totalBookings',
            'totalProjects',
            'totalRevenue',
            'chartData',
            'bookingStatusData',
            'projectStatusData',
            'bookingsPaymentsData',
            'filterType',
            'year',
            'month'
        ));
    }
}
