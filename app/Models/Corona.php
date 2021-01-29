<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Corona extends Model
{
    protected $fillable = ['country_name', 'symptoms', 'cases'];
}