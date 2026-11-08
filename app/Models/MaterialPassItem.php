<?php

namespace App\Models;

use Database\Factories\MaterialPassItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Строка перечня ТМЦ.
 */
#[Fillable([
    'material_pass_id',
    'sort_order',
    'category',
    'name',
    'owner_external_organization_id',
    'owner_organization_name',
    'quantity',
    'packaging_type',
    'total_weight',
    'unit',
    'dump_number',
])]
class MaterialPassItem extends Model
{
    /** @use HasFactory<MaterialPassItemFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'total_weight' => 'decimal:3',
        ];
    }

    public function materialPass(): BelongsTo
    {
        return $this->belongsTo(MaterialPass::class);
    }

    public function ownerExternalOrganization(): BelongsTo
    {
        return $this->belongsTo(ExternalOrganization::class, 'owner_external_organization_id');
    }
}
