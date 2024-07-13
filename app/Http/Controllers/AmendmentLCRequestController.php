<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\LCRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\AmendmentLCRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Jobs\LCRequestStatusEmailJob;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\LCRequestController;
use App\Jobs\LCAmendmentRequestStatusEmailJob;
use App\Http\Controllers\LCRequestJourneyController;

class AmendmentLCRequestController extends Controller
{
    public function index(){
       
        return view('amendment_request.index');
    }

    public function list(){

        if(\request()->ajax()){
            $amendment_lc_request = AmendmentLCRequest::join('lc_request','lc_request.id','amendment_lc_request.lc_request_id')
                ->join('lc_request_status','lc_request_status.id','amendment_lc_request.status_id')
                ->join('currencies','currencies.id','lc_request.currency_id')
                ->leftjoin('users as u','u.id','amendment_lc_request.updated_by')
                ->leftjoin('documents as d','d.lc_request_id','lc_request.id')
                ->select('lc_request.id as lc_request_number','lc_request.shipment_name as shipment_name','lc_request_status.name as status','amendment_lc_request.*','u.name as updated_by','currencies.name as currency_name','lc_request.amount as amount','d.bank_name as bank_name','d.transmited_lc_number as lc_number');

                $amendment_lc_request = $amendment_lc_request->get();
              
            return DataTables::of($amendment_lc_request)
                // ->addColumn('lc_number', function($row) {
                //     $url = route('lc_request.edit', ['id' => $row->lc_request_number]);
                //     return '<a href="'.$url.'">'.$row->lc_request_number.'</a>';
                // })
                // ->addColumn('link', '<a href="#">Html Column</a>')
                 ->addColumn('link', function($row) {
                    $url = route('lc_request.edit', ['id' => $row->lc_request_number]);
                    return '<a href="'.$url.'">'.$row->lc_request_number.'</a>';
                })
                ->addColumn('action', function ($row){
                    $actionBtn = '
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item edit-btn" href="javascript:void(0)" data-id="'.$row->id.'">Edit</a>';
                    
    
                    $actionBtn .= '
                        </div>
                    </div>';
                    
                    return $actionBtn;
                })
                ->rawColumns(['action','link'])
                ->make(true);
        }
    }

    
    public function add($id){
        return view('amendment_request.add',compact('id'));
    }

    public function submit(Request $request,$id){
       
        $validator = Validator::make($request->all(), [
            'details' => 'required|string|max:255',
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

        $amendment_lc_request = new AmendmentLCRequest();
        $amendment_lc_request->details = $request->details;
        $amendment_lc_request->lc_request_id = $id;
        $amendment_lc_request->created_by = Auth::user()->id;
        $amendment_lc_request->status_id = 11;
        $amendment_lc_request->save();

        $lc_request = $amendment_lc_request->lcRequest;
        $lc_request->amendment_request_count = 1;
        $lc_request->save();


        LCRequestController::uploadDocuments($request,$amendment_lc_request,"performa_invoice","amendment_performa_invoices", $amendment_lc_request->id); //adds performa invoice
        LCRequestController::uploadDocuments($request,$amendment_lc_request,"document_1","amendment_documents",$amendment_lc_request->id); //adds performa document1
        LCRequestController::uploadDocuments($request,$amendment_lc_request,"document_2","amendment_documents",$amendment_lc_request->id); //adds performa document2
        LCRequestController::uploadDocuments($request,$amendment_lc_request,"document_3","amendment_documents",$amendment_lc_request->id); //adds performa document3
        LCRequestController::uploadDocuments($request,$amendment_lc_request,"document_4","amendment_documents",$amendment_lc_request->id); //adds performa document4
        LCRequestController::uploadDocuments($request,$amendment_lc_request,"document_5","amendment_documents",$amendment_lc_request->id); //adds performa document5

        LCRequestJourneyController::add($id,Auth::id(),$lc_request->status_id,Carbon::now(),NULL,$amendment_lc_request->id,11);
        LCAmendmentRequestStatusEmailJob::dispatch($amendment_lc_request);
        // LCRequestStatusEmailJob::dispatch($lc_request);
         // Redirect to a specific route with success message
        return redirect()->route('amendment_request.index')->with('status', 'Amendment Request generated successfully.');
    }

    public function edit($id){
        $amendmentLcRequest = AmendmentLCRequest::find($id);
       
        $disable = true;

        // if(in_array(session('role_id'),[1,5]) && $amendmentLcRequest->status_id == 11)
        // {  
        //     $disable = false;
        // }
        return view('amendment_request.edit',compact('amendmentLcRequest','disable'));
    }

    public function applyForBank(Request $request){

        $validator = Validator::make($request->all(), [
            'amendment_lc_request_id' => 'required|integer',
            'bank_name' => 'required|string|max:255',
            'bank_document' => 'required|max:1024',
        ]);

          // Check if validation fails
          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $amendment = AmendmentLCRequest::find($request->input('amendment_lc_request_id'));
       
        if ($amendment) {

            $amendment->bank_name = $request->bank_name;
            $amendment->status_id = 7;
            $amendment->updated_by = Auth::id();
            $amendment->updated_at = Carbon::now();
            $amendment->save();

            LCRequestController::uploadDocuments($request,$amendment,"bank_document","amendment_documents",$amendment->id); //adds bank  documents

            LCRequestJourneyController::add($amendment->lcRequest->id,Auth::id(),$amendment->lcRequest->status_id,Carbon::now(),NULL,$amendment->id,7);
            
            LCAmendmentRequestStatusEmailJob::dispatch($amendment);
            
            return redirect()->back()->with('status', 'Applied for bank successfully!');
        }

        return redirect()->back()->with('error', 'Error applying for bank!');
    }

    public function applyForTransit(Request $request){

        $validator = Validator::make($request->all(), [
            'amendment_lc_request_id' => 'required|integer',
            // 'lc_number' => 'required|string|max:255',
            'transmited_lc_document' => 'required|max:1024',
        ]);

          // Check if validation fails
          if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        $amendment_id = $request->input('amendment_lc_request_id');

        // Find the LC request by ID and update the value
        $amendment = AmendmentLCRequest::find($amendment_id);

        if ($amendment) {
            // Perform the update operation
            // $amendment->transmited_lc_number = $request->lc_number;
            $amendment->status_id = 10;
            $amendment->updated_by = Auth::id();
            $amendment->updated_at = Carbon::now();
            $amendment->save();

            LCRequestController::uploadDocuments($request,$amendment,"transmited_lc_document","amendment_documents",$amendment->id);

            LCRequestJourneyController::add($amendment->lcRequest->id,Auth::id(),$amendment->lcRequest->status_id,Carbon::now(),NULL,$amendment->id,10);

            LCAmendmentRequestStatusEmailJob::dispatch($amendment);

            return redirect()->back()->with('status', 'Transited Successfully!');
        }

        return redirect()->back()->with('error', 'Error applying for bank!');
    }


}
