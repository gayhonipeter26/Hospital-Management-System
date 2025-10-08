<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_code',
        'phone',
        'email',
        'gender',
        'dob',
        'blood_type',
        'marital_status',
        'id_number',
        'nationality',
        'residence',
        'city',
        'country',
        'occupation',
        'religion',
        'notes',
    ];

    // Append virtual attributes automatically
    protected $appends = ['full_phone', 'full_name'];

    // Cast dates
    protected $dates = ['deleted_at', 'dob'];

    /**
     * Relationship: A patient has many visits
     */
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Accessor: Full phone number
     */
    public function getFullPhoneAttribute()
    {
        return "{$this->phone_code}{$this->phone}";
    }

    /**
     * Accessor: Full name (first + last)
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
