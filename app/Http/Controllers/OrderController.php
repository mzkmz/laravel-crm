<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->tokenCan('employees_permissions'))
        {
            return Order::paginate(10);
        }
        if(auth()->user()->tokenCan('customer_permissions'))
        {
            return Order::where('customer_id',auth()->user()->id)->paginate(10);
        }

        abort(403, 'Unauthorized');
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
        if(!auth()->user()->tokenCan('customer_permissions'))
        {
            abort(403, 'Unauthorized');            
        }
        $attribute = request()->validate([
            'customer_id' => 'required',
            'order_date' => 'required|date',
            'required_date' => 'required|date',
            'status' => 'required',
            'comments' => 'required|max:255',
        ]);        


        $items_id = [];
        foreach(request()->products as $product)
        {
            $items_id[] = $product["product_id"];
        }
        
        $order_products = Product::whereIn('id', $items_id)->get();
        for ($i = 0; $i < count($order_products); $i++) {
            if(!($order_products[$i]["qty_in_stock"] >= request()->products[$i]["quantity"]))
            {
                return "Dont have enough products";
            }
        }
        for ($i = 0; $i < count($order_products); $i++) {
            $order_products[$i]->update(['qty_in_stock'=>$order_products[$i]["qty_in_stock"] - request()->products[$i]["quantity"]]);            
        }
        $order = Order::create($attribute);
        $order->products()->attach(request()->products);
        
        // if($order){
        //     $products = request()->products;
        //     $values = [];
        //     foreach($products as $product)
        //     {
        //         $values[$product["id"]] = ['quantity'=>$product["quantity"]];
        //     }
        //     $order->products()->sync($values);
        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        // $o = Order::find($order);
        if((auth()->user()->tokenCan('employee_permissions') && auth()->user()->id == $order->customer->employee->id) || auth()->user()->id == $order->customer_id)
        {            
            return $order->with('products');
        }
        abort(403, 'Unauthorized');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order, Request $request)
    {
        // $order = Order: 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {        
        if(!auth()->user()->tokenCan('employee_permissions') || auth()->user()->id !== $order->customer_id)
        {
            abort(403, 'Unauthorized');
        }        
        $o = Order::find($order->id);
        $o->update($request->json()->all());

        if($request->status == 2)
        {
            $date = now();
            $o->update(['shipped_date'=>$date]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if(auth()->user()->tokenCan('employee_permissions') || auth()->user()->id == $order->customer_id)
        {            
            return Order::destroy($order);
        }
        abort(403, 'Unauthorized');
    }
}
