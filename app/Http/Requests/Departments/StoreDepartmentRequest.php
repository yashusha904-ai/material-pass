<?php

namespace App\Http\Requests\Departments;

use App\Http\Requests\Concerns\PreparesDirectoryInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDepartmentRequest extends FormRequest
{
    use PreparesDirectoryInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareNullableFields(['parent_id', 'code', 'organization_name']);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => ['nullable', 'integer', 'exists:departments,id'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('departments', 'code')],
            'name' => ['required', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
