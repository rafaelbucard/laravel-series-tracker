<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episodio extends Model
{
    use HasFactory;
    public  $timestamps = false;
    protected $fillable = ['numero'];

    public function serie()
    {
    return $this->belongsTo(Serie::class);
    }
    public function temporada()
    {
    return $this->belongsTo(Temporada::class);
    }
}
