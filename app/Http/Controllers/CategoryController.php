<?php

namespace App\Http\Controllers;

use App\Models\Category;
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
        }catch (\Throwable $e){
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
        }catch (\Throwable $e){
            return $this->error("Category is not updated");
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
        }catch (\Throwable $e){
            return $this->error("Category is not deleted");
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
