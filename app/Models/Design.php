<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    protected $guarded = [];
    protected $casts = ['design_data' => 'array', 'expires_at' => 'datetime'];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function pages()
    {
        return $this->hasMany(DesignPage::class);
    }

    public function photos()
    {
        return $this->hasMany(DesignPhoto::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
