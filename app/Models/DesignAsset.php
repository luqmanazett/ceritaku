<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignAsset extends Model
{
    use HasFactory;

    protected $fillable = [
        'design_id',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
