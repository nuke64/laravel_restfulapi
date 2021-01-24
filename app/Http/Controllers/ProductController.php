<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class ProductController extends Controller
{
    const MSG_PRODUCT_NOT_FOUND = 'Product not found';
    const MSG_CATEGORY_NOT_FOUND = 'Category not found';

    public function showAll(){
        $products = Product::all();
        $result = $this->postprocess($products);
        return response()->json($result, 200);
    }

    public function show($arg){ // Id,
        $products = null;
        if (is_numeric($arg)) {
            $id = $arg;
            $products = Product::where('id', $id)->get();
        }else if (is_string($arg)){
            if ($arg==='active'){
                $products = Product::where('active', true)->get();
            }elseif($arg==='published'){
                $products = Product::where('published', true)->get();
            }else {
                $name = $arg;
                $products = Product::where('name', $name)->orWhere('name', 'like', '%' . $name . '%')->get();
            }
        }else{
            return $this->error('Invalid type, only strings or number(id) are allowed');
        }

        if (!$products->isEmpty()){
            $result = $this->postprocess($products);
            return response()->json($result, 200);
        }else{
            return $this->error(self::MSG_PRODUCT_NOT_FOUND);
        }
    }

    public function showByCategoryIdOrName($arg){
        if (is_numeric($arg)) {
            $id = $arg;
            $category = Category::find($id);
            if (isset($category)) {
                $products = $category->products()->get();
                if (!$products->isEmpty()) {
                    $result = $this->postprocess($products);
                    return response()->json($result, 200);
                } else {
                    return $this->error(self::MSG_PRODUCT_NOT_FOUND);
                }
            } else {
                return $this->error(self::MSG_CATEGORY_NOT_FOUND);
            }
        }else if (is_string($arg)){
            $name = $arg;
            $categoryIds = Category::where('name', $name)->orWhere('name', 'like', '%'.$name.'%')->pluck('id'); // DB request #1

            if (!$categoryIds->isEmpty()) {
                $result = array();

                $categories  = Category::whereIn('id', $categoryIds)->with('products')->get(); // DB request #2 (better to do only one request, but i made 2)
                // postprocessing: populate final array
                foreach ($categories as $c) {
                    foreach ($c['products'] as $p) {
                        $result[$p['id']]=$p;
                    }
                }
                return response()->json($result, 200);
            }else{
                return $this->error("No category by given name '$name' is found");
            }
        }else{
            return $this->error('Invalid type, only strings or number(id) are allowed');
        }
    }

    public function showByPrice($min, $max){
        if (is_numeric($min) && is_numeric($max)) {
            // swap min/max
            if ($min > $max){
                $temp = $min;
                $min = $max;
                $max = $temp;
            }

            $products = Product::where('price', '>=', $min)->where('price', '<=', $max)->get();

            if (!$products->isEmpty()){
                $result = $this->postprocess($products);
                return response()->json($result, 200);
            }else{
                return $this->error("Products with price between [$min,$max] not found ");
            }
        }else{
            return $this->error('Min/Max must be numeric types');
        }
    }

    protected function postprocess($source){
        $result = array();
        foreach ($source as $p) {
            $result[$p['id']]=$p;
        }
        return $result;
    }

    protected function error($msg)
    {
        return response()->json(
            [
                'status' => 'failed',
                'message' => $msg
            ],
            404
        );
    }
}
