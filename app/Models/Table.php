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
}
