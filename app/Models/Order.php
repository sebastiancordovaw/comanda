<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;

class Order extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'date',
        'status',
        'discount',
        'total_amount',
        'description',
        'table_id',
        'user_id'
    ];

    public function detail()
    {
         return $this->belongsToMany(Product::class, 'order_details', 'order_id', 'product_id');
    }

    public function table(){
        return $this->belongsTO(Table::class);
    }



}
