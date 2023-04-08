<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        if($table->status==0)
        {
            $table->status = 1;
            $table->save();
        }
        
    }

    public function closeTable($id)
    {
        $table = (new static)::find($id);
        if($table->status==1)
        {
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
