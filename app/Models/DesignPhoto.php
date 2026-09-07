<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignPhoto extends Model
{
    protected $guarded = [];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
