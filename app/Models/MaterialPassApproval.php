<?php

namespace App\Models;

use App\Enums\MaterialPassApprovalStatus;
use Database\Factories\MaterialPassApprovalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Этап согласования материального пропуска.
 */
#[Fillable([
    'material_pass_id',
    'sort_order',
    'version',
    'stage_name',
    'participants',
    'status',
    'arrived_at',
    'result',
    'resolved_at',
    'resolver_employee_id',
    'resolver_name_title',
    'comment',
])]
class MaterialPassApproval extends Model
{
    /** @use HasFactory<MaterialPassApprovalFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'participants' => 'array',
            'arrived_at' => 'datetime',
            'resolved_at' => 'datetime',
            'status' => MaterialPassApprovalStatus::class,
        ];
    }

    public function materialPass(): BelongsTo
    {
        return $this->belongsTo(MaterialPass::class);
    }

    public function resolverEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'resolver_employee_id');
    }
}
