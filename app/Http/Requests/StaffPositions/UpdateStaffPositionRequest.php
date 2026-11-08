<?php

namespace App\Http\Requests\StaffPositions;

use App\Http\Requests\Concerns\PreparesDirectoryInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffPositionRequest extends FormRequest
{
    use PreparesDirectoryInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareNullableFields(['code']);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $staffPositionId = $this->route('staff_position')?->id;

        return [
            'code' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('staff_positions', 'code')->ignore($staffPositionId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
