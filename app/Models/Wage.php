<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];
    public static $fields =
        [
            'name' => [
                'label' => 'Название',
                'type' => 'text'
            ],
            'user_id' => [
                'label' => 'Пользователь',
                'type' => 'list',
                'model' => 'App\Models\User'
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

    public function properties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->HasMany(WagePropertyValue::class, 'wage_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
