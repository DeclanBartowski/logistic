<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WagePropertyRequest extends FormRequest
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
            'sort' => 'max:200',
            'active' => '',
            'hint' => 'max:200',
            'type' => 'required',
            'parent_id' => 'max:200',
            'options' => '',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'Тип свойства',
            'name' => 'Название'
        ];
    }
}
