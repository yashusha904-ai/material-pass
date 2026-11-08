<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employees\StoreEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Person;
use App\Models\StaffPosition;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EmployeeController extends Controller
{
    public function index(): Response
    {
        $employees = Employee::query()
            ->with([
                'person:id,last_name,first_name,middle_name',
                'department:id,name',
                'staffPosition:id,title',
            ])
            ->latest('id')
            ->paginate(15)
            ->through(fn (Employee $employee) => [
                'id' => $employee->id,
                'person_name' => $employee->person?->fullName(),
                'department_name' => $employee->department?->name,
                'staff_position_title' => $employee->staffPosition?->title,
                'personnel_number' => $employee->personnel_number,
                'hired_on' => $employee->hired_on?->format('Y-m-d'),
                'fired_on' => $employee->fired_on?->format('Y-m-d'),
                'is_active' => $employee->is_active,
            ]);

        return Inertia::render('Employees/Index', [
            'employees' => $employees,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Employees/Create', $this->formOptions());
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        Employee::query()->create($request->validated());

        return redirect()
            ->route('employees.index')
            ->with('status', 'Сотрудник создан.');
    }

    public function edit(Employee $employee): Response
    {
        return Inertia::render('Employees/Edit', [
            'employee' => [
                'id' => $employee->id,
                'person_id' => $employee->person_id,
                'department_id' => $employee->department_id,
                'staff_position_id' => $employee->staff_position_id,
                'personnel_number' => $employee->personnel_number,
                'hired_on' => $employee->hired_on?->format('Y-m-d'),
                'fired_on' => $employee->fired_on?->format('Y-m-d'),
                'is_active' => $employee->is_active,
            ],
            ...$this->formOptions(),
        ]);
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()
            ->route('employees.index')
            ->with('status', 'Сотрудник обновлён.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()
            ->route('employees.index')
            ->with('status', 'Сотрудник удалён.');
    }

    /**
     * @return array{
     *     people: list<array{id: int, label: string}>,
     *     departments: list<array{id: int, label: string}>,
     *     staffPositions: list<array{id: int, label: string}>
     * }
     */
    private function formOptions(): array
    {
        return [
            'people' => Person::query()
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->get()
                ->map(fn (Person $person) => [
                    'id' => $person->id,
                    'label' => $person->fullName(),
                ])
                ->values()
                ->all(),
            'departments' => Department::query()
                ->orderBy('name')
                ->get(['id', 'name', 'code'])
                ->map(fn (Department $department) => [
                    'id' => $department->id,
                    'label' => $department->code
                        ? $department->name.' ('.$department->code.')'
                        : $department->name,
                ])
                ->values()
                ->all(),
            'staffPositions' => StaffPosition::query()
                ->orderBy('title')
                ->get(['id', 'title', 'code'])
                ->map(fn (StaffPosition $position) => [
                    'id' => $position->id,
                    'label' => $position->code
                        ? $position->title.' ('.$position->code.')'
                        : $position->title,
                ])
                ->values()
                ->all(),
        ];
    }
}
