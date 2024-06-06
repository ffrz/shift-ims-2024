<?php

namespace App\Models;

class Expense extends BaseModel
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',

        'date',
        'description',
        'total',
        'notes',
        
        'created_datetime',
        'updated_datetime',
        'closed_datetime',
        
        'created_by_uid',
        'updated_by_uid',
        'closed_by_uid',
    ];

    public function idFormatted()
    {
        return 'BO-' . ($this->date) . '-' . str_pad($this->id, 3, '0', STR_PAD_LEFT);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by_uid');
    }

    public function closed_by()
    {
        return $this->belongsTo(User::class, 'closed_by_uid');
    }

    public function updated_by_by()
    {
        return $this->belongsTo(User::class, 'updated_by_uid');
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
}
