<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name',
            'active_from',
            'active_to',
            'active',
            'link',
            'tag',
            'preview_picture',
            'preview_text',
            'detail_text',
            'slug',
        ];
    protected $casts =
        [
            'active_from' => 'datetime',
            'active_to' => 'datetime'
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
            'tag' => [
                'label' => 'Тэг',
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
