<?php

namespace App\Http\Controllers;

use App\Http\Requests\People\StorePersonRequest;
use App\Http\Requests\People\UpdatePersonRequest;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PersonController extends Controller
{
    public function index(): Response
    {
        $people = Person::query()
            ->with('user:id,name,email')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(15)
            ->through(fn (Person $person) => [
                'id' => $person->id,
                'full_name' => $person->fullName(),
                'last_name' => $person->last_name,
                'first_name' => $person->first_name,
                'middle_name' => $person->middle_name,
                'email' => $person->email,
                'phone' => $person->phone,
                'is_active' => $person->is_active,
                'user_name' => $person->user?->name,
            ]);

        return Inertia::render('People/Index', [
            'people' => $people,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('People/Create', [
            'users' => $this->userOptions(),
        ]);
    }

    public function store(StorePersonRequest $request): RedirectResponse
    {
        Person::query()->create($request->validated());

        return redirect()
            ->route('people.index')
            ->with('status', 'Физическое лицо создано.');
    }

    public function edit(Person $person): Response
    {
        return Inertia::render('People/Edit', [
            'person' => [
                'id' => $person->id,
                'user_id' => $person->user_id,
                'last_name' => $person->last_name,
                'first_name' => $person->first_name,
                'middle_name' => $person->middle_name,
                'email' => $person->email,
                'phone' => $person->phone,
                'is_active' => $person->is_active,
            ],
            'users' => $this->userOptions(),
        ]);
    }

    public function update(UpdatePersonRequest $request, Person $person): RedirectResponse
    {
        $person->update($request->validated());

        return redirect()
            ->route('people.index')
            ->with('status', 'Физическое лицо обновлено.');
    }

    public function destroy(Person $person): RedirectResponse
    {
        $person->delete();

        return redirect()
            ->route('people.index')
            ->with('status', 'Физическое лицо удалено.');
    }

    /**
     * @return list<array{id: int, label: string}>
     */
    private function userOptions(): array
    {
        return User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email'])
            ->map(fn (User $user) => [
                'id' => $user->id,
                'label' => $user->name.' ('.$user->email.')',
            ])
            ->values()
            ->all();
    }
}
