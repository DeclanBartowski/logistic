<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
class SiteSettingsRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'logo_old'=>'',
            'logo'=>'',
            'logo_auth_old'=>'',
            'logo_auth'=>'',
            'texts'=>'',

        ];
    }
}
