<?php

namespace App\Models;

use Database\Factories\StaffPositionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Позиция штатного расписания.
 */
#[Fillable([
    'code',
    'title',
    'is_active',
])]
class StaffPosition extends Model
{
    /** @use HasFactory<StaffPositionFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
