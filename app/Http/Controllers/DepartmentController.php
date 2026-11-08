<?php

namespace App\Http\Controllers;

use App\Http\Requests\Departments\StoreDepartmentRequest;
use App\Http\Requests\Departments\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    public function index(): Response
    {
        $departments = Department::query()
            ->with('parent:id,name')
            ->orderBy('name')
            ->paginate(15)
            ->through(fn (Department $department) => [
                'id' => $department->id,
                'code' => $department->code,
                'name' => $department->name,
                'organization_name' => $department->organization_name,
                'parent_name' => $department->parent?->name,
                'is_active' => $department->is_active,
            ]);

        return Inertia::render('Departments/Index', [
            'departments' => $departments,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Departments/Create', [
            'parents' => $this->parentOptions(),
        ]);
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::query()->create($request->validated());

        return redirect()
            ->route('departments.index')
            ->with('status', 'Подразделение создано.');
    }

    public function edit(Department $department): Response
    {
        return Inertia::render('Departments/Edit', [
            'department' => [
                'id' => $department->id,
                'parent_id' => $department->parent_id,
                'code' => $department->code,
                'name' => $department->name,
                'organization_name' => $department->organization_name,
                'is_active' => $department->is_active,
            ],
            'parents' => $this->parentOptions($department->id),
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());

        return redirect()
            ->route('departments.index')
            ->with('status', 'Подразделение обновлено.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('status', 'Подразделение удалено.');
    }

    /**
     * @return list<array{id: int, label: string}>
     */
    private function parentOptions(?int $excludeId = null): array
    {
        return Department::query()
            ->when($excludeId !== null, fn ($query) => $query->whereKeyNot($excludeId))
            ->orderBy('name')
            ->get(['id', 'name', 'code'])
            ->map(fn (Department $department) => [
                'id' => $department->id,
                'label' => $department->code
                    ? $department->name.' ('.$department->code.')'
                    : $department->name,
            ])
            ->values()
            ->all();
    }
}
