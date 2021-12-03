<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'logo'
    ];
    public static $fields =
        [
            'logo' => [
                'label' => 'Логотип',
                'type' => 'file'
            ],

        ];
    public static $listFields = [
        [
            'data' => 'id',
            'name' => 'ID',
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
