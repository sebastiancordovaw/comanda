<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'measure',
        'quantityUnitMeasure',
        'stock',
        'category_id',
        'status',
        'order'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getProductsCategory($id)
    {
        $category = Category::find($id);
        return $category->products;
    }

    public function categoriesTree(){

        $category = Category::where("category_id","=",null)->orderBy("order","asc")->get();

        $data = [];
        $constador = 0;
        foreach($category as $k => $v){

            $data["root"][$constador] =
            [
                "key"=>$v["id"],
                "label"=>$v["name"],
                "data"=>$v["name"],
            ];
            if(count($v->categories))
            {
                foreach($v->categories as $k2 => $v2)
                {
                    $data["root"][$constador]["children"][]=[
                        "key"=>$v2["id"],
                        "label"=>$v2["name"],
                        "data"=>$v2["name"],
                    ];
                }

            }

            $constador++;
        }

        return $data;

        /*return json_decode('{
            "root": [
                {
                    "key": "0",
                    "label": "Documents",
                    "data": "Documents Folder",
                    "icon": "pi pi-fw pi-inbox",
                    "children": [{
                        "key": "2",
                        "label": "Work",
                        "data": "Work Folder",
                        "icon": "pi pi-fw pi-cog",
                        "children": [{ "key": "0-0-0", "label": "Expenses.doc", "icon": "pi pi-fw pi-file", "data": "Expenses Document" }, { "key": "0-0-1", "label": "Resume.doc", "icon": "pi pi-fw pi-file", "data": "Resume Document" }]
                    },
                    {
                        "key": "3",
                        "label": "Home",
                        "data": "Home Folder",
                        "icon": "pi pi-fw pi-home",
                        "children": [{ "key": "0-1-0", "label": "Invoices.txt", "icon": "pi pi-fw pi-file", "data": "Invoices for this month" }]
                    }]
                },
                {
                    "key": "1",
                    "label": "Events",
                    "data": "Events Folder",
                    "icon": "pi pi-fw pi-calendar",
                    "children": [
                        { "key": "1-0", "label": "Meeting", "icon": "pi pi-fw pi-calendar-plus", "data": "Meeting" },
                        { "key": "1-1", "label": "Product Launch", "icon": "pi pi-fw pi-calendar-plus", "data": "Product Launch" },
                        { "key": "1-2", "label": "Report Review", "icon": "pi pi-fw pi-calendar-plus", "data": "Report Review" }]
                },
                {
                    "key": "2",
                    "label": "Movies",
                    "data": "Movies Folder",
                    "icon": "pi pi-fw pi-star",
                    "children": [{
                        "key": "2-0",
                        "icon": "pi pi-fw pi-star",
                        "label": "Al Pacino",
                        "data": "Pacino Movies",
                        "children": [{ "key": "2-0-0", "label": "Scarface", "icon": "pi pi-fw pi-video", "data": "Scarface Movie" }, { "key": "2-0-1", "label": "Serpico", "icon": "pi pi-fw pi-video", "data": "Serpico Movie" }]
                    },
                    {
                        "key": "2-1",
                        "label": "Robert De Niro",
                        "icon": "pi pi-fw pi-star",
                        "data": "De Niro Movies",
                        "children": [{ "key": "2-1-0", "label": "Goodfellas", "icon": "pi pi-fw pi-video", "data": "Goodfellas Movie" }, { "key": "2-1-1", "label": "Untouchables", "icon": "pi pi-fw pi-video", "data": "Untouchables Movie" }]
                    }]
                }
            ]
        }');*/
    }


    public function getProducts($id)
    {
        $category = Category::find($id);
        return $category->products;

    }

    public function addProducto($resquest)
    {
        $producto = (new static);
        $producto->name = ucfirst($resquest->input("name"));
        $producto->category_id = $resquest->input("category_id");
        $producto->price = $resquest->input("price");
        $producto->description = ($resquest->input("description"))?$resquest->input("description"):'';
        $producto->qr = $resquest->input("qr");

        if($resquest->input("status") == '1' ||  $resquest->input("status") == 'true')
        {
            $producto->status = 1;
        }
        else
        {
            $producto->status = 0;
        }

        $producto->save();


        $last = $producto::latest('id')->first();
        return $last;
    }


    public function updateProducto($resquest)
    {
        $producto = (new static)::find($resquest->input("id"));
        $producto->name = ucfirst($resquest->input("name"));
        $producto->category_id = $resquest->input("category_id");
        $producto->price = $resquest->input("price");
        $producto->description = ($resquest->input("description"))?$resquest->input("description"):'';
        $producto->qr = $resquest->input("qr");

        if($resquest->input("status") == '1' ||  $resquest->input("status") == 'true')
        {
            $producto->status = 1;
        }
        else
        {
            $producto->status = 0;
        }

        $producto->save();
        return $resquest->input("id");
    }

    public function deleteProduct($request)
    {
        try
        {
            DB::beginTransaction();
            $category = (new static)::find($request->input("id"));
            $category->delete();
            DB::commit();
        }
        catch(\Exception $e)
        {
            DB::rollback();
            return response()->json($e->getMessage(),422);
        }
    }

    public function updateSortProduct($request)
    {
        $request = json_decode($request);
        $count = 0;
        foreach($request as $k => $v)
        {
            $count++;
            $product = (new static)::find($v->id);
            $product->order = $count;
            $product->save();
        }
    }

}
