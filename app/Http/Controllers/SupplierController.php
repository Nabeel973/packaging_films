<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(){
        return view('supply_management.index'); 
     }
 
     public function list(){
        
         $supplier = Supplier::get();
        //  return response()->json($supplier);
        return DataTables::of($supplier)
        ->editColumn('status',function($row){
            if ($row->status == 1) {
                return '<span class="badge badge-success">Active</span>';
            } else {
                return '<span class="badge badge-danger">Inactive</span>';
            }
        })
        ->addColumn('action', function ($row){
            // Determine the status text
            $text = ($row->status == 1) ? 'Set Inactive' : 'Set Active';
            
            $actionBtn = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="'.$row->id.'">Edit</a>
                    <a class="dropdown-item status-btn" href="javascript:void(0)" data-id="' . $row->id . '">' . $text . '</a>
                </div>
            </div>';
            
            return $actionBtn;
        })
        ->rawColumns(['action','status'])
        ->make(true);
      }
 
      public function add(){
         return view('supply_management.add');
      }

      public function submit(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Create a new supplier
        $user = Supplier::create([
            'name' => $request->name,
        ]);

        // Redirect to a specific route with success message
        return redirect()->route('supplier.index')->with('status', 'Supplier created successfully.');
    }

    public function edit($id){
        $supplier = Supplier::find($id);
        return view('supply_management.edit',compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        // Fetch the user to be updated
        $supplier = Supplier::find($id);
    
        // Validate the request
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);
    
        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }
    
        // Update user details
        $supplier->name = $request->name;
        $supplier->save();
    
        // Redirect to a specific route with success message
        return redirect()->route('supplier.index')->with('status', 'Supplier updated successfully.');
    }

    public function updateStatus($id){
      
        if($id){
            $supplier = Supplier::find($id);
            $supplier->status = ($supplier->status == 1) ? 0 : 1;
            $supplier->save();
            return response()->json(['success' => true]);
           
        }
        return response()->json(['success' => false, 'message' => 'Supplier not found']);
    }
}
