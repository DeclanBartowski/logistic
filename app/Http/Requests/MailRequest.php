<?php

namespace App\Http\Requests;

use App\Services\Salary;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
class MailRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => ['required'],
            'title' => ['required'],
            'text' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Для отправки письма заполните поле E-Mail',
            'title.required' => 'Для отправки письма заполните поле Заголовок',
            'text.required' => 'Для отправки письма заполните поле Сообщение',
        ];
    }
}
