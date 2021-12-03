<?php

namespace App\Http\Requests;

use App\Services\Salary;
use Illuminate\Foundation\Http\FormRequest;

class WageRequest extends FormRequest
{


    /**
     * @param Salary $salary
     * @return array
     */
    public function rules(Salary $salary): array
    {
        return array_merge($salary->getRules(), ['g-recaptcha-response' => 'required|captcha',]);

    }

    /**
     * @return array
     */
    public function attributes(): array
    {
        $salary = new Salary();
        return $salary->getAttributes();
    }
}
