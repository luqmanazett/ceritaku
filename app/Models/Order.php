<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    protected $casts = ['ordered_at' => 'datetime'];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
