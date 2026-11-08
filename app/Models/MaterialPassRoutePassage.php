<?php

namespace App\Models;

use Database\Factories\MaterialPassRoutePassageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Прохождение КПП по маршруту.
 */
#[Fillable([
    'material_pass_id',
    'sort_order',
    'checkpoint',
    'passage_status',
    'passed_at',
    'security_officer_person_id',
    'security_officer_name',
])]
class MaterialPassRoutePassage extends Model
{
    /** @use HasFactory<MaterialPassRoutePassageFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'passed_at' => 'datetime',
        ];
    }

    public function materialPass(): BelongsTo
    {
        return $this->belongsTo(MaterialPass::class);
    }

    public function securityOfficerPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'security_officer_person_id');
    }
}
