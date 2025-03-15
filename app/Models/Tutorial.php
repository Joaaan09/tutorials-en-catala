<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutorial extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'original_content',
        'translated_content'
    ];

    // Relación con los pasos
    public function steps()
    {
        return $this->hasMany(Step::class);
    }
}