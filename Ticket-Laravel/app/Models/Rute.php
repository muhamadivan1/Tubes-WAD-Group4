<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'tujuan',
        'start',
        'end',
        'harga',
        'jam',
        'transportasi_id'
    ];

    public function transportasi() //merelasikan foreign key transportasi id
    {
        return $this->belongsTo('App\Models\Transportasi', 'transportasi_id');
    }

    protected $table = 'rute';
}
