<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];
    public static $fields =
        [
            'name' => [
                'label' => 'Название',
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
        return $this->HasMany(MenuItem::class, 'menu_group_id');
    }
}
