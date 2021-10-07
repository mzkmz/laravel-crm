<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Http\Traits;
// use Laravel\Sanctum\HasApiTokens;
use App\Http\Traits\EmployeesTrait;

class Employee extends Model
{
    use HasFactory;
    use EmployeesTrait;

    protected $guarded = [];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'sales_rep_employee');
    }


}
