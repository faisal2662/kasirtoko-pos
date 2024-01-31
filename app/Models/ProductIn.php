<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductIn extends Model
{
    use HasFactory;
    protected $guarded =['id'];

 /**
  * Get the product associated with the ProductIn
  *
  * @return \Illuminate\Database\Eloquent\Relations\HasOne
  */
 public function product(): HasOne
 {
     return $this->hasOne(Product::class, 'slug', 'product_id');
 }
}