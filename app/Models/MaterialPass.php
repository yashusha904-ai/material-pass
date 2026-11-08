<?php

namespace App\Models;

use App\Enums\MaterialPassStatus;
use Database\Factories\MaterialPassFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Материальный пропуск.
 */
#[Fillable([
    'number',
    'document_date',
    'status',
    'qr_token',
    'initiator_employee_id',
    'initiator_department_id',
    'initiator_organization_name',
    'from_department_id',
    'to_department_id',
    'to_location_name',
    'valid_until',
    'seal_numbers',
    'issued_to_employee_id',
    'issued_to_department_id',
    'issued_to_organization_name',
    'issued_to_external_organization_id',
    'created_by_user_id',
])]
class MaterialPass extends Model
{
    /** @use HasFactory<MaterialPassFactory> */
    use HasFactory;

    /**
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'draft',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'document_date' => 'date',
            'valid_until' => 'datetime',
            'seal_numbers' => 'array',
            'status' => MaterialPassStatus::class,
        ];
    }

    public function initiatorEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'initiator_employee_id');
    }

    public function initiatorDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'initiator_department_id');
    }

    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    public function issuedToEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issued_to_employee_id');
    }

    public function issuedToDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'issued_to_department_id');
    }

    public function issuedToExternalOrganization(): BelongsTo
    {
        return $this->belongsTo(ExternalOrganization::class, 'issued_to_external_organization_id');
    }

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(MaterialPassVehicle::class)->orderBy('sort_order');
    }

    public function items(): HasMany
    {
        return $this->hasMany(MaterialPassItem::class)->orderBy('sort_order');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(MaterialPassApproval::class)->orderBy('sort_order');
    }

    public function routePassages(): HasMany
    {
        return $this->hasMany(MaterialPassRoutePassage::class)->orderBy('sort_order');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MaterialPassComment::class)->orderBy('commented_at');
    }

    public function title(): string
    {
        $date = $this->document_date?->format('d.m.Y') ?? '';

        return trim("Материальный пропуск №{$this->number} от {$date}");
    }
}
