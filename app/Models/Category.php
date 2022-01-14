<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'slug',
        'status',
        'store_id',
    ];


    protected $with = ['stores'];
    public function stores()
    {
        return $this->belongsTo(Stores::class, 'store_id', 'id');
    }

}
