<?php

namespace App\Http\Requests\Employees;

use App\Http\Requests\Concerns\PreparesDirectoryInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    use PreparesDirectoryInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareNullableFields([
            'department_id',
            'staff_position_id',
            'personnel_number',
            'hired_on',
            'fired_on',
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'person_id' => ['required', 'integer', 'exists:people,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'staff_position_id' => ['nullable', 'integer', 'exists:staff_positions,id'],
            'personnel_number' => ['nullable', 'string', 'max:50', Rule::unique('employees', 'personnel_number')],
            'hired_on' => ['nullable', 'date'],
            'fired_on' => ['nullable', 'date', 'after_or_equal:hired_on'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
