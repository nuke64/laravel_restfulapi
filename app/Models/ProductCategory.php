<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_category';

    protected $fillable = ['product_id', 'category_id'];

    protected $hidden = ['created_at', 'updated_at'];

}
