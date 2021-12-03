<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SocialRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:2', 'max:50', 'regex:/[A-z]|[А-я]/'],
            'link' => ['required', 'max:200'],
            'sort' => ['max:200'],
            'picture_old' => '',
            'picture' => ['required_without:picture_old'],

        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Название'
        ];
    }
}
