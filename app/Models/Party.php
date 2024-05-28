<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Party extends Model
{
    const TYPE_SUPPLIER = 1;
    const TYPE_CUSTOMER = 2;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type', 'id2', 'active', 'name', 'phone', 'address', 'notes'
    ];

    public static function getNextId2($type)
    {
        return DB::table('parties')
            ->selectRaw('ifnull(max(id2), 0) + 1 as next_id')
            ->where('type', '=', $type)
            ->value('next_id');
    }

}
