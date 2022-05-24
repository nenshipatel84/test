<?php

namespace App\Http\Controllers;
use App\Models\Employee;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    //
    public function index(){
        $data['employees']  = Employee::paginate(5);
        return view("employee.index",$data);
    }

    public function create(){
        return view("employee.add");
    }
    
    public function store(Request $request){
        
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'phone_no'=>'required',
        ]);
        
        $fileName = time().'.'.$request->file->extension();  
   
        $path = $request->file->move(public_path('uploads'), $fileName);
 
        Employee::create([
            'name' => $request->name,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'profile' => $fileName,
        ]);

        return redirect()->route('employees.index')
            ->with('success','Employee has been created successfully.');

    }

    public function show(Employee $employee)
    {
        return view('employee.show',compact('employee'));
    } 

    public function edit(Employee $employee){
        return view ("employee.edit",compact('employee'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'phone_no'=>'required',
        ]);
        
        // $name = $request->file('file')->getClientOriginalName();
 
        // $path = $request->file('file')->store('public/files');
 
        $employee = Employee::find($id);
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->phone_no = $request->phone_no;
        // $employee->profile = $path;

        $employee->save();

        return redirect()->route('employees.index')
            ->with('success','Employee has been edited successfully.');
    }

    public function delete_employee(Employee $employee,$id){
        // dd($id);
        Employee::where("id",$id)->delete();
        $employee->delete();
        return redirect()->route('employees.index')
            ->with('success','Employee has been deleted successfully.');
    }


}
