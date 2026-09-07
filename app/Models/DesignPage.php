<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignPage extends Model
{
    protected $guarded = [];
    protected $casts = ['photos' => 'array', 'texts' => 'array'];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
