<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComponent extends Model
{
    protected $table = "product_components";
    protected $guarded = [];
    public $timestamps = false;
}
