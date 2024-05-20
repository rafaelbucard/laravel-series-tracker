<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    use HasFactory;
    public  $timestamps = true;
    protected $fillable = ['number'];

    public function episodes()
    {
        return $this->hasMany(Episode::class);

    }
    public function series()
    {

    return $this->belongsTo(Series::class);

    }
}
