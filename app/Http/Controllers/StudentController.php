<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function index()
    {
        return view('student');
    }

    public function allData()
    {
        $data = Student::orderBy('id','DESC')->get();
        return response()->json($data);
    }
    
    public function storeData(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'surname'=>'required',
            'univercity'=>'required',
        ]);

        $data = Student::insert([
           'name'=>$request->name,
           'surname'=>$request->surname,
           'univercity'=>$request->univercity,
        ]);

        return response()->json($data);
    }

    //-----------------edit Student

    public function editData($id)
    {
       $data = Student::findOrFail($id);
       return response()->json($data);
    }

    public function updateData(Request $request,$id)
    {
        $request->validate([
            'name'=>'required',
            'surname'=>'required',
            'univercity'=>'required',
        ]);

          $data = Student::findOrFail($id)->update([
           'name'=>$request->name,
           'surname'=>$request->surname,
           'univercity'=>$request->univercity,
        ]);
          
           return response()->json($data);

    }

    public function deleteData($id)
    {
         Student::find($id)->delete($id);
  
    return response()->json([
        'success' => 'Record deleted successfully!'
    ]);
        
    }
}
