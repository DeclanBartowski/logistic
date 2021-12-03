<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VacancyRelation extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'related_item_id',
    ];

    public function relatedItem(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Vacancy::class, 'related_item_id');
    }
}
