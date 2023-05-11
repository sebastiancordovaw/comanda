<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qr',
        'order',
        'category_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class)
        ->orderBy('status', 'desc')
        ->orderBy('order', 'asc');


    }

    public function categories()
    {
        return $this->hasMany(Category::class)->orderBy('name', 'asc');
    }

    public function getCategories()
    {
        $fathers =  (new static)::select("categories.id","categories.category_id","categories.name", 'categories.qr')
        ->selectRaw("count(products.id) as products")
        ->leftJoin('products', function($join)
        {
            $join->on('categories.id', '=', 'products.category_id');
        })
        ->where("categories.category_id",null)
        ->groupBy("categories.id","categories.name", "categories.qr")
        ->orderBy("categories.order", "asc")
        ->orderBy("categories.name", "asc")
        ->get();

        $response = [];

        foreach($fathers as $k => $v)
        {
            $response[]=$v;
            $v->categories;
            foreach($v->categories as $k2 => $v2)
            {
                $v2->products;
                $v2->categories;
            }

        }
        return $response;

    }

    public function getCategory($request)
    {
        $response =  (new static)::find($request->input("id"));
        $response->products;
        $response->categories;

        return $response;

    }

    public function createCategory($request)
    {
        $category = (new static);
        $category->name =ucfirst($request->input("name"));
        if($request->input("qr")=='true' || $request->input("qr") =="1")
        {
            $category->qr = 1;
        }
        else{
            $category->qr = 0;
        }

        if($request->input("isSubCategory")=='true' || $request->input("isSubCategory")=='1')
        {
            if($request->input("category_id") && $request->input("category_id") != 'null')
            {
                $category->category_id = $request->input("category_id");
            }
            else{
                $category->category_id = null;
            }
        }
        else
        {
            $category->category_id = null;
        }

        $category->save();

        $result =  $category::latest('id')->first();

        return $result->id;
    }

    public function updateCategory($request)
    {
        $category = (new static)::find($request->input("id"));
        $category->name =ucfirst($request->input("name"));

        if($request->input("qr") =="true" || $request->input("qr") =="1")
        {
            $category->qr = 1;
        }
        else{
            $category->qr = 0;
        }

        if($request->input("isSubCategory")=='true' || $request->input("isSubCategory")=='1')
        {
            if($request->input("category_id") && $request->input("category_id") != 'null')
            {
                $category->category_id = $request->input("category_id");
            }
            else{
                $category->category_id = null;
            }
        }
        else
        {
            $category->category_id = null;
        }


        $category->save();

        return  $request->input("id");
    }

    public function deleteCategory($request)
    {
        $categories =  (new static)::selectRaw("count(categories.id) as categories_hijas")
        ->Join('categories as cat', function($join)
        {
            $join->on('cat.category_id', '=', 'categories.id');
        })
        ->where("categories.id",$request->input("id"))
        ->first();


        $products =  (new static)::selectRaw("count(products.id) as products")
        ->Join('products', function($join)
        {
            $join->on('products.category_id', '=', 'categories.id');
        })
        ->where("categories.id",$request->input("id"))
        ->first();

        if($products['products'] || $categories['categories_hijas'])
        {
            return response()->json([
                'error' =>"Categoría en uso",
            ], 422);
        }
        else{
            $category= (new static)::find($request->input("id"));
            $category->delete();
        }
    }

    public function updateSort($request)
    {
        $request = json_decode($request);
        $count = 0;
        foreach($request as $k => $v)
        {
            $count++;
            $category = (new static)::find($v->id);
            $category->order = $count;
            $category->save();
        }
    }
}
