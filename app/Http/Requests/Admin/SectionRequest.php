<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'grade_level'  => ['required', 'string', 'max:50'],
            'section_name' => ['required', 'string', 'max:100'],
            'teacher_id'   => ['nullable', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'grade_level'  => 'grade level',
            'section_name' => 'class name',
            'teacher_id'   => 'assigned teacher',
        ];
    }

    public function messages(): array
    {
        return [
            'grade_level.required'  => 'Please select a grade level.',
            'section_name.required' => 'Please enter a class name.',
        ];
    }
}