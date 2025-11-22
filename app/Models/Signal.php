<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signal extends Model
{
    protected $fillable = [
        'name',
        'price',
        'category',
        'description_1',
        'description_2',
        'duration',
        'status',
    ];

    public function subscribers()
    {
        return $this->hasMany(UserSignal::class);
    }

}
