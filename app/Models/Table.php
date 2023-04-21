<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\TableEvent;
use App\Events\CloseTableEvent;
class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'status'
    ];

    public function order(){
        return $this->hasMany(Order::class, 'table_id', 'id');
    }

    public function openTable($id)
    {
        $table = (new static)::find($id);

        $order = Order::select('orders.id')
        ->where('orders.table_id',$table->id) 
        ->where('orders.status',1)
        ->join('order_details', function($join) 
        {
            $join->on('order_details.order_id', '=', 'orders.id');
        })
        ->first();

        if($table->status==0)
        {
            $table->status = 1;
            $table->save();
            event(new TableEvent($table->id,$table->number,$order->id));
        }
        
    }

    public function closeTable($id)
    {
        $table = (new static)::find($id);

        $order = Order::select('orders.id')
        ->where('orders.table_id',$table->id) 
        ->where('orders.status',1)
        ->join('order_details', function($join) 
        {
            $join->on('order_details.order_id', '=', 'orders.id');
        })
        ->first();

        if($table->status==1)
        {
            event(new CloseTableEvent($table->id,$order->id));
            $table->status = 0;
            $table->save();
        }
        
    }

    public function changeTable($data){
        $tableNew = (new static)::find($data["newTable"]);

        if($tableNew->status==0)
        {
            $tableNew->status = 1;
            $tableNew->save();

            $tableOld = (new static)::find($data["oldTable"]);
            if($tableOld->status==1)
            {
                $tableOld->status = 0;
                $tableOld->save();
            }            
            return true;
        }
        return false;
    }
}
