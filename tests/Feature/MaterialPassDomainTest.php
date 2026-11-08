<?php

namespace Tests\Feature;

use App\Enums\MaterialPassStatus;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ExternalOrganization;
use App\Models\MaterialPass;
use App\Models\MaterialPassApproval;
use App\Models\MaterialPassComment;
use App\Models\MaterialPassItem;
use App\Models\MaterialPassRoutePassage;
use App\Models\MaterialPassVehicle;
use App\Models\Person;
use App\Models\StaffPosition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialPassDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_directory_entities_relate_as_expected(): void
    {
        $person = Person::factory()->create([
            'last_name' => 'Хромов',
            'first_name' => 'Михаил',
            'middle_name' => 'Викторович',
        ]);
        $department = Department::factory()->create([
            'name' => 'Пробирно-аналитическая лаборатория',
        ]);
        $position = StaffPosition::factory()->create([
            'title' => 'Начальник лаборатории',
        ]);
        $employee = Employee::factory()->create([
            'person_id' => $person->id,
            'department_id' => $department->id,
            'staff_position_id' => $position->id,
        ]);

        $this->assertTrue($person->employees->contains($employee));
        $this->assertTrue($department->employees->contains($employee));
        $this->assertSame('Хромов Михаил Викторович, Начальник лаборатории', $employee->displayName());
        $this->assertNotNull(ExternalOrganization::factory()->create()->id);
    }

    public function test_material_pass_aggregate_persists_form_sections(): void
    {
        $pass = MaterialPass::factory()->create([
            'status' => MaterialPassStatus::Draft,
            'seal_numbers' => ['145003302', '145003305'],
        ]);

        MaterialPassVehicle::factory()->create(['material_pass_id' => $pass->id, 'sort_order' => 1]);
        MaterialPassItem::factory()->create(['material_pass_id' => $pass->id, 'sort_order' => 1]);
        MaterialPassApproval::factory()->create(['material_pass_id' => $pass->id, 'sort_order' => 1]);
        MaterialPassRoutePassage::factory()->create(['material_pass_id' => $pass->id, 'sort_order' => 1]);
        MaterialPassComment::factory()->create(['material_pass_id' => $pass->id]);

        $pass->refresh()->load([
            'vehicles',
            'items',
            'approvals',
            'routePassages',
            'comments',
            'initiatorEmployee.person',
            'fromDepartment',
            'issuedToEmployee',
        ]);

        $this->assertSame(MaterialPassStatus::Draft, $pass->status);
        $this->assertSame(['145003302', '145003305'], $pass->seal_numbers);
        $this->assertCount(1, $pass->vehicles);
        $this->assertCount(1, $pass->items);
        $this->assertCount(1, $pass->approvals);
        $this->assertCount(1, $pass->routePassages);
        $this->assertCount(1, $pass->comments);
        $this->assertNotNull($pass->initiatorEmployee);
        $this->assertNotNull($pass->fromDepartment);
        $this->assertStringContainsString('Материальный пропуск №', $pass->title());
    }
}
