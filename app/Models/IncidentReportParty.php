<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReportParty extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_report_id',
        'name',
        'role',
        'contact',
        'statement'
    ];

    public function report() {
        return $this->belongsTo(IncidentReport::class, 'incident_report_id');
    }
}
