<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairerOrder extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function seller(){
        return $this->belongsTo(User::class);
    }
    public function service(){
        return $this->belongsTo(RepairService::class);
    }
    public function product(){
        return $this->belongsTo(Product::class);
    }
}
