<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the sale associated with the Payment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    // public function sale(): HasOne
    // {
    //     return $this->hasOne(Sale::class, 'code_sale', 'sale_id');
    // }
}