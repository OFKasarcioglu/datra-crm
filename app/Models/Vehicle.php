<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate',
        'brand',
        'model',
        'year',
        'current_km',
        'is_active',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'year'       => 'integer',
        'current_km' => 'integer',
    ];

    /**
     * Araç -> Bakım kayıtları
     */
    public function maintenances()
    {
        return $this->hasMany(VehicleMaintenance::class);
    }
}