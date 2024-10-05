<?php

namespace App\Http\Controllers;

use App\Models\ShipmentType;
use Illuminate\Http\Request;
use App\Models\ClearanceRequest;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\LCRequestController;

class ShipmentClearanceController extends Controller
{
    public function index(){
        return view('clearance_request.index');
    }

    public function list(){
        
        $clearance_request = ClearanceRequest::join('lc_request','lc_request.id','clearance_requests.lc_request_id')
        ->join('currencies','currencies.id','lc_request.currency_id')
        ->select('clearance_requests.*','clearance_requests.id as cl_id','lc_request.*','currencies.name as currency')->get();

        return DataTables::of($clearance_request)
    //     ->addColumn('link', function($row) {
    //        $url = route('lc_request.edit', ['id' => $row->lc_request_number]);
    //        return '<a href="'.$url.'">'.$row->lc_request_number.'</a>';
    //    })
       ->addColumn('action', function ($row){
           $actionBtn = '
           <div class="btn-group">
               <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   Actions
               </button>
               <div class="dropdown-menu">
                   <a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="'.$row->cl_id.'">Edit</a>';
           

           $actionBtn .= '
               </div>
           </div>';
           
           return $actionBtn;
       })
       ->rawColumns(['action','link'])
       ->make(true);

        
    }

    public function add($id){
        $shipment_types = ShipmentType::all();
        return view('clearance_request.add',['shipment_types'=> $shipment_types,'id' => $id]);
    }

    public function submit(Request $request, $id){
     
        $validator = Validator::make($request->all(), [
            'bill_number' => 'required|string|max:255',
            'shipment_type_id' => 'required|integer|exists:shipment_types,id',
            'tax' => 'required|numeric', 
            'shipping_document' =>'max:1024',
            'screenshot' =>'max:1024',
        ]);

          // Check if validation fails
          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $clearance_request = new ClearanceRequest();
        $clearance_request->lc_request_id = $id;
        $clearance_request->bill_number = $request->bill_number;
        $clearance_request->shipment_type_id = $request->shipment_type_id;
        $clearance_request->tax = $request->tax;
        $clearance_request->tracking_number = $request->tracking_number;
        $clearance_request->shipment_date = $request->shipment_date;
        $clearance_request->expected_arrival_date = $request->shipment_arrival_date;
        $clearance_request->created_by = Auth::user()->id;
        $clearance_request->status_id = 1;
        $clearance_request->save();

        LCRequestController::uploadDocuments($request,$clearance_request,"document","clearance_documents",$id); 
        LCRequestController::uploadDocuments($request,$clearance_request,"picture","clearance_documents",$id); 

        return redirect()->route('clearance_request.index')->with('status', 'Clearance Request generated successfully.');


    }
}
