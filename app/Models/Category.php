<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Mối quan hệ: Một category có nhiều foods
     */
    public function foods(): HasMany
    {
        return $this->hasMany(Food::class);
    }

    /**
     * Accessor để lấy số lượng món ăn trong category
     */
    public function getItemCountAttribute(): int
    {
        return $this->foods()->count();
    }
}