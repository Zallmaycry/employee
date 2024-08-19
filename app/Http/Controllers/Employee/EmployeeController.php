<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $max_data = 10;

        if (request( 'search')){
            $data = employee::where('first_name', 'LIKE', '%' . request('search') . '%')->orWhere('last_name', 'LIKE', '%' . request('search') . '%')->paginate($max_data)->withQueryString();
        } else{
            $data = employee::orderBy('first_name','asc')->paginate($max_data);
        }
        return view('employee.app', compact('data'));
        
    }

    public function indexApi(Request $request)
    {
        $max_data = 10;

        if ($request->has('search')) {
            $data = Employee::where('first_name', 'LIKE', '%' . $request->search . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->search . '%')
                            ->paginate($max_data);
        } else {
            $data = Employee::orderBy('first_name', 'asc')->paginate($max_data);
        }

        // Return data as JSON response
        return response()->json([
            'status'=>true,
            'message'=>'Data ditemukan',
            'data'=>$data
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:20',
            'last_name' => 'required|min:3|max:20',
        ],[
            'first_name.required'=>'isian nama wajib diisikan',
            'last_name.required'=>'isian nama wajib diisikan',
            'first_name.min'=>'minimal isian first_name adalah 3 karakter',
            'last_name.min'=>'minimal isian last_name adalah 3 karakter',
            'first_name.max'=>'maksimal isian first_nama adalah 20 karakter',
            'last_name.max'=>'maksimal isian last_nama adalah 20 karakter',
        ]);

        $data = [
            'first_name'=> $request->input('first_name'),
            'last_name'=> $request->input('last_name'),
            'age'=> $request->input('age'),
            'position'=> $request->input('position'),
            'office'=> $request->input('office'),
            'start_date'=> $request->input('start_date'),
        ];
        

        employee::create($data);
        return redirect()->route('employee')->with('success','Berhasil Simpan Data');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'first_name' => 'required|min:3|max:20',
            'last_name' => 'required|min:3|max:20',
        ],[
            'first_name.required'=>'isian nama wajib diisikan',
            'last_name.required'=>'isian nama wajib diisikan',
            'first_name.min'=>'minimal isian first_name adalah 3 karakter',
            'last_name.min'=>'minimal isian last_name adalah 3 karakter',
            'first_name.max'=>'maksimal isian first_nama adalah 20 karakter',
            'last_name.max'=>'maksimal isian last_nama adalah 20 karakter',
        ]);

        
        $data = [
            'first_name'=> $request->input('first_name'),
            'last_name'=> $request->input('last_name'),
            'age'=> $request->input('age'),
            'position'=> $request->input('position'),
            'office'=> $request->input('office'),
            'start_date'=> $request->input('start_date'),
        ];

        employee::where('id',$id)->update($data);
        return redirect()->route('employee')->with('success','berhasil update');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        employee::where('id',$id)->delete();
        return redirect()->route('employee')->with('success','sukses menghapus data');
    }
}