<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductOut extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the sale associated with the ProductOut
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class, 'code_sale', 'sale_id');
    }

    /**
     * Get the product associated with the ProductOut
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'code_product', 'product_id');
    }
}