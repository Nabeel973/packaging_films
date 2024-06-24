<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Supplier;
use App\Models\LCRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
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
                ->select('lc_request.*','users.name as created_by','lc_request_status.name as status','suppliers.name as supplier_name')
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

         // Redirect to a specific route with success message
         return redirect()->route('lc_request.index')->with('status', 'Request generated successfully.');
    }

    public function edit($id){
        $lcRequest = LCRequest::find($id);
        $supplier_names = Supplier::all();
        $disable = false;
        if(session('role_id') == 2 && $lcRequest->status_id == 1){
            $disable = true;
        }
        return view('lc_requests.edit',compact('supplier_names','lcRequest','disable'));
    }

    public function update(Request $request, $id)
    {
        $lcRequest = LcRequest::find($id);

        if ($request->input('action') == 'approve') {
            // Handle approval logic
            $lcRequest->status_id = 3;
            $lcRequest->save();
            LCRequestJourneyController::add($lc_request->id,Auth::id(),3,Carbon::now());
            return redirect()->route('lc_request.index')->with('status', 'LC Request approved successfully!');
        }

        // Handle update logic
        else{
            $lcRequest->shipment_name = $request->input('shipment_name');
            $lcRequest->supplier_id = $request->input('supplier');
            $lcRequest->item_name = $request->input('item_name');
            $lcRequest->item_quantity = $request->input('item_quantity');
            $lcRequest->payment_terms = $request->input('payment_terms');
            $lcRequest->draft_required = $request->input('draft_required', false);
    
            if ($request->hasFile('performa_invoice')) {
                // Handle file upload
                $lcRequest->performa_invoice = $request->file('performa_invoice')->store('invoices');
            }
    
            if ($request->hasFile('other_document')) {
                // Handle file upload
                $lcRequest->other_document = $request->file('other_document')->store('documents');
            }
    
            $lcRequest->save();
    
            return redirect()->route('lc_request.index')->with('status', 'LC Request updated successfully!');
        }
      
    }

    public function rejectReason(Request $request){

        $lc_request = LCRequest::find($request->lc_request_id);
        $status_id = 0;

        if($lc_request->status_id == 1){
            $status_id = 3;
        }
        
        $lc_request->status_id = $status_id;
        $lc_request->reason_code = $request->reason;
        $lc_request->save();

         LCRequestJourneyController::add($lc_request->id,Auth::id(),3,Carbon::now(),$request->reason);

         return redirect()->route('lc_request.index')->with('status', 'LC Request rejected successfully!');
    }

}
