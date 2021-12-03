<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextPage extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name',
            'text',
            'slug'
        ];
    public static $fields =
        [
            'name' => [
                'label' => 'Название',
                'type' => 'text'
            ],
            'text' => [
                'label' => 'Текст',
                'type' => 'editor'
            ],
            'slug' => [
                'label' => 'Символьный код',
                'type' => 'text'
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
