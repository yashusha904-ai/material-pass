<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffPositions\StoreStaffPositionRequest;
use App\Http\Requests\StaffPositions\UpdateStaffPositionRequest;
use App\Models\StaffPosition;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StaffPositionController extends Controller
{
    public function index(): Response
    {
        $staffPositions = StaffPosition::query()
            ->orderBy('title')
            ->paginate(15)
            ->through(fn (StaffPosition $position) => [
                'id' => $position->id,
                'code' => $position->code,
                'title' => $position->title,
                'is_active' => $position->is_active,
            ]);

        return Inertia::render('StaffPositions/Index', [
            'staffPositions' => $staffPositions,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('StaffPositions/Create');
    }

    public function store(StoreStaffPositionRequest $request): RedirectResponse
    {
        StaffPosition::query()->create($request->validated());

        return redirect()
            ->route('staff-positions.index')
            ->with('status', 'Позиция штатного расписания создана.');
    }

    public function edit(StaffPosition $staffPosition): Response
    {
        return Inertia::render('StaffPositions/Edit', [
            'staffPosition' => [
                'id' => $staffPosition->id,
                'code' => $staffPosition->code,
                'title' => $staffPosition->title,
                'is_active' => $staffPosition->is_active,
            ],
        ]);
    }

    public function update(UpdateStaffPositionRequest $request, StaffPosition $staffPosition): RedirectResponse
    {
        $staffPosition->update($request->validated());

        return redirect()
            ->route('staff-positions.index')
            ->with('status', 'Позиция штатного расписания обновлена.');
    }

    public function destroy(StaffPosition $staffPosition): RedirectResponse
    {
        $staffPosition->delete();

        return redirect()
            ->route('staff-positions.index')
            ->with('status', 'Позиция штатного расписания удалена.');
    }
}
