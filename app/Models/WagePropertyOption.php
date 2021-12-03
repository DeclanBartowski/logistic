<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WagePropertyOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'value',
        'hint',
        'sort',
        'wage_property_id',
    ];
    public static $linkFields = [
        'value' => [
            'label' => 'Значение',
            'type' => 'text'
        ],
        'hint' => [
            'label' => 'Подсказка',
            'type' => 'text'
        ],
        'sort' => [
            'label' => 'Сортировка',
            'type' => 'number'
        ],
    ];
    public function wageProperty(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(WageProperty::class, 'wage_property_id');
    }
}
