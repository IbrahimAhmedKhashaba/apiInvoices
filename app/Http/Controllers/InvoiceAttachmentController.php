<?php

namespace App\Http\Controllers;

use App\apiResponseTrait;
use App\Models\Invoice;
use App\Models\InvoicesAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceAttachmentController extends Controller
{
    //
    use apiResponseTrait;
    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|string',
            'invoice_number' => 'required|string',
            'pic' => 'required',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;
            $attachments = new InvoicesAttachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = auth()->user()->id;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
            return $this->apiResponse('' , 'invoice Attachment created successfully' , 201);
        }


        public function destroy(Request $request){
            $validator = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }

            $invoice = InvoicesAttachment::find($request->id);
            if(!$invoice){
                return $this->apiResponse([], 'invoice attachment not found' , 404);
            }
            $invoice->delete();
            return $this->apiResponse([] , 'invoice attachment deleted successfully' , 200);
        }
}
