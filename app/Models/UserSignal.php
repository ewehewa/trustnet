<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSignal extends Model
{
    protected $fillable = [
        'user_id',
        'signal_id',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function signal()
    {
        return $this->belongsTo(Signal::class);
    }
}

