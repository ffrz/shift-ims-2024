<?php

namespace App\Models;

class Customer extends Party
{
    public $table = 'parties';

    public function __construct()
    {
        $this->type = Party::TYPE_CUSTOMER;
    }

    public function idFormatted()
    {
        return 'CS' . str_pad($this->id2, 5, '0', STR_PAD_LEFT);
    }

    public static function query()
    {
        $q = parent::query();
        $q->where('type', '=', Party::TYPE_CUSTOMER);
        return $q;
    }
}
