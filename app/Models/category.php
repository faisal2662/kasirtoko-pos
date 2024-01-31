<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class category extends Model
{
    use HasFactory, Sluggable;

    protected $guarded = ['id'];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }



    /**
     * Get all of the product for the category
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product(): HasMany
    {
        return $this->hasMany(Product::class ,'category_id', 'id');
    }
}