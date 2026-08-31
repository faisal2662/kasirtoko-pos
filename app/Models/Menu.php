<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $table = 'menu';

    protected $guarded = ['id'];

    public $timestamps = false;

    protected $fillable =
    [
    'parent_id',
    'name',
    'route',
    'icon',
    'order',
    'is_active',
    'created_date',
    'created_by',
    'updated_by',
    'updated_date',
    'is_deleted'
    ];


    /**
     * Sub menu
     */
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')
                    ->where('is_deleted', 'N')
                    ->orderBy('order');
    }

    /**
     * Parent menu
     */
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

}
