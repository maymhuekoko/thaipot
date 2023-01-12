<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pi extends Model
{
    use HasFactory;

    protected $table = "pi";

    protected $fillable = ["name", "pi_category_id", "purchase_no", "amount", "unit", "price", "stock_quantity"];
}
