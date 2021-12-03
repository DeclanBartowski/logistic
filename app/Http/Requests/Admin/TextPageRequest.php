<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TextPageRequest extends FormRequest
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
            'name' => ['required', 'min:2', 'max:200', 'regex:/[A-z]|[А-я]/'],
            'slug' => [
                'required',
                Rule::unique('text_pages')->ignore($this->route()->parameter('text_page') ?? ''),
                'max:15'
            ],
            'text' => '',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Название'
        ];
    }
}
