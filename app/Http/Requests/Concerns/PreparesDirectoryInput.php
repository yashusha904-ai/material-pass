<?php

namespace App\Http\Requests\Concerns;

trait PreparesDirectoryInput
{
    /**
     * @param  list<string>  $nullableFields
     */
    protected function prepareNullableFields(array $nullableFields): void
    {
        $payload = [];

        foreach ($nullableFields as $field) {
            if (! $this->exists($field)) {
                continue;
            }

            $value = $this->input($field);
            $payload[$field] = $value === '' || $value === null ? null : $value;
        }

        if ($this->exists('is_active')) {
            $payload['is_active'] = $this->boolean('is_active');
        } elseif (! $this->has('is_active')) {
            $payload['is_active'] = false;
        }

        $this->merge($payload);
    }
}
