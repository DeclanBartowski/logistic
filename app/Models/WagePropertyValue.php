<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WagePropertyValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'name',
        'wage_property_id',
        'wage_property_option_id',
        'wage_id',
    ];

    public function property(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(WageProperty::class, 'wage_property_id');
    }
}
