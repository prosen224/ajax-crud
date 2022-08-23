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
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'phone' => 'required|max:191',
            'image' => 'required|image|mimes:jpg, png, jpeg, svg, gif',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages()
            ]);
        }
        
        else{
            $employee = new Employee();
            $employee->name = $request->name;
            $employee->phone = $request->phone;

            if($request->hasfile('image')){
                $file = $request->file('image');
                $filename = time().'.'. $file->getClientOriginalExtension();
                $file->move('uploads/employee', $filename);
                $employee->image = $filename;
            }
            
            $employee->save();


            return response()->json([
                'status'=> 200,
                'message' => 'Employee added successfully'
            ]);
        }
    }

}
