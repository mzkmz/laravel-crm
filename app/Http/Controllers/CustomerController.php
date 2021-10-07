<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        if(!auth()->user()->tokenCan('employee_permissions'))
        {
            abort(403, 'Unauthorized');
        }
        return Customer::with('orders')->paginate(10);
    }

    public function show($id)
    {
        if(auth()->user()->tokenCan('employee_permissions') || auth()->user()->id == $id)
        {
            return Customer::findOrFail($id)->load('orders');
        }
        abort(403, 'Unauthorized');
    }

    public function store()
    {

        $attribute = request()->validate([
            'sales_rep_employee' => 'required',
            'name' => 'required|max:255',
            'phone_number' => 'required|max:255',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'state' => 'required|max:255',
            'postal_code' => 'required|max:255',
            'credit_limit' => 'required',
        ]);

        if(Customer::create($attribute))
        {
            return true;
        }
    }

    public function update(Request $request, $id)
    {
        $c = Customer::find($id);
        if(auth()->user()->tokenCan('employee_permissions') || auth()->user()->id == $c->id)
        {            
            return $c->update($request->json()->all());
        }
        abort(403, 'Unauthorized');
    }

    public function destroy(Customer $customer)
    {
        if(auth()->user()->tokenCan('employee_permissions') || auth()->user()->id == $customer->id)
        {            
            return Customer::destroy($customer);
        }
        abort(403, 'Unauthorized');
    }
}
