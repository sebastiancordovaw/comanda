<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;
class OrderController extends Controller
{
    //
    private $order;
    private $orderDetail;
    public function __construct(Order $order, OrderDetail $orderDetail)
    {
        $this->order = $order;
        $this->orderDetail = $orderDetail;
    }

    public function setOrderDetail(Request $request){
       
        $dataOrder = $request->request->all();
        try{
            DB::beginTransaction();
            $id = $this->order->createOrder($dataOrder);
            $this->orderDetail->createOrderDetail($id,$dataOrder);
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
        return $this->order->getDetail($id["table"],"orders.table_id");
    }

    public function deleteOrderTable(Request $order_detail){
        return $this->order->deleteOrder($order_detail['order_detail']);
    }
}
