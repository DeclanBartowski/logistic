<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class HeaderBannerRequest extends FormRequest
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
            'url' => ['nullable', 'max:200', 'regex:/[A-z]|[А-я]/'],
            'description' => 'max:200',
            'text' => 'max:200',
            'wages_title' => 'max:200',
            'wages' => '',
            'link' => [
                'required',
                Rule::unique('header_banners')->ignore($this->route()->parameter('header_banner') ?? '')
            ],
            'picture_old' => '',
            'picture' => '',
            'mob_picture_old' => '',
            'mob_picture' => '',
        ];
    }
}
