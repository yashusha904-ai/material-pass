<?php

namespace App\Models;

use Database\Factories\ExternalOrganizationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Внешняя организация.
 */
#[Fillable([
    'name',
    'short_name',
    'inn',
    'kpp',
    'address',
    'is_active',
])]
class ExternalOrganization extends Model
{
    /** @use HasFactory<ExternalOrganizationFactory> */
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

    public function ownedMaterialPassItems(): HasMany
    {
        return $this->hasMany(MaterialPassItem::class, 'owner_external_organization_id');
    }
}
