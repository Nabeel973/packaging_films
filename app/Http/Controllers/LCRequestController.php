<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Supplier;
use App\Models\LCRequest;
use Illuminate\Http\Request;
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
        $users = LCRequest::join('users','users.id','lc_request.created_by')
                
                ->join('lc_request_status','lc_request_status.id','lc_request.status_id')
                ->join('suppliers','suppliers.id','lc_request.supplier_id')
                ->leftjoin('users as u','u.id','lc_request.updated_by')
                ->select('lc_request.*','users.name as created_by','lc_request_status.name as status','suppliers.name as supplier_name','u.name as updated_by')
                ->get();
        return response()->json($users);
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

        if ($request->hasFile('performa_invoice')) {
            $invoice = $request->file('performa_invoice');
            $invoiceName = time() . '.' . $invoice->getClientOriginalExtension();
            $invoiceDirectory = 'performa_invoices/' .  $lc_request->id;

            // Delete the previous invoice if it exists
            if ($lc_request->performa_invoice) {
                Storage::delete($lc_request->performa_invoice); // Assuming the 'local' disk
            }

            // Store the new invoice in the user's directory
            $invoicePath = $invoice->storeAs($invoiceDirectory, $invoiceName);
            $lc_request->performa_invoice = $invoicePath;
        }

        if ($request->hasFile('other_document')) {
            $document = $request->file('other_document');
            $documentName = time() . '.' . $document->getClientOriginalExtension();
            $documentDirectory = 'document/' .  $lc_request->id;

            // Delete the previous document if it exists
            if ($lc_request->document) {
                Storage::delete($lc_request->document); // Assuming the 'local' disk
            }

            // Store the new document in the user's directory
            $documentPath = $document->storeAs($documentDirectory, $documentName);
            $lc_request->document = $documentPath;
        }

        $lc_request->save();

        LCRequestJourneyController::add($lc_request->id,Auth::id(),1,Carbon::now());
        LCRequestStatusEmailJob::dispatch($lc_request);
         // Redirect to a specific route with success message
         return redirect()->route('lc_request.index')->with('status', 'Request generated successfully.');
    }

    public function edit($id){
        $lcRequest = LCRequest::find($id);
        $supplier_names = Supplier::all();
        $disable = true;

        if((session('role_id') == 1 && $lcRequest->status_id == 1) || (session('role_id') == 5 && $lcRequest->status_id == 3))
        {  
            $disable = false;
        }
        return view('lc_requests.edit',compact('supplier_names','lcRequest','disable'));
    }

    public function update(Request $request, $id)
    {
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

        // Handle update logic
        else{
            $lcRequest->shipment_name = $request->input('shipment_name');
            $lcRequest->supplier_id = $request->input('supplier');
            $lcRequest->item_name = $request->input('item_name');
            $lcRequest->quantity = $request->input('item_quantity');
            $lcRequest->payment_terms = $request->input('payment_terms');
            $lcRequest->draft_required = $request->input('draft_required', false);
            $lcRequest->reason_code = null;
            $lcRequest->status_id = 4;
            $lcRequest->updated_by = Auth::id();
            $lcRequest->updated_at = Carbon::now();
            $lcRequest->draft_required = ($request->draft_required == 'on') ? 1 : 0;
            $lcRequest->save();

            if ($request->hasFile('performa_invoice')) {
                $invoice = $request->file('performa_invoice');
                $invoiceName = time() . '.' . $invoice->getClientOriginalExtension();
                $invoiceDirectory = 'performa_invoices/' .  $lcRequest->id;
    
                // Delete the previous invoice if it exists
                if ($lcRequest->performa_invoice) {
                    Storage::delete($lcRequest->performa_invoice); // Assuming the 'local' disk
                }
    
                // Store the new invoice in the user's directory
                $invoicePath = $invoice->storeAs($invoiceDirectory, $invoiceName);
                $lcRequest->performa_invoice = $invoicePath;
            }
    
            if ($request->hasFile('other_document')) {
                $document = $request->file('other_document');
                $documentName = time() . '.' . $document->getClientOriginalExtension();
                $documentDirectory = 'document/' .  $lcRequest->id;

                // Delete the previous document if it exists
                if ($lcRequest->document) {
                    Storage::delete($lcRequest->document); // Assuming the 'local' disk
                }

                // Store the new document in the user's directory
                $documentPath = $document->storeAs($documentDirectory, $documentName);
                $lcRequest->document = $documentPath;
            }
    
            $lcRequest->save();

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

}
