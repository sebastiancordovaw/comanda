<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function menuDetail()
    {
        return $this->belongsToMany(Products::class, 'detail_menus', 'menu_id', 'product_id');
    }
}
