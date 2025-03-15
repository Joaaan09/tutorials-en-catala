<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    use HasFactory;

    protected $fillable = [
        'tutorial_id',
        'instructions',
        'translated_instructions',
        'order'
    ];

    // Relación con las imágenes
    public function images()
    {
        return $this->hasMany(Image::class);
    }
}