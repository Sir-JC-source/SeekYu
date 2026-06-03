<?php

namespace App\Http\Controllers\Leave;

use App\Exports\LeavesPendingExport;
use App\Exports\LeavesProcessedExport;
use App\Http\Controllers\Controller;
class LeaveExportController extends Controller
{
    private function exportAsCsv($rows, array $headings, string $filename)
    {
        $callback = function () use ($rows, $headings) {
            $out = fopen('php://output', 'w');
            // UTF-8 BOM helps Excel open UTF-8 correctly
            fputs($out, "\xEF\xBB\xBF");
            fputcsv($out, $headings);

            foreach ($rows as $row) {
                $fputRow = [];
                foreach ($headings as $key) {
                    $fputRow[] = $row[$key] ?? '';
                }
                fputcsv($out, $fputRow);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function exportPendingExcel()
    {
        // Fallback: CSV (Excel can open it). We keep the same button/route name.
        $export = new LeavesPendingExport();
        $headings = $export->headings();
        $rows = $export->collection();

        $mappedRows = $rows->map(function ($row, $i) {
            return [
                '#'=> $row[0],
                'Requestor'=> $row[1],
                'Position'=> $row[2],
                'Type'=> $row[3],
                'Reason'=> $row[4],
                'Duration'=> $row[5],
                'Date Requested'=> $row[6],
            ];
        })->values()->all();

        return $this->exportAsCsv(
            $mappedRows,
            ['#','Requestor','Position','Type','Reason','Duration','Date Requested'],
            'pending-leaves.csv'
        );
    }

    public function exportProcessedExcel()
    {
        $export = new LeavesProcessedExport();
        $headings = $export->headings();
        $rows = $export->collection();

        $mappedRows = $rows->map(function ($row, $i) {
            return [
                '#'=> $row[0],
                'Requestor'=> $row[1],
                'Position'=> $row[2],
                'Type of Leave'=> $row[3],
                'Reason'=> $row[4],
                'Duration'=> $row[5],
                'Date Requested'=> $row[6],
                'Status'=> $row[7],
                'Processed By'=> $row[8],
            ];
        })->values()->all();

        return $this->exportAsCsv(
            $mappedRows,
            ['#','Requestor','Position','Type of Leave','Reason','Duration','Date Requested','Status','Processed By'],
            'processed-leaves.csv'
        );
    }
}



