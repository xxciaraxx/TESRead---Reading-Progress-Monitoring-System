<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'grade_level'=> ['required', 'string', 'max:50'],
            'section_id' => ['required', 'exists:sections,id'],
            'reading_level_id' => ['nullable', 'exists:reading_levels,id'],
            'status'     => ['sometimes', 'in:active,archived'],
        ];
    }
}
