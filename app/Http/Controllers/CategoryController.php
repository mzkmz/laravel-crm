<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::paginate(10);
    }

    public function show($id)
    {
        return Category::findorFail($id);
    }

    public function store()
    {
        $attribute = request()->validate([
            'description' => 'required|max:255|min:3|unique:categories,description',
        ]);

        if(Category::create($attribute))
        {
            return true;
        }        
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $category->update($request->json()->all());
    }

    public function destroy(Category $category)
    {
        return Category::destroy($category);
    }
}
