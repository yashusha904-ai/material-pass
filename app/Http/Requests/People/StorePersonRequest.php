<?php

namespace App\Http\Requests\People;

use App\Http\Requests\Concerns\PreparesDirectoryInput;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePersonRequest extends FormRequest
{
    use PreparesDirectoryInput;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareNullableFields(['user_id', 'middle_name', 'email', 'phone']);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique('people', 'email')],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
