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
            Log::error("Import Request Save Failed: " . $e->getMessage() . 'additional_info' . $e);
        }
    }
}
