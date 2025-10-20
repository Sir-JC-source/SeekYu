<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'incident_name', 
        'date_of_incident', 
        'location', 
        'specific_area', 
        'incident_description'
    ];

    public function parties() {
        return $this->hasMany(IncidentReportParty::class);
    }
}
