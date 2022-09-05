<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'slug',
        'price',
        'description',
        'photo',
        'store_id',
    ];


    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
