<?php

namespace Database\Factories;

use App\Enums\MaterialPassApprovalStatus;
use App\Models\MaterialPass;
use App\Models\MaterialPassApproval;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaterialPassApproval>
 */
class MaterialPassApprovalFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'material_pass_id' => MaterialPass::factory(),
            'sort_order' => 0,
            'version' => 1,
            'stage_name' => fake()->randomElement([
                'Согласование руководителем',
                'Согласование СБ',
                'Подписание',
            ]),
            'participants' => [fake()->name()],
            'status' => MaterialPassApprovalStatus::Pending,
            'arrived_at' => now(),
            'result' => null,
            'resolved_at' => null,
            'resolver_employee_id' => null,
            'resolver_name_title' => null,
            'comment' => null,
        ];
    }
}
