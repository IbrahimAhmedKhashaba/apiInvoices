<?php

namespace App\Http\Controllers;

use App\apiResponseTrait;
use App\Http\Middleware\JwtMiddleware;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use App\Models\InvoicesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class InvoiceController extends Controller
{
    //
    use apiResponseTrait;
    public function index(){
        $invoices = Invoice::with('section')->get();
        return $this->apiResponse(InvoiceResource::collection($invoices) , 'Invoices data' , 200);
    }

    public function show(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $invoice = Invoice::with('invoicesDetail')->with('invoicesAttachments')->find($request->id);
        if(!$invoice){
            return $this->apiResponse([], 'invoice not found' , 404);
        }
        return $this->apiResponse(new InvoiceResource($invoice) , 'invoice getted successfully' , 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|string',
            'invoice_date' => 'required',
            'due_date' => 'required',
            'product' => 'required|string',
            'section_id' => 'required|string',
            'amount_collection' => 'required',
            'amount_commission' => 'required',
            'discount' => 'required',
            'value_vat' => 'required',
            'rate_vat' => 'required',
            'total' => 'required',
            'payment_date' => 'required',
            'note' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section_id,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'payment_date' => $request->payment_date,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;

        $InvoicesDetail = InvoicesDetail::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section_id,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => auth()->user(),
        ]);

        if ($request->hasFile('pic')) {
            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new InvoicesAttachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = 1;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }
        return $this->apiResponse('' , 'invoice created successfully' , 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'invoice_number' => 'required|string',
            'invoice_date' => 'required',
            'due_date' => 'required',
            'product' => 'required|string',
            'section_id' => 'required|string',
            'amount_collection' => 'required',
            'amount_commission' => 'required',
            'discount' => 'required',
            'value_vat' => 'required',
            'rate_vat' => 'required',
            'total' => 'required',
            'payment_date' => 'required',
            'status' => 'required',
            'note' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        if($request->status == 'مدفوعة جزئيا'){
            $value_status = 1;
        } else if($request->status == 'مدفوعة'){
            $value_status = 0;
        }
        $invoice = Invoice::find($request->id);
        $invoice->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'product' => $request->product,
            'section_id' => $request->section_id,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'value_vat' => $request->value_vat,
            'rate_vat' => $request->rate_vat,
            'total' => $request->total,
            'status' => $request->status,
            'value_status' => $value_status,
            'payment_date' => $request->payment_date,
            'note' => $request->note,
        ]);

        $invoice_id = Invoice::latest()->first()->id;

        InvoicesDetail::create([
            'invoice_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->section_id,
            'status' => $request->status,
            'value_status' => $value_status,
            'note' => $request->note,
            'user' => auth()->user(),
        ]);
        return $this->apiResponse('' , 'invoice updated successfully' , 201);
    }

    public function destroy(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $invoice = Invoice::find($request->id);
        if(!$invoice){
            return $this->apiResponse([], 'invoice not found' , 404);
        }
        $invoice->delete();
        return $this->apiResponse([] , 'invoice deleted successfully' , 200);
    }

    public function archive(){
        $invoices = Invoice::onlyTrashed()->with('section')->get();
        return $this->apiResponse(InvoiceResource::collection($invoices) , 'Invoices data' , 200);
    }

    public function restore(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $invoice = Invoice::onlyTrashed()->find($request->id);
        if(!$invoice){
            return $this->apiResponse([], 'invoice not found' , 404);
        }
        $invoice->restore();
        return $this->apiResponse([] , 'invoice restored successfully' , 200);
    }
}
