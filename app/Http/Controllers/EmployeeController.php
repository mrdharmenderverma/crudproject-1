<?php

namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;


class EmployeeController extends Controller
{
    public function index(){

        $employees = Employee::orderBy('id','DESC')->paginate(5     );
       return view('employee.list',['employees' => $employees]);
    }

    public function create(){
       return view('employee.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'image' => 'sometimes|image:gif,png,jpeg,jpg'
        ]);

        if ($validator->passes()){

            //Option #1
            //save data here
            // $employee = new Employee();
            // $employee->name = $request->name;
            // $employee->email = $request->email;
            // $employee->address = $request->address;

            //Option #2
            // $employee->save();
            // $request->session()-flash('success', 'Employee added successfully.');
            
            //Option #3
            $employee = Employee::create($request->post());

            //Upload image here

            if($request->image){
                $text = $request->image->getClientOriginalExtension();
                $newFileName = time().'.'.$text;
                $request->image->move(public_path().'/uploads/employees/',$newFileName); //This will save file in the folder

                $employee->image = $newFileName;
                $employee->save();

            }

            return redirect()->route('employees.index')->with('success','Employee updated successfully.');           
        }else{
            //return with errors
            return redirect()->route('employees.create')->withErrors($validator)->withInput();
        }
    }

    public function edit($id){
        $employee = Employee::findOrFail($id);       
        
        return view('employee.edit',['employee'=>$employee]);

    }

    public function update(Employee $employee, Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required',
            'image' => 'sometimes|image:gif,png,jpeg,jpg'
        ]);

        if ($validator->passes()){

            //Option #1
            //save data here
            // $employee = Employee::find($id);
            // $employee->name = $request->name;
            // $employee->email = $request->email;
            // $employee->address = $request->address;
            // $employee->save();

            // $employee = new Employee();
            // $employee->fill($request->post())->save();
            // $request->session()-flash('success', 'Employee added successfully.');

            //Option #2
           $employee->fill($request->post())->save();

            //Upload image here

            if($request->image){
                $oldImage = $employee->image;
                $text = $request->image->getClientOriginalExtension();
                $newFileName = time().'.'.$text;
                $request->image->move(public_path().'/uploads/employees/',$newFileName); //This will save file in the folder

                $employee->image = $newFileName;
                $employee->save();

                File::delete(public_path().'/uploads/employees/'.$oldImage);

            }

           

            return redirect()->route('employees.index')->with('success','Employee added successfully.');           
        }else{
            //return with errors
            return redirect()->route('employees.edit',$employee->id)->withErrors($validator)->withInput();
        }
    }

    public function destroy(Employee $employee, Request $request){
        // $employee = Employee::findOrFail($id);
        
        File::delete(public_path().'/uploads/employees/'.$employee->image);

        $employee->delete();
        return redirect()->route('employees.index')->with('success','Employee deleted successfully.');

    }

    
}