<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    use HasFactory;
    protected $table = 'stores';
    protected $fillable = [
        'user_id',
        'name',
        'image',
        'status',
    ];

    protected $with = ['user'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'store_id', 'id');
    }

}
