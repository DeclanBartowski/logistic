<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class SeoRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => ['required','min:2','max:50','regex:/[A-z]|[А-я]/'],
            'link' => ['required','max:200'],
            'title' => ['required','max:200'],
            'description' => ['required','max:200'],
            'keywords' => ['required','max:200'],
        ];
    }
    public function attributes(): array
    {
        return [
            'name'=>'Название'
        ];
    }
}
