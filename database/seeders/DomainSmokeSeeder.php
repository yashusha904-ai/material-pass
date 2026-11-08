<?php

namespace Database\Seeders;

use App\Enums\MaterialPassApprovalStatus;
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
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DomainSmokeSeeder extends Seeder
{
    /**
     * Seed a readable material-pass graph matching the form screenshot.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        $orgName = "АО 'Полюс Красноярск'";

        $lab = Department::query()->firstOrCreate(
            ['code' => 'PAL'],
            [
                'name' => 'Пробирно-аналитическая лаборатория',
                'organization_name' => $orgName,
            ],
        );

        $hydro = Department::query()->firstOrCreate(
            ['code' => 'GMU'],
            [
                'name' => 'Гидрометаллургический участок',
                'organization_name' => $orgName,
            ],
        );

        $balance = Department::query()->firstOrCreate(
            ['code' => 'BCB'],
            [
                'name' => "БЦ 'Баланс'",
                'organization_name' => $orgName,
            ],
        );

        $labHead = StaffPosition::query()->firstOrCreate(
            ['code' => 'LAB-HEAD'],
            ['title' => 'Начальник лаборатории'],
        );

        $engineer = StaffPosition::query()->firstOrCreate(
            ['code' => 'ENG'],
            ['title' => 'Инженер'],
        );

        $driverPosition = StaffPosition::query()->firstOrCreate(
            ['code' => 'DRV'],
            ['title' => 'Водитель'],
        );

        $initiatorPerson = Person::query()->firstOrCreate(
            ['last_name' => 'Хромов', 'first_name' => 'Михаил', 'middle_name' => 'Викторович'],
            [
                'user_id' => $user->id,
                'email' => 'khromov@example.com',
                'is_active' => true,
            ],
        );

        $recipientPerson = Person::query()->firstOrCreate(
            ['last_name' => 'Клементенок', 'first_name' => 'Алексей', 'middle_name' => 'Юрьевич'],
            ['is_active' => true],
        );

        $driverPerson = Person::query()->firstOrCreate(
            ['last_name' => 'Иванов', 'first_name' => 'Сергей', 'middle_name' => 'Петрович'],
            ['is_active' => true],
        );

        $securityPerson = Person::query()->firstOrCreate(
            ['last_name' => 'Сидоров', 'first_name' => 'Игорь', 'middle_name' => 'Николаевич'],
            ['is_active' => true],
        );

        $initiator = Employee::query()->firstOrCreate(
            [
                'person_id' => $initiatorPerson->id,
                'department_id' => $lab->id,
                'staff_position_id' => $labHead->id,
            ],
            ['personnel_number' => 'EMP-1001', 'is_active' => true],
        );

        $recipient = Employee::query()->firstOrCreate(
            [
                'person_id' => $recipientPerson->id,
                'department_id' => $hydro->id,
                'staff_position_id' => $engineer->id,
            ],
            ['personnel_number' => 'EMP-1002', 'is_active' => true],
        );

        Employee::query()->firstOrCreate(
            [
                'person_id' => $driverPerson->id,
                'department_id' => $hydro->id,
                'staff_position_id' => $driverPosition->id,
            ],
            ['personnel_number' => 'EMP-1003', 'is_active' => true],
        );

        $contractor = ExternalOrganization::query()->firstOrCreate(
            ['inn' => '2460000000'],
            [
                'name' => 'ООО «СеверТранс»',
                'short_name' => 'СеверТранс',
                'kpp' => '246001001',
                'address' => 'г. Красноярск',
                'is_active' => true,
            ],
        );

        $pass = MaterialPass::query()->updateOrCreate(
            [
                'number' => 1,
                'document_date' => '2026-03-01',
            ],
            [
                'status' => MaterialPassStatus::PendingApproval,
                'qr_token' => (string) Str::uuid(),
                'initiator_employee_id' => $initiator->id,
                'initiator_department_id' => $lab->id,
                'initiator_organization_name' => $orgName,
                'from_department_id' => $lab->id,
                'to_department_id' => $balance->id,
                'valid_until' => '2026-05-22 22:10:00',
                'seal_numbers' => ['145003302', '145003305', '145003307'],
                'issued_to_employee_id' => $recipient->id,
                'issued_to_department_id' => $hydro->id,
                'issued_to_organization_name' => $orgName,
                'created_by_user_id' => $user->id,
            ],
        );

        MaterialPassVehicle::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'plate_number' => 'А123ВС24',
            ],
            [
                'sort_order' => 1,
                'booking_request' => 'BR-2026-014',
                'vehicle_type' => 'Грузовой',
                'driver_person_id' => $driverPerson->id,
                'comment' => 'Погрузка у лаборатории',
            ],
        );

        MaterialPassItem::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'name' => 'Пробы руды',
            ],
            [
                'sort_order' => 1,
                'category' => 'Пробы',
                'owner_organization_name' => $orgName,
                'quantity' => 12,
                'packaging_type' => 'Контейнер',
                'total_weight' => 48.5,
                'unit' => 'кг',
                'dump_number' => null,
            ],
        );

        MaterialPassItem::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'name' => 'Тара возвратная',
            ],
            [
                'sort_order' => 2,
                'category' => 'Прочее',
                'owner_external_organization_id' => $contractor->id,
                'owner_organization_name' => $contractor->name,
                'quantity' => 4,
                'packaging_type' => 'Ящик',
                'total_weight' => 20,
                'unit' => 'шт',
                'dump_number' => 'ОТВ-03',
            ],
        );

        MaterialPassApproval::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'stage_name' => 'Согласование руководителем',
                'version' => 1,
            ],
            [
                'sort_order' => 1,
                'participants' => [$initiator->displayName()],
                'status' => MaterialPassApprovalStatus::Approved,
                'arrived_at' => '2026-03-01 09:00:00',
                'result' => 'Согласовано',
                'resolved_at' => '2026-03-01 09:15:00',
                'resolver_employee_id' => $initiator->id,
                'resolver_name_title' => $initiator->displayName(),
                'comment' => null,
            ],
        );

        MaterialPassApproval::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'stage_name' => 'Согласование СБ',
                'version' => 1,
            ],
            [
                'sort_order' => 2,
                'participants' => ['Служба безопасности'],
                'status' => MaterialPassApprovalStatus::Pending,
                'arrived_at' => '2026-03-01 09:16:00',
                'result' => null,
                'resolved_at' => null,
            ],
        );

        MaterialPassRoutePassage::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'checkpoint' => 'КПП-1',
            ],
            [
                'sort_order' => 1,
                'passage_status' => 'ожидает',
                'passed_at' => null,
                'security_officer_person_id' => $securityPerson->id,
            ],
        );

        MaterialPassComment::query()->updateOrCreate(
            [
                'material_pass_id' => $pass->id,
                'body' => 'Черновик подготовлен к согласованию.',
            ],
            [
                'commented_at' => '2026-03-01 08:55:00',
                'role' => 'Инициатор',
                'author_person_id' => $initiatorPerson->id,
                'author_user_id' => $user->id,
            ],
        );
    }
}
