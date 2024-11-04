<?php

namespace App\Http\Controllers;

use App\Helper;
use Carbon\Carbon;
use App\Models\Company;
use App\Models\Payment;
use App\Models\Currency;
use App\Models\Supplier;
use App\Models\RequestType;
use Illuminate\Http\Request;
use App\Models\ImportRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\ImportRequestJourney;
use Illuminate\Support\Facades\Auth;
use App\Models\ImportRequestAttachments;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ImportRequestJourneyController;

class ImportRequestController extends Controller
{
    
    public function index(){
        return view('import_requests.index');
    }

    protected function buildLcRequestQuery($request)
    {
        $import_requests = ImportRequest::join('import_request_statuses', 'import_request_statuses.id', 'import_requests.status_id')
                    ->join('companies', 'companies.id', 'import_requests.company_id')
                    ->join('suppliers', 'suppliers.id', 'import_requests.supplier_id')
                    ->join('currencies', 'currencies.id', 'import_requests.currency_id')
                    // ->leftJoin('documents as d', 'd.import_requests_id', 'import_requests.id')
                    ->select('import_requests.*','import_request_statuses.name as status','suppliers.name as supplier_name','currencies.name as currency_name',
                   'companies.name as company_name');

        // if ($request->filled('supplier_id')) {
        //     $import_requests->where('import_requests.supplier_id', (int) $request->supplier_id);
        // }
        // if ($request->filled('quantity_from')) {
        //     $import_requests->where('import_requests.quantity', '>=',(int) $request->quantity_from);
        // }
        // if ($request->filled('quantity_to')) {
        //     $import_requests->where('import_requests.quantity', '<=',(int) $request->quantity_to);
        // }
        // if ($request->filled('value_from')) {
        //     $import_requests->where('import_requests.amount', '>=', (double) $request->value_from);
        // }
        // if ($request->filled('value_to')) {
        //     $import_requests->where('import_requests.amount', '<=',(double) $request->value_to);
        // }
        // if ($request->filled('company_id')) {
        //     $import_requests->where('import_requests.company_id', $request->company_id);
        // }
        
        // if ($request->filled('date_range')) {
        //     [$start_date, $end_date] = explode(' - ', $request->date_range);
        //     $start_date = Carbon::parse($start_date)->startOfDay()->toDateTimeString();
        //     $end_date = Carbon::parse($end_date)->endOfDay()->toDateTimeString();
        //     $import_requests->whereBetween('import_requests.created_at', [$start_date, $end_date]);
        // }
        return $import_requests;
    }

