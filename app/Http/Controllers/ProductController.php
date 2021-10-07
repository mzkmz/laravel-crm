<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::paginate(10);
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
    public function store()
    {
        if(auth()->user()->tokenCan('employees_permissions'))
        {
            $attribute = request()->validate([
                'category_id' => 'required',
                'name' => 'required|max:255',
                'scale' => 'required',
                'pdt_description' => 'required|max:255',
                'qty_in_stock' => 'required',
                'price' => 'required',
            ]);
            Product::create($attribute);
        }
        abort(403, 'Unauthorized');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::findorFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        if(auth()->user()->tokenCan('employees_permissions'))
        {
            $p = Product::find($product->id);
            $p->update($request->json()->all());
        }
        abort(403, 'Unauthorized');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if(auth()->user()->tokenCan('employees_permissions'))
        {
            return Product::destroy($product);
        }
        abort(403, 'Unauthorized');
    }
}
