<?php

namespace App\Models;

use Database\Factories\MaterialPassCommentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Комментарий к материальному пропуску.
 */
#[Fillable([
    'material_pass_id',
    'commented_at',
    'role',
    'body',
    'author_person_id',
    'author_user_id',
])]
class MaterialPassComment extends Model
{
    /** @use HasFactory<MaterialPassCommentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'commented_at' => 'datetime',
        ];
    }

    public function materialPass(): BelongsTo
    {
        return $this->belongsTo(MaterialPass::class);
    }

    public function authorPerson(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'author_person_id');
    }

    public function authorUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_user_id');
    }
}
