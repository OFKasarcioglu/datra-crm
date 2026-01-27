<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleMaintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'maintenance_type_id',
        'maintenance_date',
        'km',
        'next_maintenance_date',
        'next_km',
        'cost',
        'service_name',
        'description',
        'status',
    ];

    protected $casts = [
        'maintenance_date'       => 'date',
        'next_maintenance_date'  => 'date',
        'cost'                   => 'decimal:2',
        'km'                     => 'integer',
        'next_km'                => 'integer',
    ];

    /**
     * Bakım kaydı -> Araç
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Bakım kaydı -> Bakım türü
     */
    public function maintenanceType()
    {
        return $this->belongsTo(MaintenanceType::class);
    }
}