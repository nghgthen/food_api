<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Food extends Model
{
    use HasFactory;

    // Thêm dòng này để chỉ định tên bảng là 'foods'
    protected $table = 'foods';

    protected $fillable = [
        'name', 'description', 'price', 'rating', 
        'review_count', 'image', 'category_id', 'is_popular'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'rating' => 'decimal:1',
        'is_popular' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}