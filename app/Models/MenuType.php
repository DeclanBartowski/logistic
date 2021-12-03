<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug'
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

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->HasMany(MenuItem::class, 'menu_type_id');
    }
}
