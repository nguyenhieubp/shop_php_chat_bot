<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'image',
        'is_featured',
        'is_active',
        'stock',
        'min_height',
        'max_height',
        'min_weight',
        'max_weight',
        'gender',
        'material',
        'size_guide'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
