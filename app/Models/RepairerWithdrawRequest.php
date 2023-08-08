<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairerWithdrawRequest extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function repairer(){
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function bankDetails(){
        return $this->belongsTo(BankDetails::class,'user_id','user_id');
    }
}
