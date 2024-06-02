<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['id','total_price'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_product')->withPivot('quantity');
    }
}
