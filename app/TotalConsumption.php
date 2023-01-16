<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TotalConsumption extends Model
{
    use HasFactory;

    protected $fillable = ['total_quantity', 'price'];
}
