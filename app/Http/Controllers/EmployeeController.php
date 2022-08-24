<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use File;
use Validator;

class EmployeeController extends Controller
{
    public function index(){
        return view('index');
    }

    public function store(Request $request){
        
    }

}
