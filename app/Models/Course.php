<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
    ];

    // ✅ Tambahkan ini
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}
