<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_at', 'updated_at'];
}
