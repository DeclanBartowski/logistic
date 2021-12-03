<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo',
        'logo_auth',
        'texts',
    ];
    protected $casts = [
        'texts' => 'array'
    ];
    public static $fields =
        [

            'logo' => [
                'label' => 'Логотип',
                'type' => 'file'
            ],
            'logo_auth' => [
                'label' => 'Логотип при авторизации',
                'type' => 'file'
            ],
            'texts' => [
                'label' => 'Тексты',
                'type' => 'json',
                'fields' => [
                    'top_text' => [
                        'label' => 'Текст в шапке',
                        'type' => 'text'
                    ],
                    'main_form_title' => [
                        'label' => 'Заголовок формы на главной',
                        'type' => 'text'
                    ],
                    'main_form_top_text' => [
                        'label' => 'Текст под заголовком формы',
                        'type' => 'text'
                    ],
                    'main_form_salary' => [
                        'label' => 'Текст медианной зарплаты ({form_quantity} - количество анкет)',
                        'type' => 'text'
                    ],
                    'main_form_salary_error' => [
                        'label' => 'Текст ошибки',
                        'type' => 'text'
                    ],
                    'main_form_salary_bottom' => [
                        'label' => 'Текст снизу формы добавления зарплаты',
                        'type' => 'text'
                    ],
                    'filter_hint' => [
                        'label' => 'Подсказка фильтра',
                        'type' => 'editor'
                    ],
                    'contacts' => [
                        'label' => 'Текст контактов в футере',
                        'type' => 'editor'
                    ],
                    'link' => [
                        'label' => 'Ссылка в футере',
                        'type' => 'editor'
                    ],
                    'copyright' => [
                        'label' => 'Copyright',
                        'type' => 'editor'
                    ],
                ]
            ],
        ];
    public static $listFields = [
        [
            'data' => 'id',
            'name' => 'ID'
        ],

    ];
}
