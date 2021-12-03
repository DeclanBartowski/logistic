<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name',
            'slug',
            'active',
            'active_from',
            'active_to',
            'link',
            'preview_picture',
            'preview_text',
            'detail_text',
            'city',
            'schedule',
            'wage',
            'related',
        ];
    protected $casts =
        [
            'active_from' => 'datetime',
            'active_to' => 'datetime',
            'related' => 'array'
        ];
    public static $fields =
        [
            'name' => [
                'label' => 'Название',
                'type' => 'text'
            ],
            'slug' => [
                'label' => 'Символьный код',
                'type' => 'text'
            ],
            'active' => [
                'label' => 'Активность',
                'type' => 'boolean'
            ],
            'active_from' => [
                'label' => 'Начало активности',
                'type' => 'date'
            ],
            'active_to' => [
                'label' => 'Окончание активности',
                'type' => 'date'
            ],
            'link' => [
                'label' => 'Ссылка',
                'type' => 'text'
            ],
            'preview_picture' => [
                'label' => 'Изображение анонса',
                'type' => 'file'
            ],
            'preview_text' => [
                'label' => 'Анонс',
                'type' => 'editor'
            ],
            'detail_text' => [
                'label' => 'Текст публикации',
                'type' => 'editor'
            ],
            'city' => [
                'label' => 'Город',
                'type' => 'text'
            ],
            'schedule' => [
                'label' => 'График работы',
                'type' => 'text'
            ],
            'wage' => [
                'label' => 'Заработная плата',
                'type' => 'number'
            ],
            'related' => [
                'label' => 'Похожие вакансии',
                'type' => 'list',
                'model' => 'App\Models\Vacancy',
                'multiple' => 'Y'
            ],
        ];
    public static $listFields = [
        [
            'data' => 'id',
            'name' => 'ID'
        ],
        [
            'data' => 'name',
            'name' => 'Название',
            'link' => 'Y'
        ],
        [
            'data' => 'active',
            'name' => 'Активность',
        ],
        [
            'data' => 'action',
            'name' => 'Действие',
            'orderable' => false,
            'searchable' => false
        ],
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

}
