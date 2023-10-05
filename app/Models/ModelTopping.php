<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTopping extends Model
{
    protected $table = "tbl_topping";
    protected $guarded = [];
    public $timestamps = false;
}