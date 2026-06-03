<?php

namespace App\Exports;

use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LeavesProcessedExport
{
    public function headings(): array
    {
        return [
            '#',
            'Requestor',
            'Position',
            'Type of Leave',
            'Reason',
            'Duration',
            'Date Requested',
            'Status',
            'Processed By',
        ];
    }

    public function collection(): Collection
    {
        $leaves = Leave::query()
            ->whereIn('status', ['Approved', 'Rejected'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Eager-load approver/rejecter to avoid N+1.
        $leaves->loadMissing(['approver', 'rejecter']);

        return $leaves->values()->map(function (Leave $leave, int $i) {
            $duration = $leave->date_from && $leave->date_to
                ? Carbon::parse($leave->date_from)->format('M d, Y') . ' - ' . Carbon::parse($leave->date_to)->format('M d, Y')
                : (string) $leave->duration;

            $processedBy = '-';
            if ($leave->status === 'Approved') {
                $processedBy = $leave->approver?->fullname ?? '-';
            } elseif ($leave->status === 'Rejected') {
                $processedBy = $leave->rejecter?->fullname ?? '-';
            }

            return [
                $i + 1,
                $leave->requestor,
                $leave->position,
                $leave->leave_type,
                $leave->reason,
                $duration,
                $leave->created_at?->format('F d, Y'),
                $leave->status,
                $processedBy,
            ];
        });
    }
}

