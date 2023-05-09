<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    //
    private $category;
    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getCategories(){
        return $this->category->getCategories();
    }

    public function getCategory(Request $request){
        return $this->category->getCategory($request);
    }

    public function deleteCategory(Request $request){
        return $this->category->deleteCategory($request);
    }

    public function updateSort($request){
        return $this->category->updateSort($request);
    }

    public function createCategory(Request $request)
    {
        if($request->input("isSubCategory")=='true' || $request->input("isSubCategory")=='1')
        {
            $validar = [
                "name" => "required|unique:categories,name,".$request->input("id"),
                "fatherCategory" => "required"
            ];
        }
        else{
            $validar = [
                "name" => "required|unique:categories,name,".$request->input("id"),
            ];
        }

        $validator = Validator::make($request->all(), $validar);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }

        return $this->category->createCategory($request);
    }

    public function updateCategory(Request $request)
    {
        if($request->input("isSubCategory")=='true' || $request->input("isSubCategory")=='1')
        {
            $validar = [
                "name" => "required|unique:categories,name,".$request->input("id"),
                "fatherCategory" => "required"
            ];
        }
        else{
            $validar = [
                "name" => "required|unique:categories,name,".$request->input("id"),
            ];
        }

        $validator = Validator::make($request->all(), $validar);

        if ($validator->fails()) {
            return response()->json([
                'errors' =>$validator->errors(),
            ], 422);
        }

        return $this->category->updateCategory($request);
    }

}
