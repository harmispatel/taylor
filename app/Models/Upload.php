<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upload extends Model
{
    use SoftDeletes;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'file_original_name', 'file_name', 'user_id', 'extension', 'type', 'file_size','approval'
    ];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function model_details()
    {
    	return $this->belongsTo(ModelDetail::class,'id','upload_id');
    }
    public function model_images()
    {
    	return $this->belongsTo(ModelImage::class,'id','uploaded_image_id');
    }


}
