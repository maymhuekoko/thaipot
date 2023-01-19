<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TakeAwayOrder extends Model
{
    use HasFactory;

    protected $fillable = ["order_number", "menu_name", "menu_quantity", "menu_price", "menu_quantity", "total_price", "created_at", "updated_at"];
}
