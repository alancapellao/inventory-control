<?php

namespace App\Managers;

use Illuminate\Support\Facades\DB;

class DatabaseManager
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::connection();
    }

    public function query($sql)
    {
        return $this->db->select($sql);
    }
}
