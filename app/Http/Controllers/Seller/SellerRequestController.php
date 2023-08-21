<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\RequestPersonaliseProduct;
use App\Models\User;
use Session;
use App\Models\RequestAppointment;
use App\Models\{RequestMembership,RepairerOrder,repairOrderPayment};
use App\Models\UserProfile;
use Illuminate\Support\Facades\DB;

class SellerRequestController extends Controller
{
    //
    // public function index() {
    //     $requests = RequestPersonaliseProduct::with('customer', 'product')->where('owner_id', \Auth::user()->id)->paginate();
    //     $measurers = User::join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->select(['users.id', 'users.name'])->get();

    //     return view('seller.requests.index', compact('requests', 'measurers'));
    // }
    public function index(Request $request) {

        $requests = RequestPersonaliseProduct::with('addresses','customer', 'product', 'appointment:id,request_id,appointment_status')->where(function($query) use($request){
            $cQuery = $query->where('owner_id', \Auth::user()->id);
            if($request->get('request_id')){

                $cQuery = $query->where('id', $request->get('request_id'));
            }
            return $cQuery;
        })->paginate();

        $measurers = User::join('user_profiles', 'user_profiles.user_id', '=', 'users.id')->select(['users.id', 'users.name'])->get();

        appointments_expire();

        return view('seller.requests.index', compact('requests', 'measurers'));
    }


    public function nearby_measurers(Request $request) {

     $address = Address::find($request->address_id);
     $user = User::find($request->user_id);

     $latitude  =       $address->latitude;
     $longitude =       $address->longitude;

     $nearUser  =       DB::table("users")->join('addresses', 'addresses.user_id', '=', 'users.id');

     $nearUser  =       $nearUser->select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                        * cos(radians(addresses.latitude)) * cos(radians(addresses.longitude) - radians(" . $longitude . "))
                        + sin(radians(" .$latitude. ")) * sin(radians(addresses.latitude))) AS distance"))->where("users.id","!=",$user->id);
     $nearUser  =       $nearUser->having('distance', '<', 20);
     $nearUser  =       $nearUser->where('users.user_type', 'measurer');
     $nearUser  =       $nearUser->orderBy('distance', 'asc');

     $data['measurers'] =       $nearUser->get();


     return response()->json($data);

    }

    public function nearby_repairer(Request $request) {

        try{
            $address = Address::find($request->address_id);
            $user = User::find($request->user_id);
            $latitude  =       isset($address->latitude) ? $address->latitude : 1.0000;
            $longitude =       isset($address->longitude) ? $address->longitude : 1.0000;
            $nearUser  =       DB::table("users")->join('addresses', 'addresses.user_id', '=', 'users.id')->join('uploads', 'uploads.user_id', '=', 'users.id','left');
            $nearUser  =       $nearUser->select("*",'uploads.file_name',DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(addresses.latitude)) * cos(radians(addresses.longitude) - radians(" . $longitude . "))
                            + sin(radians(" .$latitude. ")) * sin(radians(addresses.latitude))) AS distance"))->where("users.id","!=",$user->id);
            $nearUser  =       $nearUser->having('distance', '<', config('global.rediusRange'));
            $nearUser  =       $nearUser->where('users.user_type', 'repair_store');
            $nearUser  =       $nearUser->orderBy('distance', 'asc');
            $data['repairer'] = $nearUser->get();
            return response()->json($data);
        }
        catch (\Exception $e) {
            $result['success']=0;
            return response()->json($result);

        }

    }
    public function repairRequest(){

        try{

            $orders=RepairerOrder::with('seller','service','product')->where('seller_id',auth()->user()->id)->latest()->paginate(10);
            return view('seller.repairer.orders',compact('orders'));

        }catch (\Throwable $th) {

        }
    }
    public function repairOrder_payment_done($payment_data, $payment)
    {
        try{

            $paymentDetails=json_decode($payment);
            $orderData['payment_status']=3;
            $orderDetails=RepairerOrder::find($payment_data['repairOrder_id']);
            $orderDetails->update($orderData);

            $orderPayment=new repairOrderPayment;
            $orderPayment->user_id=auth()->user()->id;
            $orderPayment->amount=$payment_data['amount'];;
            $orderPayment->payment_details=$payment;
            $orderPayment->status=isset($paymentDetails->result->status) ? $paymentDetails->result->status : '';
            $orderPayment->payment_method=$payment_data['payment_type'];;
            $orderPayment->repairOrder_id=$payment_data['repairOrder_id'];
            $orderPayment->save();

            flash(translate('Payment done successful'))->success();
            return redirect()->route('dashboard');
        }
        catch (\Throwable $th) {
            flash(translate('something went wrong'))->errors();
            return redirect()->route('dashboard');
        }
    }
    public function appointment_show($id) {
        $appointment = RequestAppointment::findOrFail($id);
        return view('seller.requests.request-appointment', compact('appointment'));
    }


    public function membership_request_store(Request $request)
    {

     $request_membership = new RequestMembership;
     $request_membership->seller_id = Auth::id();
     $request_membership->profile_type_id = $request->profile_type_id;

     if($request_membership->save()){

        flash(translate('Request for Premium User has successfully sent'))->success();
        return redirect()->back();
    }

    }

    public function all_membership_requests()
    {
        $request_membership =  RequestMembership::with('user','profile_type')->get();

        return view('backend.sellers.seller_membership_request.index', compact('request_membership'));


    }

    public function update_membership_status(Request $request)
    {

        if($request->status == "1"){

        $request_membership =  RequestMembership::where('seller_id',$request->id)->first();

        $request_membership->is_premium = 1;
        $request_membership->save();


        DB::update('update user_profiles set profile_id = 2 where user_id = ?', [$request->id]);

        }

        elseif ($request->status == "0") {

            $request_membership =  RequestMembership::where('seller_id',$request->id)->first();
            $request_membership->is_premium = 0;
            $request_membership->save();
        }

    }
}
