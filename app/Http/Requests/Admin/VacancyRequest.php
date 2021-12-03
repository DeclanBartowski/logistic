<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class VacancyRequest extends FormRequest
{


    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->slug),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:50', 'regex:/[A-z]|[А-я]/'],
            'slug' => [
                'required',
                Rule::unique('vacancies')->ignore($this->route()->parameter('vacancy') ?? ''),
                'max:15'
            ],
            'active' => '',
            'active_from' => ['date', 'nullable'],
            'active_to' => ['date', 'nullable'],
            'preview_picture_old' => '',
            'preview_picture' => '',
            'city' => 'max:200',
            'schedule' => 'max:200',
            'wage' => 'max:200',
            'link' => 'max:200',
            'preview_text' => 'max:200',
            'detail_text' => 'max:200',
            'related' => '',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Название'
        ];
    }
}
