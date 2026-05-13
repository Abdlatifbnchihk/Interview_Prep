<?php

namespace App\Http\Requests;

use App\Enums\ConceptDifficulty;
use App\Enums\ConceptStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConceptRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'domain_id'   => ['required', 'exists:domains,id'],
            'title'       => ['required', 'string', 'max:255'],
            'explanation' => ['required', 'string'],
            'difficulty'  => ['required', Rule::enum(ConceptDifficulty::class)],
            'status'      => ['required', Rule::enum(ConceptStatus::class)],
        ];
    }
}