<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseHistory extends Model
{
    use HasFactory;

    protected $fillable = ["total_purchase_id", "pi_category_id", "purchase_item_id", "name", "purchase_no", "amount", "unit", "price", "stock_quantity", "created_at"];
}
