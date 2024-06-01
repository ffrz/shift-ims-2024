<?php

namespace App\Models;

class Supplier extends Party
{
    public $table = 'parties';

    public function __construct()
    {
        parent::__construct();
        $this->type = Party::TYPE_SUPPLIER;
    }

    public function idFormatted()
    {
        return 'SUP-' . str_pad($this->id2, 5, '0', STR_PAD_LEFT);
    }

    public static function query()
    {
        $q = parent::query();
        $q->where('type', '=', Party::TYPE_SUPPLIER);
        return $q;
    }
}
