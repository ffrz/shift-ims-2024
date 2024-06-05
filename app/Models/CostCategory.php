<?php

namespace App\Models;

class CostCategory extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'description'
    ];
}
