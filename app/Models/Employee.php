<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Сотрудник (привязка физлица к подразделению и позиции ШР).
 */
#[Fillable([
    'person_id',
    'department_id',
    'staff_position_id',
    'personnel_number',
    'hired_on',
    'fired_on',
    'is_active',
])]
class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'hired_on' => 'date',
            'fired_on' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function staffPosition(): BelongsTo
    {
        return $this->belongsTo(StaffPosition::class);
    }

    public function displayName(): string
    {
        $name = $this->person?->fullName() ?? '';
        $title = $this->staffPosition?->title;

        return $title ? trim($name.', '.$title) : $name;
    }
}
