<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $guarded = [];
    protected $casts = ['layout_structure' => 'array', 'is_active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(TemplateCategory::class, 'category_id');
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function getThumbnailAttribute($value)
    {
        if ($value && str_contains($value, '/storage/')) {
            return str_replace('/storage/', '/media/', $value);
        }
        return $value;
    }
}
