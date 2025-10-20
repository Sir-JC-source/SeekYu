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

        return redirect()->route('incident-reports.logs')->with('success', 'Incident report submitted successfully!');
    }

    public function logs() {
        $reports = IncidentReport::with('parties')->latest()->get();
        return view('IncidentReports.logs', compact('reports'));
    }
}
