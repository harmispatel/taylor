<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{RepairStoreAvailibilityHours,RepairService};
use Auth;

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
            echo "<pre>";print_r($th->getMessage());exit;
           // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
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
}
