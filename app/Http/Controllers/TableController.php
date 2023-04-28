<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Zone;
use App\Models\Order;
use App\Models\OrderDetail;



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
