<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalPurchase extends Model
{
    use HasFactory;

    protected $table = "total_purchases";

    protected $fillable = ["total_quantity", "price"];
}
