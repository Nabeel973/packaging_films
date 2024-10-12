<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ShipmentType;
use Illuminate\Http\Request;
use App\Models\ClearanceRequest;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ClearanceRequestJourney;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\LCRequestController;
use App\Http\Controllers\ClearanceRequestJourneyController;

class ShipmentClearanceController extends Controller
{
    public function index(){
        return view('clearance_request.index');
    }

    public function list(){
        
        $clearance_request = ClearanceRequest::join('lc_request','lc_request.id','clearance_requests.lc_request_id')
        ->join('currencies','currencies.id','lc_request.currency_id')
        ->join('clearance_request_statuses','clearance_request_statuses.id','clearance_requests.status_id')
        ->select('clearance_requests.*','clearance_requests.id as cl_id','lc_request.*','currencies.name as currency','clearance_request_statuses.name as status','clearance_request_statuses.id as status_id')->get();

        return DataTables::of($clearance_request)
        ->addColumn('action', function ($row) use ($clearance_request) {
            $actionBtn = '
            <div class="btn-group">
                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Actions
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="'.$row->cl_id.'">Edit</a>';
        
            if ($row->status_id == 1 && in_array(Auth::user()->role_id, [1, 5])) {
                $actionBtn .= '<a class="dropdown-item shipment_arrived" href="javascript:void(0)" data-id="'.$row->cl_id.'" >Shipment Arrived</a>';
            } 
            else if ($row->status_id == 2 && in_array(Auth::user()->role_id, [1, 4])) {
                $actionBtn .= '<a class="dropdown-item shipment_arrived" href="javascript:void(0)" data-id="'.$row->cl_id.'">Documents Released</a>';
            }

            $actionBtn .= '<a class="dropdown-item view-logs" href="javascript:void(0)" data-id="'.$row->cl_id.'">View Logs</a>';
        
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
        $clearance_request->created_at = Carbon::now();
        $clearance_request->save();

        LCRequestController::uploadDocuments($request,$clearance_request,"document","clearance_documents",$id); 
        LCRequestController::uploadDocuments($request,$clearance_request,"picture","clearance_documents",$id); 
        ClearanceRequestJourneyController::add($clearance_request->id,Auth::user()->id,1,Carbon::now()); 

        return redirect()->route('clearance_request.index')->with('status', 'Clearance Request generated successfully.');

    }


    public function edit($id){

        $shipment_types = ShipmentType::all();
        $clearnace_request = ClearanceRequest::find($id);
        $disable = (in_array(Auth::user()->role_id,[1,5]) && $clearnace_request->status_id == 1) ? false : true;
        return view('clearance_request.edit',['shipment_types'=> $shipment_types,'id' => $id,'clearnace_request' => $clearnace_request,'disable' => $disable]);
    }

    public function update(Request $request, $id){
     
        $validator = Validator::make($request->all(), [
            'bill_number' => 'required|string|max:255',
            'shipment_type_id' => 'required|integer|exists:shipment_types,id',
            'tax' => 'required|numeric', 
            'shipping_document' =>'max:1024',
            'screenshot' =>'max:1024',
        ]);

          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $clearance_request = ClearanceRequest::find($id);
        $clearance_request->lc_request_id = $id;
        $clearance_request->bill_number = $request->bill_number;
        $clearance_request->shipment_type_id = $request->shipment_type_id;
        $clearance_request->tax = $request->tax;
        $clearance_request->tracking_number = $request->tracking_number;
        $clearance_request->shipment_date = $request->shipment_date;
        $clearance_request->expected_arrival_date = $request->shipment_arrival_date;
        $clearance_request->status_id = 1;
        $clearance_request->updated_at = Carbon::now();
        $clearance_request->save();

        LCRequestController::uploadDocuments($request,$clearance_request,"document","clearance_documents",$id); 
        LCRequestController::uploadDocuments($request,$clearance_request,"picture","clearance_documents",$id); 

        return redirect()->route('clearance_request.index')->with('status', 'Clearance Request updated successfully.');

    }

    public function status_update(Request $request){
        $clearnace_request_id = $request->input('clearnace_request_id');
        $clearnace_request = ClearanceRequest::find($clearnace_request_id);

        if ($clearnace_request) {
          
            $status_id = ($clearnace_request->status_id == 1 && in_array(Auth::user()->role_id, [1, 5])) ? 2 : 3;
            if($status_id == 2){
                $clearnace_request->actual_arrival_date = Carbon::now();
            }
            $clearnace_request->status_id = $status_id;
            $clearnace_request->updated_at = Carbon::now();
            $clearnace_request->save();

            ClearanceRequestJourneyController::add($clearnace_request->id,Auth::user()->id,$status_id,Carbon::now()); 

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function view_logs($id){
        return view('clearance_request.logs',compact('id'));
    }

    public function log_list(){
        $clearance_request_journey = ClearanceRequestJourney::join('clearance_requests','clearance_requests.id','clearance_request_journeys.clearance_id')
        ->join('users','users.id','clearance_request_journeys.user_id')
        ->join('clearance_request_statuses','clearance_request_statuses.id','clearance_request_journeys.status_id')
        ->select('clearance_request_journeys.*','clearance_request_statuses.name as status','users.name as user')->get();
        
        return DataTables::of($clearance_request_journey)->make(true);
    }
}
