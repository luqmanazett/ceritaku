<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateCategory extends Model
{
    protected $guarded = [];

    public function templates()
    {
        return $this->hasMany(Template::class, 'category_id');
    }
}
