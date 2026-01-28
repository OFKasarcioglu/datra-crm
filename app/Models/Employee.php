<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        /* =========
           KİMLİK
        ========= */
        'sicil_no',
        'first_name',
        'last_name',
        'tc_no',
        'birth_date',
        'gender',
        'marital_status',
        'photo',

        /* =========
           İŞ
        ========= */
        'department_id',
        'position_id',
        'hire_date',
        'exit_date',
        'status',
        'employment_type',
        'employee_type',

        /* =========
           İLETİŞİM
        ========= */
        'phone',
        'email',
        'address',
        'city',
        'district',
        'emergency_contact',
        'emergency_phone',

        /* =========
           SGK / SAĞLIK
        ========= */
        'sgk_no',
        'sgk_start',
        'sgk_end',
        'blood_type',
        'disability_rate',
    ];

    protected $casts = [
        'birth_date'   => 'date',
        'hire_date'    => 'date',
        'exit_date'    => 'date',
        'sgk_start'    => 'date',
        'sgk_end'      => 'date',
        'status'       => 'boolean',
    ];

    /* =======================
       RELATIONSHIPS
    ======================= */

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /* =======================
       ACCESSORS
    ======================= */

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /* =======================
       SCOPES
    ======================= */

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopePassive($query)
    {
        return $query->where('status', false);
    }
}
