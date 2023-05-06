<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function tables(){
        return $this->hasMany(Table::class)->orderBy('number', 'asc');
    }
    public function insertz($data)
    {
        $zone = (new static);
        $zone->name = $data->input("name");
        $zone->save();
    }

    public function deletez($data)
    {
        $zone = (new static)::where("name",$data->input("name"))->first();

        if(count($zone->tables))
        {
            return response()->json([
                'errors' =>"Esta mesa se encuenta abierta",
            ], 422);
        }

        return $zone->delete();
    }
}
