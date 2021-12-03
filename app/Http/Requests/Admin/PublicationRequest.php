<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PublicationRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name' => ['required', 'min:2', 'max:50', 'regex:/[A-z]|[А-я]/'],
            'slug' => [
                'required',
                Rule::unique('publications')->ignore($this->route()->parameter('publication') ?? ''),
                'max:15'
            ],
            'active' => '',
            'active_from' => ['date', 'nullable'],
            'active_to' => ['date', 'nullable'],
            'link' => '',
            'tag' => '',
            'preview_picture_old' => '',
            'preview_picture' => '',
            'preview_text' => '',
            'detail_text' => '',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Название'
        ];
    }
}
