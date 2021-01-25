<?php

namespace App\Models;

use App\Tool;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = ['name', 'description', 'quantity', 'price' ,'active' ,'published'];

    protected $attributes = [
        'is_active' => false,
        'is_published' => false
    ];
    protected $casts = [
        'is_active' => 'boolean',
        'is_published' => 'boolean'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class,'product_category','product_id', 'category_id');
    }

    public function setIsActiveAttribute($value){
        $this->attributes['is_active'] = $value;
    }

    public function setIsPublishedAttribute($value){
        $this->attributes['is_published'] = $value;
    }

}
