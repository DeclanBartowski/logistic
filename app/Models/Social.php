<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'picture',
        'link',
        'sort',
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
            'picture' => [
                'label' => 'Иконка',
                'type' => 'file'
            ],
            'sort' => [
                'label' => 'Сортировка',
                'type' => 'number'
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
}
