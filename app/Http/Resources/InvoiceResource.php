<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'invoice_number'=>$this->invoice_number,
            'invoice_date' => $this->invoice_date,
            'due_date' =>$this->due_date,
            'product' => $this->product,
            'amount_collection' => $this->amount_collection,
            'amount_commission' => $this->amount_commission,
            'discount'=>$this->discount,
            'rate_vat' => $this->rate_vat,
            'value_vat'=>$this->value_vat,
            'total'=>$this->total,
            'status' => $this->status,
            'value_status'=>$this->value_status,
            'note'=>$this->note,
            'payment_date' => $this->payment_date,
            'invoicesDetail' => $this->invoicesDetail,
            'invoicesAttachments' => $this->invoicesAttachments,
            'section' => new SectionResource($this->whenLoaded('section'))
        ];
    }
}
