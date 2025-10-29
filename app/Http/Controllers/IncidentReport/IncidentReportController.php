<?php

namespace App\Http\Controllers\IncidentReport;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\IncidentReport;
use App\Models\IncidentReportParty;

class IncidentReportController extends Controller
{
    public function create() {
        return view('IncidentReports.submit');
    }

    public function store(Request $request) {
        $data = $request->validate([
            'incident_name' => 'required|string|max:255',
            'date_of_incident' => 'required|date',
            'location' => 'required|string|max:255',
            'specific_area' => 'required|string|max:255',
            'incident_description' => 'required|string',
            'parties' => 'required|array|min:1',
            'parties.*.name' => 'required|string|max:255',
            'parties.*.role' => 'required|string|max:255',
            'parties.*.contact' => 'required|string|max:255',
            'parties.*.statement' => 'required|string',
        ]);

        $report = IncidentReport::create($data);

        foreach ($data['parties'] as $party) {
            $report->parties()->create($party);
        }

        return redirect()->route('incident-reports.submit')->with('success', 'Incident report submitted successfully!');
    }

    public function logs(Request $request) {
        $query = IncidentReport::with('parties');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('incident_name', 'like', "%{$search}%")
                  ->orWhere('incident_description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('date_of_incident', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date_of_incident', '<=', $request->date_to);
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Quick filters
        if ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case 'today':
                    $query->whereDate('date_of_incident', today());
                    break;
                case 'week':
                    $query->whereBetween('date_of_incident', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('date_of_incident', now()->month)
                          ->whereYear('date_of_incident', now()->year);
                    break;
            }
        }

        // Get statistics
        $totalReports = IncidentReport::count();
        $resolvedReports = IncidentReport::where('status', 'resolved')->count();
        $pendingReports = IncidentReport::where('status', 'pending')->count();
        $investigatingReports = IncidentReport::where('status', 'investigating')->count();

        // Get unique locations for filter dropdown
        $locations = IncidentReport::distinct()->pluck('location')->filter()->values();

        // Paginate results
        $reports = $query->latest()->paginate($request->get('per_page', 12));

        return view('IncidentReports.logs', compact(
            'reports',
            'totalReports',
            'resolvedReports',
            'pendingReports',
            'investigatingReports',
            'locations'
        ));
    }

    public function updateStatus(Request $request, $id) {
        $request->validate([
            'status' => 'required|in:pending,investigating,resolved',
        ]);

        $report = IncidentReport::findOrFail($id);
        $oldStatus = $report->status;
        $newStatus = $request->status;

        // Only send notification if status actually changed
        if ($oldStatus !== $newStatus) {
            $report->update(['status' => $newStatus]);

            // Get users to notify based on roles (admins and security personnel)
            $usersToNotify = \App\Models\User::whereIn('role', ['Super Admin', 'Admin', 'HR Officer', 'Security Guard', 'Head Guard'])->get();

            // Send notification to each user
            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\IncidentReportStatusUpdated($report, $oldStatus, $newStatus));
            }
        }

        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
}
