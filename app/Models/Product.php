<?php

namespace App\Models;

use App\Models\Unit;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,Sluggable;

    protected $guarded = ['id'];
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name_product'
            ]
        ];
    }
    /**
     * Get the category as  sociated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function category():  HasOne
    {
        return $this->hasOne(category::class, 'slug', 'category_id');
    }

    /**
     * Get the unit associated with the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function unit(): HasOne
    {
        return $this->hasOne(Unit::class, 'slug', 'unit');
    }

    /**
     * Get all of the productIn for the Product
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productIn(): HasMany
    {
        return $this->hasMany(ProductIn::class, 'product_id', 'slug');
    }
}