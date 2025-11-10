<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nft extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'price',
        'category',
        'owner_type',
        'owner_id',
        'approved',
        'on_sale',
    ];

    public function owner()
    {
        return $this->morphTo();
    }
}
