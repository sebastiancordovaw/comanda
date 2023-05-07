<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;
class ZoneController extends Controller
{
    //

    private $zone;

    public function __construct(Zone $zone)
    {
        $this->zone = $zone;
    }

    public function insert(Request $request){

        $validator = Validator::make($request->all(), [
            "name" => "required|unique:zones",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }

        $this->zone->insertz($request);
    }

    public function delete(Request $request){

        return $this->zone->deletez($request);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            "name" => "required|unique:zones,name,".$request->input("name")
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }
        return $this->zone->updatez($request);
    }
}
