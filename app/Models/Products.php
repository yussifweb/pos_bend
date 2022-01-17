<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'category_id',
        'slug',
        'name',
        'description',
        'brand',
        'selling_price',
        'original_price',
        'qty',
        'unit',
        'image',
        'status',
    ];

    protected $with = ['category', 'store'];
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'id');
    }
}
