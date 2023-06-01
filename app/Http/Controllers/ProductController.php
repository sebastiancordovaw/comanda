<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getProducts(Request $resource,$id=null){

        if($id)
        {
            return  $this->product->getProducts($id);
        }

        return Product::select('id','name','price')
        ->where('name', 'like', '%'.$resource->search.'%')
        ->where('status', '=', 1)
        ->get();
    }

    public function getProductsCategory($id)
    {
        $this->product->getProductsCategory($id);
    }

    public function getCategoriesTree()
    {
        return $this->product->categoriesTree();
    }

    public function addProducto(Request $request)
    {
        $validar = [
            "name" => "required|unique:products",
            "category_id" => "required",
            "price" => "required|numeric|max_digits:6"
        ];

        $validator = Validator::make($request->all(), $validar);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }

        return $this->product->addProducto($request);
    }

    public function updateProducto(Request $request)
    {
        $validar = [
            "name" => "required|unique:products,name,".$request->input("id"),
            "category_id" => "required",
            "price" => "required|numeric|max_digits:6"
        ];

        $validator = Validator::make($request->all(), $validar);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }

        return $this->product->updateProducto($request);
    }

    public function deleteProduct(Request $request)
    {
        return $this->product->deleteProduct($request);
    }

    public function updateSortProduct($request)
    {
        return $this->product->updateSortProduct($request);
    }
}
