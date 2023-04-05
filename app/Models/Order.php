<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public static function createOrder($request)
    {    
        $total_amount = 0;

        foreach ($request["cart"] as $valor) {
            $total_amount += intval($valor["total_amount"]);
        }

        $find = (new static)::where([
            'status'=>1,
            'table_id'=>$request["key"]
        ])->first();
        if(!$find)
        {
            $order = (new static)::create([
                "date"=>Carbon::now(),
                "status"=>1,
                "discount"=>0,
                "total_amount"=>$total_amount,
                "description"=>"sdsddf",
                "table_id"=>$request["key"],
                "user_id"=>Auth::id(),
            ]);
        }
        else
        {
            $order = (new static)::find($find->id);

            $order->total_amount += $total_amount;
             
            $order->save();
        }
        

        return $order->id;
    }

    public function getDetail($id,$from = "order_id"){

        return OrderDetail::select('order_details.id','order_details.order_id','order_details.count','order_details.amount','products.name','order_details.product_id','orders.table_id')
        ->where($from,$id) 
        ->leftJoin('orders', function($join) 
        {
            $join->on('order_details.order_id', '=', 'orders.id');
        })
        ->leftJoin('products', function($join) 
        {
            $join->on('order_details.product_id', '=', 'products.id');
        })
        ->get();
    }





}
