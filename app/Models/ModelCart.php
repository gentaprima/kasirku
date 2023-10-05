<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelCart extends Model
{
    protected $table = "tbl_cart";
    protected $guarded = [];
    public $timestamps = false;
}
