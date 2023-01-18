<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumptionHistory extends Model
{
    use HasFactory;

    protected $fillable = ['total_consumption_id', 'pi_category_id', 'purchase_item_id', 'name', 'consumption_no', 'unit', 'stock_quantity'];
}
