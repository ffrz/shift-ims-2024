<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroupAccess extends Model
{    
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id', 'resource', 'allow'
    ];
}
