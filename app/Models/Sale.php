<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\SaleDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    /**
     * Get the customer associated with the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    
    }
    /**
     * Get all of the saleDetail for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class, 'sale_id', 'code_sale');
    }

    /**
     * Get all of the productOut for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productOut(): HasMany
    {
        return $this->hasMany(ProductOut::class, 'product_id', 'code_product');
    }

    // hasM
}