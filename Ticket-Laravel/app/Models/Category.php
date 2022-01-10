<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model    //mendefiniskan model tabel category
{
    use HasFactory;

    protected $fillable = [ //kolom yg perlu diisi
        'name',
        'slug'
    ];

    protected $table = 'category';
}
