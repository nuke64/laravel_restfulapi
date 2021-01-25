<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Carbon;

class ProductController extends Controller
{
    const MSG_PRODUCT_NOT_FOUND = 'Product not found';
    const MSG_CATEGORY_NOT_FOUND = 'Category not found';

    public function index()
    {
        $products = Product::all();
        $result = $this->postprocess($products);
        return response()->json($this->addStatus($result, 'success'), 200);
    }

    public function show($arg)
    {
        $products = null;
        if (is_numeric($arg)) {
            $id = $arg;
            $products = Product::where('id', $id)->get();
        }else if (is_string($arg)){
            if ($arg==='active'){
                $products = Product::where('is_active', true)->get();
            }elseif($arg==='published'){
                $products = Product::where('is_published', true)->get();
            }else {
                $name = $arg;
                $products = Product::where('name', $name)->orWhere('name', 'like', '%' . $name . '%')->get();
            }
        }else{
            return $this->error('Invalid type, only strings or number(id) are allowed');
        }

        if (!$products->isEmpty()){
            $result = $this->postprocess($products);
            return response()->json($this->addStatus($result, 'success'), 200);
        }else{
            return $this->error(self::MSG_PRODUCT_NOT_FOUND);
        }
    }

    public function showByCategoryIdOrName($arg)
    {
        if (is_numeric($arg)) {
            $id = $arg;
            $category = Category::find($id);
            if (isset($category)) {
                $products = $category->products()->get();
                if (!$products->isEmpty()) {
                    $result = $this->postprocess($products);
                    return response()->json($this->addStatus($result, 'success'), 200);
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
                return response()->json($this->addStatus($result, 'success'), 200);
            }else{
                return $this->error("No category by given name '$name' is found");
            }
        }else{
            return $this->error('Invalid type, only strings or number(id) are allowed');
        }
    }

    public function showByPrice($min=null, $max=null)
    {
        if (is_null($min)){
            return $this->error('Min argument is not defined');
        }
        if (is_null($max)){
            return $this->error('Max argument is not defined');
        }
        if (!is_numeric($min)){
            return $this->error('Min argument must be numeric types');
        }
        if (!is_numeric($max)) {
            return $this->error('Max argument must be numeric types');
        }

        // swap min/max
        if ($min > $max){
            $temp = $min;
            $min = $max;
            $max = $temp;
        }

        $products = Product::where('price', '>=', $min)->where('price', '<=', $max)->get();

        if (!$products->isEmpty()){
            $result = $this->postprocess($products);
            return response()->json($this->addStatus($result, 'success'), 200);
        }else{
            return $this->error("Products with price between [$min,$max] not found ");
        }
    }

    public function store(Request $req)
    {
        try {
            //$product = Product::create($req->all()); // We use attribute mutators then use 'save()' instead of 'create()'
            $product = new Product();
            $product->fill($req->all());
            // fill other attributes
            if (isset($req['is_active'])) {
                $product->is_active = $req->boolean('is_active');
            }
            if (isset($req['is_published'])) {
                $product->is_published = $req->boolean('is_published');
            }

            if (isset($req['category_ids'])) {
                $ids = array_unique(json_decode($req['category_ids']));
                if (count($ids) <2 || count($ids)>10 ){
                    return $this->error("The count of ID's from 'category_ids' must be >=2 and <=10");
                }
                $product->save(); // to get Id for make relation
                $product->categories()->attach($ids);
            }else{
                return $this->error("'category_ids' is not present");
            }

            $result = array($product);
            return response()->json($this->addStatus($result, 'success'), 201);
        }catch (\Throwable $e){
            return $this->error("Product is not added");
        }
    }

    public function update(Request $req, $id /*Product $product*/) // NOTE: I use $id, not Product model class because I don't want get 404 error by updating non-existing product, this error cannot be catched in try/catch scope
    {
        try {
            $product = Product::findOrFail($id);
            $product->fill($req->all());
            // fill other attributes
            if (isset($req['is_active'])) {
                $product->is_active = $req->boolean('is_active');
            }
            if (isset($req['is_published'])) {
                $product->is_published = $req->boolean('is_published');
            }
            if (isset($req['category_ids'])) {
                $ids = array_unique(json_decode($req['category_ids']));
                if (count($ids) <2 || count($ids)>10 ){
                    return $this->error("The count of ID's from 'category_ids' must be >=2 and <=10");
                }
                $product->categories()->detach(); // detach all
                $product->categories()->attach($ids);
            }else{
                // skip, without changes
            }

            $product->save();

            //$product->update($req->all()); // We use attribute mutators then use 'save()' instead of 'create()'
            $result = array($product);
            return response()->json($this->addStatus($result, 'success'), 200);
        }catch (\Throwable $e){
            return $this->error("Product is not updated");
        }
    }

    public function delete(Request $req, $id /*Product $product*/) // NOTE: I use $id, not Product model class because I don't want get 404 error by deleting non-xisting product, this error cannot be catched in try/catch scope
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->is_active) { // prevent double product 'delete' to save DB load
                $product->is_active = false;
                $product->deleted_at = Carbon::now();
                $product->save();
            }
            $result = array($product);
            return response()->json($this->addStatus($result, 'success'), 200);
        }catch (\Throwable $e){
            return $this->error("Product is not deleted");
        }
    }

    /* Protected functions
    TODO: refac: move into base class
     */

    protected function postprocess($source)
    {
        $result = array();
        foreach ($source as $p) {
            $result[$p['id']]=$p;
        }
        return $result;
    }

    protected function error($msg)
    {
        return response()->json($this->addStatus(array(), 'failed', $msg), 404 );
    }

    protected function addStatus($data, $status, $msg = null)
    {
        return array_merge($data,
            [
                'status' => $status,
                'message' => $msg
            ]
        );
    }
}
