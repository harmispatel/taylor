<?php

namespace App\Http\Controllers;
use App\Models\PurchaseTicketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use QrCode;
use PDF;

class StoreTicketController extends Controller
{
    public function index(){
        $ticketList=PurchaseTicketHistory::where('user_id',auth()->user()->id)->latest()->paginate(5);
        return view("frontend.user.store_ticket.index",compact(['ticketList']));
    }
    public function viewTicketDetails($id){

        $ticketDetails=PurchaseTicketHistory::find(Crypt::decrypt($id));
        return view("frontend.user.store_ticket.view_ticket",compact(['ticketDetails']));
    }
    public function getTicketDetails(Request $request){
        try{

            $ticketdetails=PurchaseTicketHistory::find($request->ticketId);

            if(!empty($ticketdetails)){
                $result['success']=1;
                $success=1;
                $result['ticketdetails']= $ticketdetails;
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

    public function updateTicketDetails(Request $request){
        try{

            $ticketData=PurchaseTicketHistory::find($request->ticketId);
            $input=$request->except('_token','ticketId');
            if($request->hasFile('document')){
                $input=$request->except('_token','document','ticketId');
                if ($request->file('document')->isValid()){
                    $file = $request->file('document');
                    $name = $file->getClientOriginalName();
                    $file->move('public/uploads/documents/' , $name);
                    $input['document'] = $name;
                    // remove previous file from folder
                    if(!empty($ticketData->document)){
                        @unlink('/public/uploads/documents/'.$ticketData->document );
                    }

                }
            }
            $ticketData->update($input);
            flash(translate('Ticket Data Updated Successfully'))->success();
            return redirect()->back();
        }catch (\Exception $e) {
            flash(translate('something went Wrong'))->error();
            return redirect()->back();
        }

    }
    public function downloadTicket($id){
        try{
            $ticketData=PurchaseTicketHistory::find(Crypt::decrypt($id));
            $qr_name=isset($ticketData->ticket_qrcode_image) ? $ticketData->ticket_qrcode_image:'';
            $ticketNumber=isset($ticketData->ticket_number) ? $ticketData->ticket_number:'';
            $address=isset($ticketData->address) ? $ticketData->address:'';
             $path = public_path('uploads/ticket_qr/'.$qr_name);
                QrCode::format('png')->size(200)->generate($ticketNumber, $path);
                $data = ['ticketNumber' => $ticketNumber,'qr_name'=> $qr_name,'address'=>$address];
                $pdf = PDF::loadView('frontend.user.downloads.ticket', $data);
                $pdf->download();
        }catch (\Exception $e) {
            flash(translate('something went Wrong'))->error();
            return redirect()->back();
        }
    }

    public function nearby_delivery_store(Request $request) {

        try{

            $ticketData=PurchaseTicketHistory::find($request->id);
            $user = User::find($request->user_id);
            $latitude  =       isset($ticketData->latitude) ? $ticketData->latitude : 1.0000;
            $longitude =       isset($ticketData->longitude) ? $ticketData->longitude : 1.0000;
            $nearUser  =       DB::table("users")->join('uploads', 'uploads.user_id', '=', 'users.id','left');
            $nearUser  =       $nearUser->select("*",'uploads.file_name',DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(addresses.latitude)) * cos(radians(addresses.longitude) - radians(" . $longitude . "))
                            + sin(radians(" .$latitude. ")) * sin(radians(addresses.latitude))) AS distance"))->where("users.id","!=",$user->id);
            $nearUser  =       $nearUser->having('distance', '<', config('global.rediusRange'));
            $nearUser  =       $nearUser->where('users.user_type', 'delivery_store');
            $nearUser  =       $nearUser->orderBy('distance', 'asc');
            $data['deliveryStore'] = $nearUser->get();
            return response()->json($data);
        }
        catch (\Exception $e) {
            $result['success']=0;
            return response()->json($result);

        }

    }
}
