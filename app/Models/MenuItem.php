<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable =
        [
            'name',
            'link',
            'menu_type_id',
            'menu_group_id',
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
            'sort' => [
                'label' => 'Сортировка',
                'type' => 'number'
            ],
            'menu_type_id' => [
                'label' => 'тип меню',
                'type' => 'list',
                'model' => 'App\Models\MenuType'
            ],
            'menu_group_id' => [
                'label' => 'Группа меню',
                'type' => 'list',
                'model' => 'App\Models\MenuGroup'
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

    public function group(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(MenuGroup::class, 'menu_group_id');
    }

    public function type(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(MenuType::class, 'menu_type_id');
    }
}
