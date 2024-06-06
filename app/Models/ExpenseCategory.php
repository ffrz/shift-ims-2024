<?php

namespace App\Models;

class ExpenseCategory extends BaseModel
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
