<?php

namespace App\Models;

// jj
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

      protected $fillable = [
        'nom',
        'slug',
        'description',
    ];

public function products()
{
    return $this->hasMany(Product::class, 'categorie_id');
}

}
