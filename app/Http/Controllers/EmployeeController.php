<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        return Employee::with('customers')->paginate(10);
    }

    public function show($id)
    {
        return Employee::findOrFail($id);
    }

    public function store()
    {
        $attribute = request()->validate([
            'office_code' => 'required',
            'reports_to' => 'required',
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'extension' => 'required|max:255',
            'email' => 'required|email',
            'job_title' => 'required|max:255',
        ]);

        if(Employee::create($attribute))
        {
            return true;
        }
    }

    public function update(Request $request, Employee $employee)
    {
        $e = Employee::find($employee->id);
        $e->update($request->json()->all());
    }

    public function destroy($id)
    {
        return Employee::destroy($id);
    }
}
