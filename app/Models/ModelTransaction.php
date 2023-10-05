<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTransaction extends Model
{
    protected $table = "tbl_transaction";
    protected $guarded = [];
    public $timestamps = false;
}
