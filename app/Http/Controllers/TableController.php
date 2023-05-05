<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Zone;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Validator;



class TableController extends Controller
{
    //

    private $table;
    private $order;
    private $orderDetail;

    public function __construct(Table $table,Order $order,OrderDetail $orderDetail ){
        $this->table = $table;
        $this->order = $order;
        $this->orderDetail = $orderDetail;
    }

    public function insert(Request $request){
        $validator = Validator::make($request->all(), [
            "number" => "required|unique:tables",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }

        $this->table->insert($request);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            "number" => "required|unique:tables,number,".$request->input("id"),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }
        $this->table->updatet($request);
    }

    public function delete(Request $request){

        $response = $this->order->getDetail($request->input("id"),"orders.table_id");
        if($response[0]["status"])
        {
            return response()->json([
                'errors' =>["table"=>["Esta mesa se encuenta abierta"]],
            ], 422);
        }
        else{
            $this->table->deletet($request);
        }

    }

    public function getTables(Request $resource){
        $tables = [];
        $zones = Zone::all();
        foreach($zones as $k =>$v)
        {
            $tables[$v->name]['tables'] = $v->tables;
        }
        return $tables;
    }

    public function changeTable(Request $data){
        $return = $this->table->changeTable($data);
        if($return )
        {
            return $this->order->changeTable($data);
        }
        return $return;
    }
}
