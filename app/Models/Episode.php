<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    public  $timestamps = true;
    protected $fillable = ['number'];

    public function serie()
    {
    return $this->belongsTo(Series::class);
    }
    public function temporada()
    {
    return $this->belongsTo(Season::class);
    }
}
