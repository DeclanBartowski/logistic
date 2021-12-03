<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
class CommerceRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'active'=>'',
            'active_from'=>'max:200',
            'active_to'=>'max:200',
            'link'=>['required','max:200'],
            'picture_old'=>'',
            'picture'=>'required_without:picture_old',

        ];
    }
}
