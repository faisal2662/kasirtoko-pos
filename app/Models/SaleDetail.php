<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the sale associated with the SaleDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class, 'id', 'sale_id');
    }


    public function product() :HasOne
    {
        return $this->hasOne(Product::class,'id', 'product_id');
    }
}
