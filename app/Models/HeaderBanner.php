<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderBanner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'mob_picture',
        'picture',
        'link',
        'text',
        'wages_title',
        'wages',
        'url',
    ];
    protected $casts = [
        'wages' => 'array'
    ];
    public static $fields =
        [

            'name' => [
                'label' => 'Название',
                'type' => 'text'
            ],
            'description' => [
                'label' => 'Подзаголовок',
                'type' => 'editor'
            ],
            'text' => [
                'label' => 'Текст',
                'type' => 'editor'
            ],
            'wages_title' => [
                'label' => 'Заголовок зарплат',
                'type' => 'text'
            ],
            'url' => [
                'label' => 'Ссылка кнопки',
                'type' => 'text'
            ],
            'wages' => [
                'label' => 'Зарплаты',
                'type' => 'text',
                'multiple' => 'Y',
                'max_count' => 4
            ],
            'link' => [
                'label' => 'Ссылка',
                'type' => 'text'
            ],

            'picture' => [
                'label' => 'Изображение',
                'type' => 'file'
            ],
            'mob_picture' => [
                'label' => 'Изображение (мобильная версия)',
                'type' => 'file'
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
            'data' => 'link',
            'name' => 'Ссылка'
        ],
        [
            'data' => 'action',
            'name' => 'Действие',
            'orderable' => false,
            'searchable' => false
        ],
    ];
}
