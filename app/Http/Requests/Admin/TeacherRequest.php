<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class TeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        $teacherId = $this->route('teacher')?->id;

        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($teacherId)],
        ];

        if ($this->isMethod('POST')) {
            $rules['password'] = ['required', Password::min(8)];
        } else {
            $rules['password'] = ['nullable', Password::min(8)];
        }

        return $rules;
    }
}
