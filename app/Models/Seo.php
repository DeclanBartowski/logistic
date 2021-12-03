<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'link',
        'title',
        'description',
        'keywords',
    ];

    public static $fields =
        [
            'name' => [
                'label' => 'Название',
                'type' => 'text'
            ],
            'link' => [
                'label' => 'Ссылка',
                'type' => 'text'
            ],
            'title' => [
                'label' => 'Заголовок',
                'type' => 'text'
            ],
            'description' => [
                'label' => 'Описание',
                'type' => 'textarea'
            ],
            'keywords' => [
                'label' => 'Ключевые слова',
                'type' => 'textarea'
            ]
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
}
