<?php

namespace App\Models;

use Database\Factories\MaterialPassVehicleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Данные ТС в материальном пропуске.
 */
#[Fillable([
    'material_pass_id',
    'sort_order',
    'booking_request',
    'vehicle_type',
    'plate_number',
    'driver_person_id',
    'driver_name',
    'comment',
])]
class MaterialPassVehicle extends Model
{
    /** @use HasFactory<MaterialPassVehicleFactory> */
    use HasFactory;

    public function materialPass(): BelongsTo
    {
        return $this->belongsTo(MaterialPass::class);
    }

    public function driverPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'driver_person_id');
    }
}
