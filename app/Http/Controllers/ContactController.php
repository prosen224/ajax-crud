<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Validator;

class ContactController extends Controller
{
    public function index(){
        return view('index');
    }

    public function store(Request $request){

        $validated = Validator::make($request->all(), [
            'name'=> 'required',
            'phone'=> 'required|numeric'
        ]);

        if($validated->fails()){
            return response()->json([
                'status' => 400,
                'errors'=> $validated->errors()
            ]);

        }else{
            $contactData = [
                'name'=>$request->name,
                'phone'=>$request->phone,
            ];

            Contact::create($contactData);
            return response()->json([
                'status' => 200,
            ]);
        }

    }

    public function fetchAll(){
        $contacts = Contact::all();
        $output = '';
        if($contacts->count() > 0){
            $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';

            foreach($contacts as $key => $contact){
                $serial = $key + 1;
                $output .= '<tr>
                    <td>'. $serial .'</td>
                    <td>'. $contact->name .'</td>
                    <td>'. $contact->phone .'</td>
                    <td>
                        <a href="#" id="' . $contact->id . '" class="text-success mx-1 editIcon" data-bs-toggle="modal" data-bs-target="#editContactModal"><i class="bi-pencil-square h4"></i></a>

                        <a href="#" id="' . $contact->id . '" class="text-danger mx-1 deleteIcon"><i class="bi-trash h4"></i></a>
                    </td>
                </tr>';
            }
            $output .= '<tbody>
            <table>';
            echo $output;
        }
        else {
			echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
		}
    }

    public function edit(Request $request){
        $id = $request->id;
        $contact = Contact::find($id);
        return response()->json($contact);

    }

    public function update(Request $request){
        $contact = Contact::find($request->contact_id);

        $validated = Validator::make($request->all(), [
            'name'=> 'required',
            'phone'=> 'required|numeric'
        ]);

        if($validated->fails()){
            return response()->json([
                'status' => 400,
                'errors'=> $validated->errors()
            ]);

        }else{
            $contactData = [
                'name'=>$request->name,
                'phone'=>$request->phone,
            ];
            $contact->update($contactData);
            return response()->json([
                'status' => 200,
            ]);
        }
    }

    public function delete(Request $request){
        $id = $request->id;
        $contact = Contact::find($id);
        Contact::destroy($id);
    }

}
