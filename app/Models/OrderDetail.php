<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'count',
        'amount',
        'note'
    ];

    public function product()
    {
         return $this->belongsTo(Product::class);
    }

    public static function createOrderDetail($id,$product)
    {
        foreach ($product["cart"] as $valor) {

            $orderDetail = (new static)::create([
                "order_id"=> $id,
                "product_id"=>$valor['id'],
                "count"=>$valor['count'],
                "amount"=>$valor['total_amount'],
                "note"=>(isset($valor['note']))?$valor['note']:null,
            ]);
        }
    }

    public static function updateProductCheck($data,$tip)
    {
        
       $current = DB::raw('CURRENT_TIMESTAMP');
        foreach($data as $valor){

            $orderDetail = OrderDetail::find(explode("_",$valor["id"])[2]);
            $orderDetail->date_pay = $current;
            $orderDetail->tip = $valor["tip"];
            $orderDetail->save();

        }
        return true;
    }

}
