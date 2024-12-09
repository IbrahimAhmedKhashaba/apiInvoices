<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    //

    use SoftDeletes;
    protected $guarded = [];

    public function section(){
        return $this->belongsTo(Section::class);
    }

    public function invoicesDetail(){
        return $this->hasMany(InvoicesDetail::class);
    }

    public function invoicesAttachments(){
        return $this->hasMany(InvoicesAttachment::class);
    }
}
