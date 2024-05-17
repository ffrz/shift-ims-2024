<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'active', 'name', 'phone', 'address', 'notes'
    ];

    public function idFormatted()
    {
        return 'SP' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }
}
