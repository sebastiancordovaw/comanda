<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    //
    private $order;
    private $orderDetail;
    private $table;
    public function __construct(Order $order, OrderDetail $orderDetail,Table $table)
    {
        $this->order = $order;
        $this->orderDetail = $orderDetail;
        $this->table = $table;
    }

    public function setOrderDetail(Request $request){
       
        $dataOrder = $request->request->all();
        try{
            DB::beginTransaction();
            $id = $this->order->createOrder($dataOrder);
            $this->orderDetail->createOrderDetail($id,$dataOrder);
            $this->table->openTable($dataOrder["key"]);
            DB::commit();
            return $this->order->getDetail($id);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json($e->getMessage(),500);
        }
        
    }

    public function getOrderDetail(Request $id){
        
        $detail =  $this->order->getDetail($id["table"],"orders.table_id");
        $discount =  $this->order->getdiscount($id["table"]);
        return ['detail'=>$detail, 'discount'=>$discount];
    }

    public function deleteOrderTable(Request $order_detail){
        return $this->order->deleteOrder($order_detail['order_detail']);
    }

    public function applyDiscount(Request $data){
        return $this->order->applyDiscount($data);
    }
}
