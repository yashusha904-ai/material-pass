<?php

namespace App\Http\Controllers;

use App\Models\MaterialPass;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MaterialPassController extends Controller
{
    /**
     * Show a material pass form scaffold (read-only / draft view).
     */
    public function show(Request $request, MaterialPass $materialPass): Response
    {
        $materialPass->load([
            'initiatorEmployee.person',
            'initiatorEmployee.staffPosition',
            'initiatorDepartment',
            'fromDepartment',
            'toDepartment',
            'issuedToEmployee.person',
            'issuedToEmployee.staffPosition',
            'issuedToDepartment',
            'issuedToExternalOrganization',
            'vehicles.driverPerson',
            'items.ownerExternalOrganization',
            'approvals.resolverEmployee.person',
            'approvals.resolverEmployee.staffPosition',
            'routePassages.securityOfficerPerson',
            'comments.authorPerson',
        ]);

        return Inertia::render('MaterialPasses/Show', [
            'materialPass' => [
                'id' => $materialPass->id,
                'title' => $materialPass->title(),
                'number' => $materialPass->number,
                'document_date' => $materialPass->document_date?->format('d.m.Y'),
                'status' => $materialPass->status?->value,
                'qr_token' => $materialPass->qr_token,
                'initiator' => [
                    'name' => $materialPass->initiatorEmployee?->displayName(),
                    'department' => $materialPass->initiatorDepartment?->name,
                    'organization' => $materialPass->initiator_organization_name,
                ],
                'from' => $materialPass->fromDepartment?->name,
                'to' => $materialPass->toDepartment?->name ?? $materialPass->to_location_name,
                'valid_until' => $materialPass->valid_until?->format('d.m.Y H:i'),
                'seal_numbers' => implode(', ', $materialPass->seal_numbers ?? []),
                'issued_to' => [
                    'department' => $materialPass->issuedToDepartment?->name,
                    'organization' => $materialPass->issued_to_organization_name
                        ?? $materialPass->issuedToExternalOrganization?->name,
                    'person' => $materialPass->issuedToEmployee?->displayName(),
                ],
                'vehicles' => $materialPass->vehicles->map(fn ($row) => [
                    'booking_request' => $row->booking_request,
                    'vehicle_type' => $row->vehicle_type,
                    'plate_number' => $row->plate_number,
                    'driver' => $row->driverPerson?->fullName() ?? $row->driver_name,
                    'comment' => $row->comment,
                ])->values(),
                'items' => $materialPass->items->map(fn ($row) => [
                    'category' => $row->category,
                    'name' => $row->name,
                    'owner_organization' => $row->owner_organization_name
                        ?? $row->ownerExternalOrganization?->name,
                    'quantity' => $row->quantity,
                    'packaging_type' => $row->packaging_type,
                    'total_weight' => $row->total_weight,
                    'unit' => $row->unit,
                    'dump_number' => $row->dump_number,
                ])->values(),
                'approvals' => $materialPass->approvals->map(fn ($row, int $index) => [
                    'index' => $index + 1,
                    'version' => $row->version,
                    'stage_name' => $row->stage_name,
                    'participants' => implode(', ', $row->participants ?? []),
                    'status' => $row->status?->value,
                    'arrived_at' => $row->arrived_at?->format('d.m.Y H:i'),
                    'result' => $row->result,
                    'resolved_at' => $row->resolved_at?->format('d.m.Y H:i'),
                    'resolver_name_title' => $row->resolver_name_title
                        ?? $row->resolverEmployee?->displayName(),
                    'comment' => $row->comment,
                ])->values(),
                'route_passages' => $materialPass->routePassages->map(fn ($row, int $index) => [
                    'index' => $index + 1,
                    'checkpoint' => $row->checkpoint,
                    'passage_status' => $row->passage_status,
                    'passed_at' => $row->passed_at?->format('d.m.Y H:i'),
                    'security_officer' => $row->securityOfficerPerson?->fullName()
                        ?? $row->security_officer_name,
                ])->values(),
                'comments' => $materialPass->comments->map(fn ($row, int $index) => [
                    'index' => $index + 1,
                    'date' => $row->commented_at?->format('d.m.Y H:i'),
                    'role' => $row->role,
                    'body' => $row->body,
                ])->values(),
            ],
        ]);
    }

    /**
     * Empty draft scaffold when no pass id is selected.
     */
    public function create(): Response
    {
        return Inertia::render('MaterialPasses/Show', [
            'materialPass' => [
                'id' => null,
                'title' => 'Материальный пропуск (черновик)',
                'number' => null,
                'document_date' => null,
                'status' => 'draft',
                'qr_token' => null,
                'initiator' => [
                    'name' => null,
                    'department' => null,
                    'organization' => null,
                ],
                'from' => null,
                'to' => null,
                'valid_until' => null,
                'seal_numbers' => '',
                'issued_to' => [
                    'department' => null,
                    'organization' => null,
                    'person' => null,
                ],
                'vehicles' => [],
                'items' => [],
                'approvals' => [],
                'route_passages' => [],
                'comments' => [],
            ],
        ]);
    }
}
