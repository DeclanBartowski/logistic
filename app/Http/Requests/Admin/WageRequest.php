<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WageRequest extends FormRequest
{

    /*TODO раскоментить проверку на уникальность*/
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $arRules = [
            'name' => ['required', 'min:2', 'max:50', 'regex:/[A-z]|[А-я]/'],
            'user_id' => [
                'required'
                /*,Rule::unique('wages')->ignore($this->route()->parameter('wage') ?? '')*/,
                'max:200'
            ],
            'properties' => ''
        ];

        $arRules['properties.11.value.value'] = 'required';
        $arRules['properties.11.value.currency'] = 'required';
        $arRules['properties.12.value.value'] = 'required';
        $arRules['properties.12.value.currency'] = 'required';
        return $arRules;
    }

    public function attributes(): array
    {
        return [
            'name' => 'Название',
            'user_id ' => 'Пользователь',
            'properties.11.value.value' => 'Ваш оклад в месяц (Сумма)',
            'properties.11.value.currency' => 'Ваш оклад в месяц (Валюта)',
            'properties.12.value.value' => 'Ваша итоговая зарплата в месяц (Сумма)',
            'properties.12.value.currency' => 'Ваша итоговая зарплата в месяц (Валюта)',
        ];
    }
}
