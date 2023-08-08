<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use App\Models\{RepairStoreAvailibilityHours,RepairService,OrderDetail,RepairerOrder};
use Auth;
use Mail;

class RepairStoreController extends Controller
{
    public function index(){
        try {
                $repairStoreAvaliablity=RepairStoreAvailibilityHours::where('repair_store_id',Auth::user()->id)->get();
                return view('frontend.user.repair_store.repair-store-availablity',compact('repairStoreAvaliablity'));
        }catch (\Throwable $th) {
           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
    public function store(Request $request) {
        try {
            if (isset($request->days)) {
                $check_measurer_exists = RepairStoreAvailibilityHours::where('repair_store_id',Auth::user()->id)->delete();
                foreach($request->days as $key =>$value){
                    $data = array(
                        'days'=>$value,
                        'from_time'=>$request->from [$key],
                        'to_time'=>$request->to [$key],
                        'repair_store_id'=>Auth::user()->id,
                    );
                    RepairStoreAvailibilityHours::insert($data);
                }
                flash("Hours set successfully")->success();
                return redirect()->back();

            }
        }
        catch (\Throwable $th) {
            // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
    public function serviceList(){
        try {

            $service=RepairService::latest()->paginate(10);
            return view('frontend.user.repair_store.service.service',compact('service'));

        }catch (\Throwable $th) {
           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
    public function repairerServiceDetails(Request $request){
        try {

            $order = OrderDetail::with('product')->where('order_id',$request->order_id)->first();
            $service=RepairService::where('user_id',$request->repairer_id)->get();
            $repairerId=$request->repairer_id;
            $customerId=$request->customer_id;
            return view('seller.repairer.service-details', compact('service','order','repairerId','customerId'));

        }catch (\Throwable $th) {
           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
    public function storeService(Request $request){
        try{
            $input=$request->except('_token','id');
            if($request->id != 0){
                $repairService=RepairService::find($request->id);
                $repairService->update($input);
                flash(translate('Service Updated Successfully'))->success();
            }
            else{

                $input['user_id']=Auth::user()->id;
                RepairService::insert($input);
                flash(translate('New Service Added Successfully!'))->success();

            }
            return redirect()->back();

        }catch (\Throwable $th) {

           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
        }
    }
    public function bookService(Request $request){

        try{
            $input=$request->except('_token');

            if( $request->hasFile('image')){
                if ($request->file('image')->isValid()){
                    $file = $request->file('image');
                    $name = $file->getClientOriginalName();
                    $file->move('public/uploads/repairer_order/' , $name);
                    $input['image'] = $name;
                }
            }
            RepairerOrder::insert($input);
            flash(translate('Service Booked Successfully!'))->success();
            return redirect()->route('seller.orders.index');

        }catch (\Throwable $th) {

           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->route('seller.orders.index');
        }
    }
    public function getServiceDetails(Request $request){
        try{

            $service=RepairService::find($request->id);

            if(!empty($service)){
                $result['success']=1;
                $success=1;
                $result['service']=$service;
                return $result;

            }
            else{
                $result['success']=0;
                return $result;
            }
        }catch (\Exception $e) {
            $result['success']=0;
            return $result;
        }
    }
    public function gerServiceCost(Request $request){
        try{

            $service=RepairService::find($request->serviceId);
            if(!empty($service)){
                $result['success']=1;
                $result['serviceCost']=$service->service_price;
            }
            else{
                $result['success']=0;

            }
            return response()->json($result);
        }catch (\Exception $e) {
            $result['success']=0;
            return response()->json($result);
        }
    }
    public function myOrders(){
        try{
            $orders=RepairerOrder::with('seller','service','product')->where('repairer_id',auth()->user()->id)->latest()->paginate(10);
            return view('frontend.user.repair_store.orders.orders',compact('orders'));
        }catch (\Throwable $th) {

        }
    }
    public function updateOrderStatus(Request $request){
        try{

            $service=RepairerOrder::find($request->id);
            if(!empty($service)){

                $data['status']=$request->status;
                $service->update($data);
                $result['success']=1;
                flash(translate('Order Updated Successfully!'))->success();
            }
            else{
                $result['success']=0;

            }
            return response()->json($result);
        }catch (\Exception $e) {
            $result['success']=0;
            flash(translate('something went Wrong'))->error();
            return response()->json($result);
        }
    }

    public function acceptOrder(Request $request){

        try{
            $service=RepairerOrder::find($request->id);
            if($request->orderStatus==2){

                $data['payment_status']= 2;
            }
            $data['status']=$request->orderStatus;
            $service->update($data);

            // send mail to seller for order confirmation
            $subject='Order Confirmation';
            $to='';
           // $to=$emailData['email'];
            Mail::send(
                'emails.repairer_order.confirm_order',['data' =>$data],
                function ($message) use ($to,$subject) {
                    $message->from('noreply@sikikamis.dudumizi.com');
                    $message->to('harmistest@gmail.com');
                    //$message->to($to);
                    $message->subject($subject);
                }
            );
            flash(translate('Order Updated Successfully!'))->success();
            return redirect()->back();
        }catch (\Exception $e) {
            echo "<pre>";print_r($e->getMessage());exit;
            flash(translate('something went Wrong'))->error();
            return redirect()->back();
        }

    }
    public function destroy($id)
    {

        try{

            $service=RepairService::find(Crypt::decrypt($id));
            if($service){
                $service->delete();
                flash(translate('Service Deleted Successfully'))->success();
            }
            else{
                flash(translate('No Data Found for Delete This record'))->error();
            }
            return redirect()->back();
        }catch (\Exception $e) {
            flash(translate('Something Went Wrong !'))->error();
            return redirect()->back();
        }
    }
}
