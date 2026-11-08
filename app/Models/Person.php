<?php

namespace App\Models;

use Database\Factories\PersonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Физическое лицо.
 */
#[Fillable([
    'user_id',
    'last_name',
    'first_name',
    'middle_name',
    'email',
    'phone',
    'is_active',
])]
class Person extends Model
{
    /** @use HasFactory<PersonFactory> */
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function fullName(): string
    {
        return collect([$this->last_name, $this->first_name, $this->middle_name])
            ->filter()
            ->implode(' ');
    }

    public function shortName(): string
    {
        $initials = collect([$this->first_name, $this->middle_name])
            ->filter()
            ->map(fn (string $part): string => mb_substr($part, 0, 1).'.')
            ->implode('');

        return trim($this->last_name.' '.$initials);
    }
}
