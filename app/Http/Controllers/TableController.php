<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;

class TableController extends Controller
{
    //
    public function getTables(Request $resource){
        return Table::all();
    }
}
