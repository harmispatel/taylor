<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairerWithdrawRequestPayment extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function repairer(){
        return $this->belongsTo(User::class,'repairer_id','id');
    }
}
