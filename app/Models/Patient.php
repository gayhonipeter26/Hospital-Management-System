<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes; // ✅ Enable soft deletes

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

    // ✅ Automatically append these attributes
    protected $appends = ['full_phone'];

    // ✅ Cast deleted_at as datetime
    protected $dates = ['deleted_at'];

    // ✅ Virtual attribute: full_phone
    public function getFullPhoneAttribute()
    {
        return "{$this->phone_code}{$this->phone}";
    }
}
