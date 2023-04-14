<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDetail;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

            /******realizar funcion cuando hay descuento *********** */

            $order->total_amount += $total_amount;
            $order->save();
        }
        

        return $order->id;
    }

    public function getDetail($id,$from = "order_id"){

        return OrderDetail::select('order_details.id','order_details.percentage','order_details.status','order_details.order_id','order_details.count','order_details.amount','order_details.note','products.name','order_details.product_id','orders.table_id','order_details.date_pay', 'order_details.tip')
        ->where($from,$id) 
        ->where('orders.status',"=",1)
        ->join('orders', function($join) 
        {
            $join->on('order_details.order_id', '=', 'orders.id');
        })
        ->join('products', function($join) 
        {
            $join->on('order_details.product_id', '=', 'products.id');
        })
       
        ->get();
    }

    public function getOrder($id,$from = "id"){

        return (new static)::where($from,$id) 
        ->where('orders.status',"=",1)
        ->first();
    }

    public function deleteOrder($id)
    {
        $detail = OrderDetail::find($id);
        $detail->status = 0;
        $detail->save();
    }

    public function deleteOrderPaid($id)
    {
        $detail = OrderDetail::find($id);
        $detail->date_pay;
      

        OrderDetail::where('status', 1)
        ->where('order_id', $detail->order_id )
        ->where('date_pay', $detail->date_pay )
        ->update([
                    'tip' =>0,
                    "date_pay" =>null
                ]);
    }

    public static function applyDiscount($data)
    {
        $orderFind = (new static)::
        where("table_id",$data['table'])
        ->where("status",1)
        ->first();

        if($data['percentage'] == 0 && $data['discount']>0 )
        {
            self::updateDiscountOrder($orderFind->id,$data['discount']);

            //self::updatePercentageDetailOrder($orderFind->id, 0);
            return;
        }
       
        if($data['discount']!= null)
        {
            //self::updateDiscountOrder($orderFind->id,0);

            self::updatePercentageDetailOrder($orderFind->id, $data['percentage']);
        } 
    }

    public static function updateDiscountOrder($id,$discount)
    {
        $order = (new static)::find($id);
        $order->discount = $discount;
        $order->save();
    }

    public static function updatePercentageDetailOrder($id, $percentage)
    {

        $orderDetail = OrderDetail::where("order_id",$id)->get();
        foreach($orderDetail as $detail)
        {
            if($detail['date_pay']==null && $detail['status']>0)
            {
                $product = OrderDetail::find($detail['id']);
                $product->percentage = $percentage;
                $product->save();
            }
            
        }
    }

    public static function deleteDiscountPermanent($id)
    {
        self::updateDiscountOrder($id,0);
    }

    public static function deleteDiscountPercentage($id)
    {
        self::updatePercentageDetailOrder($id, 0);
    }

    public function deleteDiscountProduct($id)
    {
        $detail = OrderDetail::find($id['product']);
        $detail->percentage = 0;
        $detail->save();
    }

    public function addDiscountProduct($data)
    {
        $detail = OrderDetail::find($data['product']);
        $detail->percentage =$data['discount'];
        $detail->save();
    }

    public static function closeOrder($data)
    {
        $order = (new static)::find($data['order']);
        $order->status=0;
        $order->save();

        $count = OrderDetail::where('status', 1)
        ->where('order_id', $data['order'])
        ->where('date_pay', null)
        ->count();
        OrderDetail::where('status', 1)
        ->where('order_id', $data['order'])
        ->where('date_pay', null)
        ->update([
                    'tip' => $data['tip']/$count,
                    "date_pay" =>$current = DB::raw('CURRENT_TIMESTAMP')
                ]);
    }

    public static function closeOrderCheck($data)
    {
        $order = (new static)::find($data['order']);
        $order->status=0;
        $order->save();

        $count = OrderDetail::where('status', 1)
        ->where('order_id', $data['order'])
        ->where('date_pay', null)
        ->count();
        OrderDetail::where('status', 1)
        ->where('order_id', $data['order'])
        ->where('date_pay', null)
        ->update([
                    'tip' => $data['tip']/$count,
                    "date_pay" =>$current = DB::raw('CURRENT_TIMESTAMP')
                ]);
    }

    public function changeTable($data){
    
        $order = (new static)::where("table_id",$data["oldTable"])
        ->where("status",1)
        ->first();
        $order->table_id=$data["newTable"];
        $order->save();
        $table = Table::where('id',$data["newTable"])->first();
        
        return $table->number;

    }
     





}
