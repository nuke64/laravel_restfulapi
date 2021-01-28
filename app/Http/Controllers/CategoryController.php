<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $products = Category::all();
        $result = $this->postprocess($products);
        return response()->json($this->addStatus($result, 'success'), 200);
    }

    public function store(Request $req)
    {
        try {
            $category = new Category();
            $category->create($req->all());

            return response()->json($this->addStatus(array(), 'success'), 201);
        }catch (\Exception $e){
            return $this->error("Category is not added");
        }
    }

    public function update(Request $req, $id )
    {
        try {
            $category = Category::findOrFail($id);
            $category->update($req->all());
            $result = array($category);
            return response()->json($this->addStatus($result, 'success'), 200);
        }catch (ModelNotFoundException $e){
            return $this->error("Category update error (not found)");
        }catch (\Exception $e){
            return $this->error("Category is not updated (other error)");
        }
    }

    public function delete(Request $req, $id)
    {
        try {
            if (!Category::where('id', $id)->exists()){
                return $this->error('Category does not exists');
            }

            $productsCount  = count(Category::where('id', $id)->with('products')->get()[0]['products']);
            if ($productsCount > 0) {
                return $this->error("Category cannot be deleted because it have $productsCount linked products");
            }
            $category = Category::find($id);
            $result = array($category);
            $category->delete();
            return response()->json($this->addStatus($result, 'success'), 200);
        }catch (ModelNotFoundException $e){
            return $this->error("Category delete error (not found)");
        }catch (\Exception $e){
            return $this->error("Category is not deleted (other error)");
        }
    }


    /* Protected functions */
    protected function postprocess($source)
    {
        $result = array();
        foreach ($source as $p) {
            $result[$p['id']]=$p;
        }
        return $result;
    }

}
