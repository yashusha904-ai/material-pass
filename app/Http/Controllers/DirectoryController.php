<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class DirectoryController extends Controller
{
    /**
     * Directory hub (справочники).
     */
    public function __invoke(): Response
    {
        return Inertia::render('Directories/Index', [
            'directories' => [
                [
                    'name' => 'Физические лица',
                    'description' => 'Карточки физических лиц',
                    'route' => 'people.index',
                ],
                [
                    'name' => 'Сотрудники',
                    'description' => 'Связь физлица с подразделением и позицией ШР',
                    'route' => 'employees.index',
                ],
                [
                    'name' => 'Позиции штатного расписания',
                    'description' => 'Должности штатного расписания',
                    'route' => 'staff-positions.index',
                ],
                [
                    'name' => 'Подразделения',
                    'description' => 'Организационная структура',
                    'route' => 'departments.index',
                ],
                [
                    'name' => 'Внешние организации',
                    'description' => 'Контрагенты (ИНН/КПП/адрес)',
                    'route' => 'external-organizations.index',
                ],
            ],
        ]);
    }
}