    public function list(Request $request)
    {   
       
        $import_requests = $this->buildLcRequestQuery($request)
                        ->get();

        return DataTables::of($import_requests)
                // ->addIndexColumn()
                ->editColumn('priority', function ($row) {
                    return $row->priority == 1 ? 'High' : 'Normal';
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
                    
                        if (in_array(session('role_id'), [1, 3])) {
                            if ($row->priority == 0) {
                                $text = 'Set Priority High';
                            } else {
                                $text = 'Set Priority Normal';
                            }
                            $actionBtn .= '<a class="dropdown-item set-priority-high" href="javascript:void(0)" data-id="' . $row->id . '">' . $text . '</a>';
                        }
                   
                    $actionBtn .= '<a class="dropdown-item view-logs" href="javascript:void(0)" data-id="'.$row->id.'">View Logs</a>';

                    $actionBtn .= '
                        </div>
                    </div>';
                    
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);       
    }

    public function add(){
       
        $supplier_names = Supplier::where('status',1)->get();
        $currencies = Currency::all();
        $companies = Company::all();
        $payments = Payment::all();
        $request_types = RequestType::all();
        return view('import_requests.add',compact('supplier_names','currencies','companies','payments','request_types'));
    }

    public function submit(Request $request){
      
        try {

            $validator = Validator::make($request->all(), [
                'shipment_name' => 'required|string|max:255',
                'supplier' => 'required|integer',
                'company_id' => 'required|integer',
                'item_name' => 'required|string',
                'item_quantity' => 'required|integer',
                'request_type_id' => 'required|integer',
                'currency' => 'nullable|integer',
                'amount' => 'nullable|numeric',
                'invoice' => 'max:1024',
                'shipping_document' => 'max:1024',
                'paid_gd' => 'max:1024',
                'comments' => 'string|max:1024'
            ]);
    
              // Check if validation fails
              if ($validator->fails()) {
                return redirect()->back()
                                 ->withErrors($validator)
                                 ->withInput();
            }
    
            $import_request = new ImportRequest();
            $import_request->shipment_name = $request->shipment_name;
            $import_request->company_id = $request->company_id;
            $import_request->supplier_id = $request->supplier;
            $import_request->item_name = $request->item_name;
            $import_request->quantity = $request->item_quantity;
            $import_request->comments = $request->comments;
            $import_request->currency_id = $request->currency;
            $import_request->amount = $request->amount;
            $import_request->request_type_id = $request->request_type_id;
            $import_request->status_id = 1;
            $import_request->save();
    
            $document = new ImportRequestAttachments();
            $document->import_request_id = $import_request->id;
            $document->save();
    
            $invoice = $request->file('invoice');
            $shipping_document = $request->file('shipping_document');
            $paid_gd = $request->file('paid_gd');
            
            Helper::uploadDocuments($invoice,$document,"invoice","invoice",$import_request->id);
            Helper::uploadDocuments($shipping_document,$document,"shipping_document","shipping_document",$import_request->id);
            Helper::uploadDocuments($paid_gd,$document,"duty_paid_gd","paid_gd",$import_request->id);
    
            ImportRequestJourneyController::add($import_request->id,Auth::id(),1,null,null,$request->comments);
          
            return redirect()->route('import_requests.pending.index')->with('status', 'Request generated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An Error Occured.');
        }
    }

    public function viewLogs($id){
        return view('import_requests.logs',compact('id'));
    }

    public function getLogs(Request $request){
        $import_requests_logs = ImportRequestJourney::join('import_requests','import_request_journeys.import_request_id','import_requests.id')
                    ->join('users','users.id','import_request_journeys.user_id')
                    ->join('import_request_statuses','import_request_statuses.id','import_request_journeys.status_id')
                    ->join('currencies','currencies.id','import_requests.currency_id')
                    ->select('import_request_journeys.id as id','import_requests.id as import_requests_id','import_request_statuses.name as status','import_request_journeys.reason_code as reason','users.name as created_by','import_request_journeys.created_at as created_at','import_request_journeys.comments as comments')
                    ->where('import_requests.id',$request->id)
                    ->get();

            return DataTables::of($import_requests_logs)
                ->make(true);
    }


    public function setPriority(Request $request){

        $import_request_id = $request->input('import_request_id');

        // Find the LC request by ID and update the value
        $import_request = ImportRequest::find($import_request_id);

        if ($import_request) {
            // Perform the update operation
            $priority = ($import_request->priority == 0) ? 1 : 0;
            $import_request->priority = $priority; // Example update, change as needed
            $import_request->updated_at = Carbon::now();
            $import_request->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function edit($id){
        $importRequest = ImportRequest::find($id);
        $supplier_names = Supplier::where('status',1)->get();
        $disable = true;
        $currencies = Currency::all();
        $companies = Company::all();
        $request_types = RequestType::all();

        if(
            (in_array(session('role_id'),[1,5]) && in_array($importRequest->status_id,[1,4])) || 
            (session('role_id') == 5 && in_array($importRequest->status_id,[3,5]))
        )
        {  
            $disable = false;
        }
        return view('import_requests.edit',compact('supplier_names','importRequest','disable','currencies','companies','request_types'));
    }

    public function update(Request $request, $id)
    {
       
        $importRequest = ImportRequest::find($id);

        $comments = null;
        if($importRequest->comments != $request->comments){
            $comments = $request->comments;
        }

        $importRequest->comments = $request->comments;

        if ($request->input('action') == 'approve') {
            // Handle approval logic
            $importRequest->status_id = 2;
            $importRequest->reason_code = null;
            $importRequest->updated_at = Carbon::now();
            $importRequest->save();

            LCRequestJourneyController::add($importRequest->id,Auth::id(),2,Carbon::now(),null,null,null,$comments);
            
            // LCRequestStatusEmailJob::dispatch($importRequest);
            
            return redirect()->route('lc_request.pending.index')->with('status', 'LC Request approved successfully!');
        }

        if ($request->input('action') == 'next') {
            // Handle approval logic

            if($importRequest->draft_required == 1){
                $importRequest->status_id = 8;
            }
            
            $importRequest->updated_at = Carbon::now();
            $importRequest->save();

            LCRequestJourneyController::add($importRequest->id,Auth::id(),$importRequest->status_id,Carbon::now(),null,null,null,$comments);
            
            // LCRequestStatusEmailJob::dispatch($importRequest);
            
            return redirect()->route('lc_request.pending.index')->with('status', 'LC Request status updated successfully!');
        }

        if ($request->input('action') == 'transmit') {
            // Handle approval logic

            $importRequest->status_id = 9;
            $importRequest->updated_at = Carbon::now();
            $importRequest->save();

            LCRequestJourneyController::add($importRequest->id,Auth::id(),$importRequest->status_id,Carbon::now(),null,null,null,$comments);
            
            // LCRequestStatusEmailJob::dispatch($importRequest);
            
            return redirect()->route('lc_request.pending.index')->with('status', 'LC Request status updated successfully!');
        }
        

        // Handle update logic
        else{
            $validator = Validator::make($request->all(), [
                'shipment_name' => 'required|string|max:255',
                'supplier' => 'required|integer',
                'company_id' => 'required|integer',
                'payment_id' => 'required|integer',
                'currency' => 'required|integer',
                'amount' => 'required|numeric',
                'performa_invoice' => 'max:1024',
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


            $importRequest->shipment_name = $request->input('shipment_name');
            $importRequest->supplier_id = $request->input('supplier');
            $importRequest->company_id = $request->input('company_id');
            $importRequest->item_name = $request->input('item_name');
            $importRequest->quantity = $request->input('item_quantity');
            $importRequest->payment_id = $request->input('payment_id');
            $importRequest->draft_required = $request->input('draft_required', false);
            $importRequest->currency_id = $request->input('currency');
            $importRequest->amount = $request->input('amount');
            $importRequest->reason_code = null;
            if($importRequest->status_id == 5){    //disperency identified
                $importRequest->status_id = 6;  //disperency removed 
            }
            else{
                $importRequest->status_id = 4;  //adjusted
            }
            
            $importRequest->updated_by = Auth::id();
            $importRequest->updated_at = Carbon::now();
            $importRequest->draft_required = ($request->draft_required == 'on') ? 1 : 0;
            $importRequest->save();

            if($importRequest->documents){
                $document = $importRequest->documents;
            }
            else{
                $document = new Document();
                $document->lc_request_id = $request->id;
            }

            LCRequestController::uploadDocuments($request,$document,"performa_invoice","performa_invoices",$importRequest->id); //adds performa invoice
            LCRequestController::uploadDocuments($request,$document,"document_1","documents",$importRequest->id); //adds performa document1
            LCRequestController::uploadDocuments($request,$document,"document_2","documents",$importRequest->id); //adds performa document2
            LCRequestController::uploadDocuments($request,$document,"document_3","documents",$importRequest->id); //adds performa document3
            LCRequestController::uploadDocuments($request,$document,"document_4","documents",$importRequest->id); //adds performa document4
            LCRequestController::uploadDocuments($request,$document,"document_5","documents",$importRequest->id); //adds performa document5
    
            // LCRequestStatusEmailJob::dispatch($importRequest);
            LCRequestJourneyController::add($importRequest->id,Auth::id(),$importRequest->status_id,Carbon::now(),null,null,null,$comments);
           
            return redirect()->route('lc_request.pending.index')->with('status', 'LC Request updated successfully!');
        }
      
    }

    public function rejectReason(Request $request){
        
        $importRequest = ImportRequest::find($request->import_request);
        $journeyController = ImportRequestJourneyController::class; // or another controller
        $journeyMethod = 'add'; // specify the method dynamically if needed
        $emailJob = null; // or another job class

    Helper::rejectReason($importRequest, $request->reason, $journeyController, $journeyMethod,$emailJob);
    }
}
