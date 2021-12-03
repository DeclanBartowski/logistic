<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class MenuItemRequest extends FormRequest
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
            'link' => ['required', 'max:200'],
            'sort' => 'max:200',
            'menu_type_id' => '',
            'menu_group_id' => '',
        ];
    }

    public function attributes(): array
    {
        return ['name' => 'Название']; // TODO: Change the autogenerated stub
    }
}