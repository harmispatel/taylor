<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RepairerWithdrawRequest;

class RepairerWithdrawRequestController extends Controller
{

    public function index()
    {
        $repairer_withdraw_requests = RepairerWithdrawRequest::with('repairer')->latest()->paginate(15);
        return view('backend.repairer.money_withdraw_requests.index', compact('repairer_withdraw_requests'));
    }
    public function payment_modal(Request $request)
    {
        $repairer_withdraw_requests = RepairerWithdrawRequest::with('repairer','bankDetails')->where('id', $request->repairer_withdraw_request_id)->first();
        return view('backend.repairer.money_withdraw_requests.payment_modal', compact('repairer_withdraw_requests'));
    }
    public function store(Request $request)
    {
        try{
            $input=$request->except('_token');
            $input['user_id']=auth()->user()->id;

            RepairerWithdrawRequest::insert($input);
            flash(translate('Request has been sent successfully'))->success();
            return redirect()->back();
        }catch (\Throwable $th) {

            // for check error  $th->getMessage();
            flash(translate('Something went Wrong'))->error();
            return redirect()->back();
         }
    }
}
