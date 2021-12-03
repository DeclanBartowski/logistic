<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commerce extends Model
{
    use HasFactory;

    protected $fillable = [
        'active',
        'active_from',
        'active_to',
        'picture',
        'link'
    ];
    protected $casts = [
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];
    public static $fields =
        [

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
            'picture' => [
                'label' => 'Изображение анонса',
                'type' => 'file'
            ],
        ];
    public static $listFields = [
        [
            'data' => 'id',
            'name' => 'ID'
        ],
        [
            'data' => 'link',
            'name' => 'Ссылка',
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
