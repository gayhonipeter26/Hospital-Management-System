<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visit extends Model
{
    use HasFactory;

  protected $fillable = [
    'patient_id',
    'start_date',
    'status',
    'patient_type',
    'scheduled',
    'notes',
    'department',
    'practitioner',
    'practitioner_name',
];


    // ✅ Relationship: visit belongs to one patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
