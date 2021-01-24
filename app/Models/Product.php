<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name', 'description', 'quantity', 'price' ,'active' ,'published'];

    public function categories()
    {
        return $this->belongsToMany(Category::class,'product_category','product_id', 'category_id');
    }
}
