<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WageProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort',
        'active',
        'hint',
        'type',
        'parent_id',
    ];
    public static $fields =
        [
            'name' => [
                'label' => 'Название',
                'type' => 'text'
            ],
            'sort' => [
                'label' => 'Сортировка',
                'type' => 'number'
            ],
            'active' => [
                'label' => 'Активность',
                'type' => 'boolean'
            ],
            'type' => [
                'label' => 'Тип свойства',
                'type' => 'list',
                'values' => [
                    [
                        'name' => 'Число + валюта',
                        'id' => 'currency'
                    ],
                    [
                        'name' => 'Местоположение',
                        'id' => 'location'
                    ],
                    [
                        'name' => 'Список',
                        'id' => 'list'
                    ],
                ]
            ],
            'parent_id' => [
                'label' => 'Родительское свойство',
                'type' => 'list',
                'model' => 'App\Models\WageProperty'
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
            'data' => 'sort',
            'name' => 'Сортировка',
        ],
        [
            'data' => 'action',
            'name' => 'Действие',
            'orderable' => false,
            'searchable' => false
        ],
    ];

    public function options(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->HasMany(WagePropertyOption::class, 'wage_property_id');
    }

    public function values(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->HasMany(WagePropertyValue::class, 'wage_property_id');
    }
}
