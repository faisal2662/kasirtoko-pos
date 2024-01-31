<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Unit extends Model
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
     * Get all of the product for the Unit
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function product(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    
}