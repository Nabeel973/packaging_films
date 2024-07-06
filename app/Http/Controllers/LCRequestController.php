<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Document;
use App\Models\Supplier;
use App\Models\LCRequest;
use Illuminate\Http\Request;
use App\Models\LCRequestJourney;
use Yajra\DataTables\DataTables;
use App\Models\AmendmentLCRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\LCRequestStatusEmailJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Notifications\LCRequestStatusUpdateEmail;
use App\Http\Controllers\LCRequestJourneyController;

class LCRequestController extends Controller
{

    public function index(){
       
        return view('lc_requests.index');
    }

    public function list(){

        if(\request()->ajax()){
            $users = LCRequest::join('users','users.id','lc_request.created_by')
                ->join('lc_request_status','lc_request_status.id','lc_request.status_id')
                ->join('suppliers','suppliers.id','lc_request.supplier_id')
                ->leftjoin('users as u','u.id','lc_request.updated_by')
                ->select('lc_request.*','users.name as created_by','lc_request_status.name as status','suppliers.name as supplier_name','u.name as updated_by');

                $users = $users->get();
                
            return DataTables::of($users)
                // ->addIndexColumn()
                ->editColumn('priority', function ($row) {
                    return $row->priority == 1 ? 'High' : 'Low';
                })
                ->editColumn('draft_required', function ($row) {
                    return $row->draft_required == 1 ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($row){
                    $actionBtn = '
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="'.$row->id.'">Edit</a>';
                    
                    if (in_array(session('role_id'),[1,3]) && $row->priority == 0) {
                        $actionBtn .= '<a class="dropdown-item set-priority-high" href="javascript:void(0)" data-id="'.$row->id.'">Set Priority High</a>';
                    }

                   
                    $actionBtn .= '<a class="dropdown-item view-logs" href="javascript:void(0)" data-id="'.$row->id.'">View Logs</a>';
                    
                   
                    $amendment_request = AmendmentLCRequest::where('lc_request_id',$row->id)->latest()->first();

                    if ( in_array(session('role_id'),[1,5]) && $row->status_id == 10 && (!$amendment_request || ($row->amendment_request_count > 0 && $amendment_request->status_id == 10 ))) {
                        $actionBtn .= '<a class="dropdown-item amendment-request" href="javascript:void(0)" data-id="'.$row->id.'">Add Amendment Request</a>';
                    }
    

                    $actionBtn .= '
                        </div>
                    </div>';
                    
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
     }

    public function add(){
        $supplier_names = Supplier::all();
        return view('lc_requests.add',compact('supplier_names'));
    }

    public function submit(Request $request){
       
        $validator = Validator::make($request->all(), [
            'shipment_name' => 'required|string|max:255',
            'supplier' => 'required|integer',
            'payment_terms' => 'required|string',
            'performa_invoice' => 'required|max:1024',
            'document_1' =>'max:1024',
            'document_2' =>'max:1024',
            'document_3' =>'max:1024',
            'document_4' =>'max:1024',
            'document_5' =>'max:1024',
        ]);

          // Check if validation fails
          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $lc_request = new LCRequest();
        $lc_request->shipment_name = $request->shipment_name;
        $lc_request->supplier_id = $request->supplier;
        $lc_request->item_name = $request->item_name;
        $lc_request->quantity = $request->item_quantity;
        $lc_request->payment_terms = $request->payment_terms;
        $lc_request->draft_required = ($request->draft_required == 'on') ? 1 : 0;
        $lc_request->created_by = Auth::user()->id;
        $lc_request->status_id = 1;
        $lc_request->save();

        $document = new Document();
        $document->lc_request_id = $lc_request->id;
        $document->save();

        LCRequestController::uploadDocuments($request,$document,"performa_invoice","performa_invoices",$lc_request->id); //adds performa invoice
        LCRequestController::uploadDocuments($request,$document,"document_1","documents",$lc_request->id); //adds performa document1
        LCRequestController::uploadDocuments($request,$document,"document_2","documents",$lc_request->id); //adds performa document2
        LCRequestController::uploadDocuments($request,$document,"document_3","documents",$lc_request->id); //adds performa document3
        LCRequestController::uploadDocuments($request,$document,"document_4","documents",$lc_request->id); //adds performa document4
        LCRequestController::uploadDocuments($request,$document,"document_5","documents",$lc_request->id); //adds performa document5

        LCRequestJourneyController::add($lc_request->id,Auth::id(),1,Carbon::now());
        LCRequestStatusEmailJob::dispatch($lc_request);
         // Redirect to a specific route with success message
        return redirect()->route('lc_request.index')->with('status', 'Request generated successfully.');
    }

    public function edit($id){
        $lcRequest = LCRequest::find($id);
        $supplier_names = Supplier::all();
        $disable = true;

        if((session('role_id') == 1 && $lcRequest->status_id == 1) || (session('role_id') == 5 && in_array($lcRequest->status_id,[3,5])))
        {  
            $disable = false;
        }
        return view('lc_requests.edit',compact('supplier_names','lcRequest','disable'));
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());
        $lcRequest = LcRequest::find($id);

        if ($request->input('action') == 'approve') {
            // Handle approval logic
            $lcRequest->status_id = 2;
            $lcRequest->reason_code = null;
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->save();

            LCRequestJourneyController::add($lcRequest->id,Auth::id(),2,Carbon::now());
            
            LCRequestStatusEmailJob::dispatch($lcRequest);
            
            return redirect()->route('lc_request.index')->with('status', 'LC Request approved successfully!');
        }

        if ($request->input('action') == 'next') {
            // Handle approval logic

            if($lcRequest->draft_required == 1){
                $lcRequest->status_id = 8;
            }
            
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->save();

            LCRequestJourneyController::add($lcRequest->id,Auth::id(),$lcRequest->status_id,Carbon::now());
            
            LCRequestStatusEmailJob::dispatch($lcRequest);
            
            return redirect()->route('lc_request.index')->with('status', 'LC Request status updated successfully!');
        }

        if ($request->input('action') == 'transmit') {
            // Handle approval logic

            $lcRequest->status_id = 9;
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->save();

            LCRequestJourneyController::add($lcRequest->id,Auth::id(),$lcRequest->status_id,Carbon::now());
            
            LCRequestStatusEmailJob::dispatch($lcRequest);
            
            return redirect()->route('lc_request.index')->with('status', 'LC Request status updated successfully!');
        }
        

        // Handle update logic
        else{

            $lcRequest->shipment_name = $request->input('shipment_name');
            $lcRequest->supplier_id = $request->input('supplier');
            $lcRequest->item_name = $request->input('item_name');
            $lcRequest->quantity = $request->input('item_quantity');
            $lcRequest->payment_terms = $request->input('payment_terms');
            $lcRequest->draft_required = $request->input('draft_required', false);
            $lcRequest->reason_code = null;
            if($lcRequest->status_id == 5){    //disperency identified
                $lcRequest->status_id = 6;  //disperency removed 
            }
            else{
                $lcRequest->status_id = 4;  //adjusted
            }
            
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->draft_required = ($request->draft_required == 'on') ? 1 : 0;
            $lcRequest->save();

            if($lcRequest->documents){
                $document = $lcRequest->documents;
            }
            else{
                $document = new Document();
                $document->lc_request_id = $request->id;
            }

            LCRequestController::uploadDocuments($request,$document,"performa_invoice","performa_invoices",$lcRequest->id); //adds performa invoice
            LCRequestController::uploadDocuments($request,$document,"document_1","documents",$lcRequest->id); //adds performa document1
            LCRequestController::uploadDocuments($request,$document,"document_2","documents",$lcRequest->id); //adds performa document2
            LCRequestController::uploadDocuments($request,$document,"document_3","documents",$lcRequest->id); //adds performa document3
            LCRequestController::uploadDocuments($request,$document,"document_4","documents",$lcRequest->id); //adds performa document4
            LCRequestController::uploadDocuments($request,$document,"document_5","documents",$lcRequest->id); //adds performa document5
    
            LCRequestStatusEmailJob::dispatch($lcRequest);
            return redirect()->route('lc_request.index')->with('status', 'LC Request updated successfully!');
        }
      
    }

    public function rejectReason(Request $request){
        
        $lc_request = LCRequest::find($request->lc_request_id);
        $status_id = 0;

        if(Auth::user()->role_id == 3){
            $status_id = 3;
        }
        else if(Auth::user()->role_id == 4){
            $status_id = 5;
        }
        
        $lc_request->status_id = $status_id;
        $lc_request->reason_code = $request->reason;
        $lc_request->updated_by = Auth::id();
        $lc_request->updated_at = Carbon::now();
        $lc_request->save();

        LCRequestJourneyController::add($lc_request->id,Auth::id(),3,Carbon::now(),$request->reason);

        LCRequestStatusEmailJob::dispatch($lc_request);

         return redirect()->route('lc_request.index')->with('status', 'LC Request rejected successfully!');
    }

    public function setPriority(Request $request){

        $lc_request_id = $request->input('lc_request_id');

        // Find the LC request by ID and update the value
        $lcRequest = LCRequest::find($lc_request_id);

        if ($lcRequest) {
            // Perform the update operation
            $lcRequest->priority = 1; // Example update, change as needed
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function applyForBank(Request $request){

        $validator = Validator::make($request->all(), [
            'lc_request_id' => 'required|integer',
            'bank_name' => 'required|string|max:255',
            'bank_document' => 'required|max:1024',
        ]);

          // Check if validation fails
          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $lcRequest = LCRequest::find($request->input('lc_request_id'));
       
        if ($lcRequest) {

            if($lcRequest->documents){
                $document = $lcRequest->documents;
            }
            else{
                $document = new Document();
                $document->lc_request_id =$request->lc_request_id;
            }
            
            $document->bank_name = $request->bank_name;
            $document->save();

            LCRequestController::uploadDocuments($request,$document,"bank_document","documents",$lcRequest->id); //adds performa document1

            $lcRequest->reason_code = null;
            $lcRequest->status_id = 7;
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->save();

            return redirect()->back()->with('status', 'Applied for bank successfully!');
        }

        return redirect()->back()->with('error', 'Error applying for bank!');
    }

    public function applyForTransit(Request $request){

        $validator = Validator::make($request->all(), [
            'lc_request_id' => 'required|integer',
            'lc_number' => 'required|string|max:255',
            'transmited_lc_document' => 'required|max:1024',
        ]);

          // Check if validation fails
          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $lc_request_id = $request->input('lc_request_id');

        // Find the LC request by ID and update the value
        $lcRequest = LCRequest::find($lc_request_id);

        if ($lcRequest) {
            // Perform the update operation
            
            if($lcRequest->documents){
                $document = $lcRequest->documents;
            }
            else{
                $document = new Document();
                $document->lc_request_id =$request->lc_request_id;
            }

            $document->transmited_lc_number = $request->lc_number;
            $document->save();

            LCRequestController::uploadDocuments($request,$document,"transmited_lc_document","documents",$lcRequest->id); //adds performa document1

            $lcRequest->reason_code = null;
            $lcRequest->status_id = 10;
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->save();

            return redirect()->back()->with('status', 'Transited Successfully!');
        }

        return redirect()->back()->with('error', 'Error applying for bank!');
    }

    public static function uploadDocuments(Request $request, $document, $field_name, $directory_name,$lc_request_id)
    {
        if ($request->hasFile($field_name)) {
            $uploadedDocument = $request->file($field_name);
            $documentName = time() . '.' . $field_name . '.' . $uploadedDocument->getClientOriginalExtension();
            $documentDirectory = $directory_name . '/' . $lc_request_id;  // Assuming $document has an id field
            $documentPath = $uploadedDocument->storeAs($documentDirectory, $documentName);

            // Delete the old file if it exists
            if ($document->$field_name) {
                Storage::delete($document->$field_name);
            }

            // Update the document field with the new path
            $document->$field_name = $documentPath;
            $document->save();
        }
    }

    public function viewLogs($id){
        return view('lc_requests.logs',compact('id'));
    }

    public function getLogs(Request $request){
        $lc_request_logs = LCRequestJourney::join('lc_request','lc_request_journey.lc_request_id','lc_request.id')
                    ->join('users','users.id','lc_request_journey.user_id')
                    ->join('lc_request_status','lc_request_status.id','lc_request_journey.status_id','')
                    ->leftJoin('amendment_lc_request','amendment_lc_request.id','lc_request_journey.amendment_request_id')
                    ->leftJoin('lc_request_status as lcs','lcs.id','lc_request_journey.amendment_request_status_id')
                    ->select('lc_request_journey.id as id','lc_request.id as lc_request_id','lc_request_status.name as status','lc_request_journey.reason_code as reason','lc_request_journey.amendment_request_id as amendment_id','users.name as created_by','lc_request_journey.created_at','lcs.name as amendment_status')
                    ->where('lc_request.id',$request->id)
                    ->get();

            return DataTables::of($lc_request_logs)
                ->make(true);
    }
}


