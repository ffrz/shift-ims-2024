<?php

namespace App\Models;

class ProductCategory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
