<?php

namespace App\Exports;

use App\Models\Leave;
use App\Models\RegisteredUsers;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class LeavesPendingExport
{
    public function headings(): array
    {
        return [
            '#',
            'Requestor',
            'Position',
            'Type',
            'Reason',
            'Duration',
            'Date Requested',
        ];
    }

    public function collection(): Collection
    {
        $user = Auth::user();
        $role = $user?->role;

        $query = Leave::query()->where('status', 'Pending');

        // Match the same role filtering logic as LeaveController@pending()
        switch ($role) {
            case 'hr-officer':
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['security-guard', 'head-guard']);
                });
                break;
            case 'admin':
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['hr-officer', 'security-guard', 'head-guard']);
                });
                break;
            case 'super-admin':
                $query->whereHas('user', function ($q) {
                    $q->whereIn('role', ['admin', 'hr-officer']);
                });
                break;
            default:
                $query->whereRaw('1 = 0');
                break;
        }

        $leaves = $query->orderBy('created_at', 'desc')->get();

        return $leaves->values()->map(function (Leave $leave, int $i) {
            $duration = $leave->date_from && $leave->date_to
                ? Carbon::parse($leave->date_from)->format('F d, Y') . ' - ' . Carbon::parse($leave->date_to)->format('F d, Y')
                : (string) $leave->duration;

            return [
                $i + 1,
                $leave->requestor,
                $leave->position,
                $leave->leave_type,
                $leave->reason,
                $duration,
                $leave->created_at?->format('F d, Y'),
            ];
        });
    }
}

