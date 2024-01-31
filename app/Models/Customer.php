<?php

namespace App\Models;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['code_customer','role_id','name', 'gender', 'phone', 'address'];

    /**
     * Get all of the sale for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}