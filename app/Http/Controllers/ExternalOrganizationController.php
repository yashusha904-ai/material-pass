<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExternalOrganizations\StoreExternalOrganizationRequest;
use App\Http\Requests\ExternalOrganizations\UpdateExternalOrganizationRequest;
use App\Models\ExternalOrganization;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ExternalOrganizationController extends Controller
{
    public function index(): Response
    {
        $organizations = ExternalOrganization::query()
            ->orderBy('name')
            ->paginate(15)
            ->through(fn (ExternalOrganization $organization) => [
                'id' => $organization->id,
                'name' => $organization->name,
                'short_name' => $organization->short_name,
                'inn' => $organization->inn,
                'kpp' => $organization->kpp,
                'address' => $organization->address,
                'is_active' => $organization->is_active,
            ]);

        return Inertia::render('ExternalOrganizations/Index', [
            'organizations' => $organizations,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('ExternalOrganizations/Create');
    }

    public function store(StoreExternalOrganizationRequest $request): RedirectResponse
    {
        ExternalOrganization::query()->create($request->validated());

        return redirect()
            ->route('external-organizations.index')
            ->with('status', 'Внешняя организация создана.');
    }

    public function edit(ExternalOrganization $externalOrganization): Response
    {
        return Inertia::render('ExternalOrganizations/Edit', [
            'organization' => [
                'id' => $externalOrganization->id,
                'name' => $externalOrganization->name,
                'short_name' => $externalOrganization->short_name,
                'inn' => $externalOrganization->inn,
                'kpp' => $externalOrganization->kpp,
                'address' => $externalOrganization->address,
                'is_active' => $externalOrganization->is_active,
            ],
        ]);
    }

    public function update(
        UpdateExternalOrganizationRequest $request,
        ExternalOrganization $externalOrganization,
    ): RedirectResponse {
        $externalOrganization->update($request->validated());

        return redirect()
            ->route('external-organizations.index')
            ->with('status', 'Внешняя организация обновлена.');
    }

    public function destroy(ExternalOrganization $externalOrganization): RedirectResponse
    {
        $externalOrganization->delete();

        return redirect()
            ->route('external-organizations.index')
            ->with('status', 'Внешняя организация удалена.');
    }
}
