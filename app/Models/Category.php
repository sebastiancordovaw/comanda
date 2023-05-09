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
        'category_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class)->orderBy('name', 'asc');
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
        ->orderBy("categories.name", "asc")
        ->get();

        $response = [];

        foreach($fathers as $k => $v)
        {
            $response[$v["id"]]=$v;
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

        if($request->input("isSubCategory")!='false')
        {
            if($request->input("fatherCategory"))
            {
                $category->category_id = $request->input("fatherCategory");
            }
        }
        else
        {
            $category->category_id = null;
        }

        $category->save();

        $id =  $category::latest('id')->first();
        $categoryList = self::getCategories();

        return response()->json([
            'idrow' =>$id,
            'rows' =>$categoryList,
        ], 200);
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

        if($request->input("isSubCategory")!='false')
        {
            if($request->input("fatherCategory"))
            {
                $category->category_id = $request->input("fatherCategory");
            }
        }
        else
        {
            $category->category_id = null;
        }

        $response = $category->save();

        $categoryList = self::getCategories();

        return response()->json([
            'idrow' =>$request->input("id"),
            'rows' =>$categoryList,
        ], 200);
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
}
