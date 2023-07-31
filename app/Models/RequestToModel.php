<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestToModel extends Model
{
    use HasFactory;
    protected $fillable = ['seller_id','customer_id','model_id', 'model_commission', 'request_status','appointment_datetime'];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }
    public function model()
    {
        return $this->belongsTo(User::class, 'model_id', 'id');
    }

}
