<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_text' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function attributes(): array
    {
        return [
            'color_text' => 'color',
        ];
    }

    protected function prepareForValidation(): void
    {
        $color = $this->input('color_text') ?: $this->input('color');
        $this->merge(['color' => $color]);
    }

    public function validated($key = null, $default = null): array
    {
        $validated = parent::validated($key, $default);
        if (!isset($validated['color']) || $validated['color'] === '') {
            $validated['color'] = '#6366F1';
        }
        return $validated;
    }
}