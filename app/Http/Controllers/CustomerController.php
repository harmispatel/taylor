<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\MeasurerAvailablityHours;
use App\Models\Message;
use App\Models\RequestAppointment;
use App\Models\RequestMeasurement;
use App\Models\RequestPersonaliseProduct;
use App\Models\{TemporaryMeasurerCommission,PurchaseTicketPaymentHistory,PurchaseTicketHistory};
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\{User,Brand};
use Session;
use PDF;
use QrCode;
use Mail;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use  App\Http\Controllers\Payment\PaypalController;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $users = User::where('user_type', 'customer')->where('email_verified_at', '!=', null)->orderBy('created_at', 'desc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $users->where(function ($q) use ($sort_search){
                $q->where('name', 'like', '%'.$sort_search.'%')->orWhere('email', 'like', '%'.$sort_search.'%');
            });
        }
        $users = $users->paginate(15);
        return view('backend.customer.customers.index', compact('users', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function purchaseStoreTicket(Request $request){
        try{

                $qr_slug='ticket';
                $ticketNumber=$this->genratetoken(10);
                $qr_name = $qr_slug."_".time()."_qr.png";
                $pdfFileName = 'ticket'."_".time().".pdf";
                $upload_path = public_path('uploads/ticket_qr/'.$qr_name);
                QrCode::format('png')->size(200)->generate($ticketNumber, $upload_path);
                $data = ['ticketNumber' => $ticketNumber,'qr_name'=> $qr_name];
                $pdf = PDF::loadView('frontend.user.downloads.ticket', $data);
                $path = 'public/uploads/ticket_pdf/';
                $pdf->save($path  .$pdfFileName);
                $input=$request->except('_token','document');
            if( $request->hasFile('document')){
                if ($request->file('document')->isValid()){
                    $file = $request->file('document');
                    $name = $file->getClientOriginalName();
                    $file->move('public/uploads/documents/' , $name);
                    $input['documents'] = $name;
                }
            }


            $input['amount']=3;
            Session::put('userDetails',$input);
            Session::put('payment_type','store_purchase_ticket_payment');
            return (new PaypalController)->pay();
        }catch (\Exception $e) {
            flash(translate('something went Wrong'))->error();
            return redirect()->back();
        }

    }
    function genratetoken($length = 32)
    {
        // Function for Genrate random Token
        $string = 'A0B1C2D3E4F5G6H7I8J9KLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $max = strlen($string) - 1;
        $token = '';
        for($i = 0; $i < $length; $i++)
        {
            $token .= $string[mt_rand(0, $max)];
        }
        return $token;
    }
    public function purchase_ticket_payment_done($payment_data, $payment)
    {
        try{

            $paymentDetails=json_decode($payment);
            $purchaseTicketPayment=new PurchaseTicketPaymentHistory;
            $purchaseTicketPayment->user_id=auth()->user()->id;
            $purchaseTicketPayment->amount=$payment_data['amount'];
            $purchaseTicketPayment->payment_details=$payment;
            $purchaseTicketPayment->status=isset($paymentDetails->result->status) ? $paymentDetails->result->status : '';
            $purchaseTicketPayment->payment_method=$payment_data['payment_option'];
            $purchaseTicketPayment->save();

            if(isset($paymentDetails->result->status) && $paymentDetails->result->status=='COMPLETED'){
                $userData=Session::get('userDetails');
                $qr_slug='ticket';
                $ticketNumber=$this->genratetoken(10);
                $qr_name = $qr_slug."_".time()."_qr.png";
                $pdfFileName = 'ticket'."_".time().".pdf";
                $upload_path = public_path('uploads/ticket_qr/'.$qr_name);
                QrCode::format('png')->size(200)->generate($ticketNumber, $upload_path);
                $data = ['ticketNumber' => $ticketNumber,'qr_name'=> $qr_name,'address'=>$userData['address']];
                $pdf = PDF::loadView('frontend.user.downloads.ticket', $data);
                $path = 'public/uploads/ticket_pdf/';
                $pdf->save($path  .$pdfFileName);

                $currentDate=date('Y-m-d', strtotime('+1 year'));
                $purchaseTicketData['name']=$userData['name'];
                $purchaseTicketData['email']=$userData['email'];
                $purchaseTicketData['address']=$userData['address'];
                $purchaseTicketData['address1']=$userData['address1'];
                $purchaseTicketData['address2']=$userData['address2'];
                $purchaseTicketData['longitude']=$userData['longitude'];
                $purchaseTicketData['latitude']=$userData['latitude'];
                $purchaseTicketData['phone']=$userData['phone'];
                $purchaseTicketData['document']=$userData['documents'];
                $purchaseTicketData['amount']=$userData['amount'];
                $purchaseTicketData['user_id']=auth()->user()->id;
                $purchaseTicketData['ticket_validity']=$currentDate;
                $purchaseTicketData['ticket_number']=$ticketNumber;
                $purchaseTicketData['ticket_qrcode_image']=$qr_name;
                PurchaseTicketHistory::insert($purchaseTicketData);

                // send mail to seller for order confirmation
                $ticketFile = public_path('uploads/ticket_pdf/'.$pdfFileName);
                $subject='Purchased Ticket';
                $to='';
                $to=auth()->user()->email;
                Mail::send(
                    'emails.purchase_ticket.ticket',['data' =>$data],
                    function ($message) use ($to,$subject,$ticketFile) {
                        $message->from(env('MAIL_FROM_ADDRESS'));
                        //$message->to('harmistest@gmail.com');
                        $message->to($to);
                        $message->subject($subject);
                        $message->attach($ticketFile);
                    }
                );
            }

            flash(translate('Payment done successful'))->success();
            return redirect()->route('dashboard');
        }
        catch (\Throwable $th) {
            echo "<pre>";print_r($th->getMessage());exit;
            flash(translate('something went wrong'))->errors();
            return redirect()->route('dashboard');
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'email'         => 'required|unique:users|email',
            'phone'         => 'required|unique:users',
        ]);

        $response['status'] = 'Error';

        $user = User::create($request->all());

        $customer = new Customer;

        $customer->user_id = $user->id;
        $customer->save();

        if (isset($user->id)) {
            $html = '';
            $html .= '<option value="">
                        '. translate("Walk In Customer") .'
                    </option>';
            foreach(Customer::all() as $key => $customer){
                if ($customer->user) {
                    $html .= '<option value="'.$customer->user->id.'" data-contact="'.$customer->user->email.'">
                                '.$customer->user->name.'
                            </option>';
                }
            }

            $response['status'] = 'Success';
            $response['html'] = $html;
        }

        echo json_encode($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        flash(translate('Customer has been deleted successfully'))->success();
        return redirect()->route('customers.index');
    }

    public function bulk_customer_delete(Request $request) {
        if($request->id) {
            foreach ($request->id as $customer_id) {
                $this->destroy($customer_id);
            }
        }

        return 1;
    }

    public function login($id)
    {
        $user = User::findOrFail(decrypt($id));

        auth()->login($user, true);

        return redirect()->route('dashboard');
    }

    public function ban($id) {
        $user = User::findOrFail(decrypt($id));

        if($user->banned == 1) {
            $user->banned = 0;
            flash(translate('Customer UnBanned Successfully'))->success();
        } else {
            $user->banned = 1;
            flash(translate('Customer Banned Successfully'))->success();
        }

        $user->save();

        return back();
    }

    public function measurer_conversations_create(Request $request) {

        if (Auth::check()) {

            $measurer = User::findOrFail($request->measurer_id);

            $curUser = Auth::user();
            if ($measurer) {

                $conversation = Conversation::where(['sender_id' => $curUser->id, 'receiver_id' => $request->measurer_id])->first();
                if($conversation) {

                    return redirect()->route('measurer.conversations', ['id' => encrypt($conversation->id), 'measurer_id' => $request->measurer_id, 'request_id' => $request->request_id]);
                }
                else {

                    $conversation = Conversation::create(['sender_id' => $curUser->id, 'receiver_id' => $request->measurer_id, 'sender_viewed' => 0, 'receiver_viewed' => 0]);
                    if ($conversation) {
                        return redirect()->route('measurer.conversations', ['id' => encrypt($conversation->id), 'measurer_id' => $request->measurer_id, 'request_id' => $request->request_id]);
                    }
                }
            }
            else {
                return redirect('/');
            }
        }
        else {
            return redirect('/');
        }
    }


    public function measurer_conversations(Request $request, $id) {

        // dd('measurer_conversations');
        $measurer_avaliablity = MeasurerAvailablityHours::where('measurer_id',$request->measurer_id)->get();


        // dd($measurer_hours);


        if($request->isMethod('post')) {

            $message = new Message;
            $message->conversation_id = $request->conversation_id;
            $message->user_id = Auth::user()->id;
            $message->message = $request->message;
            $message->save();
            $conversation = $message->conversation;
            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->receiver_viewed = "1";
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->sender_viewed = "1";
            }
            $conversation->save();

            return back();
        }
        else {
            $conversation = Conversation::findOrFail(decrypt($id));
            $request_personalize_product = RequestPersonaliseProduct::where('id',$request->request_id)->first();
            // dd($request->request_id);
            if ($conversation->sender_id == Auth::user()->id) {
                $conversation->sender_viewed = 1;
            }
            elseif($conversation->receiver_id == Auth::user()->id) {
                $conversation->receiver_viewed = 1;
            }
            $conversation->save();
            $measurer = User::find($request->measurer_id);
            $commission = TemporaryMeasurerCommission::where('measurer_id', $request->measurer_id)->where('consumer_id', auth()->id())->first();
            if(!$commission){

                if (isset($measurer->defaultMeasurerCommission)) {
                    $commission = $measurer->defaultMeasurerCommission->default_commission;
                }else{
                    $commission = 0;
                }
            }else{
                $commission = $commission->commission;
            }

            return view('frontend.user.conversations.show', compact('conversation','request_personalize_product','measurer_avaliablity','measurer','commission'));
        }
    }

    public function appointment_create(Request $request) {
        // dd($request->all());
        $tmpcommission = TemporaryMeasurerCommission::where([
            ['measurer_id', '=', decrypt($request->measurer_id)],
            ['consumer_id', '=', auth()->id()]
        ])->delete();

       $appointment = RequestAppointment::create(['datetime' => $request->datetime,'measurer_commission' => $request->measurer_commission ,'user_id' => auth()->id(), 'measurer_id' => decrypt($request->measurer_id),'customer_address_id' => $request->customer_address ]);

       return redirect()->back()->with('msg', 'Appointment created successfully!');
    }
}
