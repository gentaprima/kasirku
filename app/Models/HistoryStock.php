<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    protected $table = "tbl_history_stock";
    protected $guarded = [];
    public $timestamps = false;
}
