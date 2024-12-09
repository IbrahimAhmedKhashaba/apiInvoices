<?php

namespace App\Http\Controllers;

use App\apiResponseTrait;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    //
    use apiResponseTrait;
    public function index(){
        $products = Product::with('section')->get();
        return $this->apiResponse(ProductResource::collection($products),'products retrieved successfully',200);
    }

    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = Product::find($request->id);
        if(!$product){
            return $this->apiResponse([], 'product not found' , 404);
        }
        return $this->apiResponse(new ProductResource($product) , 'product getted successfully' , 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'description' => 'required',
            'section_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        return $this->apiResponse(new ProductResource($product) , 'product created successfully' , code: 201);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|string|max:255',
            'description' => 'required',
            'section_id' => 'required',
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $product = Product::find($request->id);
        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $request->section_id,
        ]);

        return $this->apiResponse(new ProductResource($product) , 'product updated successfully' , code: 201);
    }

    public function destroy(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $product = Product::find($request->id);
        if(!$product){
            return $this->apiResponse([], 'product not found' , 404);
        }
        $product->delete();
        return $this->apiResponse([] , 'product deleted successfully' , 200);
    }
}
