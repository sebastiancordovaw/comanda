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
                "table_id"=>$request["key"],
                "user_id"=>Auth::id(),
            ]);
        }
        else
        {
            $order = (new static)::find($find->id);

            $order->total_amount += $total_amount;
            if($order->percentage > 0){
                $order->discount = ($order->total_amount*$order->percentage)/100;
            }
            
            $order->save();
        }
        

        return $order->id;
    }

    public function getDetail($id,$from = "order_id"){

        return OrderDetail::select('order_details.id','order_details.status','order_details.order_id','order_details.count','order_details.amount','order_details.note','products.name','order_details.product_id','orders.table_id')
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

    public function getDiscount($id){

        return (new static)::select('orders.percentage','orders.discount')
        ->where('table_id',$id) 
        ->first();
    }

    public static function deleteOrder($id)
    {
        $orderDetail = OrderDetail::find($id);
        $orderDetail->status = 0;
        $orderDetail->save();

        $order = (new static)::find($orderDetail->order_id);
        $order->total_amount -= $orderDetail->amount;

        if($order->percentage > 0){
            $order->discount = ($order->total_amount*$order->percentage)/100;
        }
        
        $order->save();

    }

    public static function applyDiscount($data)
    {
        $order = (new static)::where("table_id",$data['table'])->first();
        $order->discount=$data['discount'];
        $order->percentage=$data['porcentage'];
        $order->save();
    }

    public static function deleteDiscount($id)
    {
  
       $order = (new static)::where("table_id",$id)->first();
        $order->discount=0;
        $order->percentage=0;
        $order->save();
    }
     





}
