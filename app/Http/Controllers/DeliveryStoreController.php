<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\DeliveryStoreAvailablityHours;

class DeliveryStoreController extends Controller
{
    public function index(){
        try {
            $deliveryStoreAvaliablity=DeliveryStoreAvailablityHours::where('delivery_store_id',Auth::user()->id)->get();
            return view('frontend.user.delivery_store.delivery-store-availablity',compact('deliveryStoreAvaliablity'));
        }catch (\Throwable $th) {
           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
    public function store(Request $request) {
        try {
            if (isset($request->days)) {
                $check_measurer_exists = DeliveryStoreAvailablityHours::where('delivery_store_id',Auth::user()->id)->delete();
                foreach($request->days as $key =>$value){
                    $data = array(
                        'days'=>$value,
                        'from_time'=>$request->from [$key],
                        'to_time'=>$request->to [$key],
                        'delivery_store_id'=>Auth::user()->id,
                    );
                    DeliveryStoreAvailablityHours::insert($data);
                }
                flash("Hours set successfully")->success();
                return redirect()->back();

            }
        }
        catch (\Throwable $th) {
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
}
