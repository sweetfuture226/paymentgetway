<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use App\Deposit;
use App\Faqs;
use App\Investment;
use App\Menu;
use App\PaymentLog;
use App\PaymentMethod;
use App\Plan;
use App\Repeat;
use App\RepeatLog;
use App\Service;
use App\Slider;
use App\Testimonial;
use App\TraitsFolder\MailTrait;
use App\User;
use App\UserLog;
use App\WithdrawLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\Token;

class HomeController extends Controller
{
    use MailTrait;
    public function getHome()
    {
        $data['basic_setting'] = BasicSetting::first();
        $data['page_title'] = "Home Page";
        $data['plan'] = Plan::whereStatus(1)->get();
        $data['slider'] = Slider::all();
        $data['service'] = Service::take(8)->get();
        $data['total_repeat'] = RepeatLog::sum('amount');
        $data['total_user'] = User::all()->count();
        $data['total_deposit'] = Deposit::whereNotIn('status',[0])->sum('amount');
        $data['total_withdraw'] = WithdrawLog::whereStatus(2)->sum('amount');
        $data['top_investor'] = DB::table('investments')
            ->select('amount','user_id', DB::raw('SUM(amount) as total_invest'))
            ->groupBy('amount','user_id')
            ->orderBy('total_invest','desc')
            ->take(8)
            ->get();
        $data['testimonial'] = Testimonial::orderBy('id','desc')->get();
        $data['latest_deposit'] = Deposit::whereStatus(1)->orderBy('id','desc')->take(6)->get();
        $data['latest_withdraw'] = WithdrawLog::whereStatus(2)->orderBy('id','desc')->take(6)->get();
        $data['payment'] = PaymentMethod::take(4)->get();
        return view('home.home',$data);
    }
    public function menu($id,$name)
    {
        $data['menu1'] = Menu::findOrFail($id);
        $data['page_title'] = $data['menu1']->name;
        return view('home.menu',$data);
    }
    public function getAbout()
    {
        $data['page_title'] = 'About Page';
        return view('home.about',$data);
    }
    public function getFaqs()
    {
        $data['page_title'] = 'FAQS Page';
        $data['faqs'] = Faqs::orderBy('id','desc')->paginate(10);
        return view('home.faqs',$data);
    }
    public function getContact()
    {
        $data['page_title'] = 'Contact Page';
        return view('home.contact',$data);
    }
    public function submitContact(Request $request)
    {
        $request->validate([
           'name' => 'required',
           'email' => 'required',
           'subject' => 'required',
           'phone' => 'required',
           'message' => 'required',
        ]);
        $this->sendContact($request->email,$request->name,$request->subject,$request->message,$request->phone);
        session()->flash('message','Contact Message Successfully Send.');
        return redirect()->back();
    }

    public function repeatGenerate()
    {
        $basic = BasicSetting::first();
        if ($basic->repeat_status == 1){

            $repeats = Repeat::whereStatus(0)->get();
            foreach ($repeats as $rep){
                if ($rep->repeat_time < Carbon::now()){

                    $rLog['user_id'] = $rep->user_id;
                    $rLog['trx_id'] = strtoupper(Str::random(20));
                    $rLog['investment_id'] = $rep->investment_id;
                    $rLog['made_time'] = Carbon::now();
                    $rLog['amount'] = round(($rep->invest->amount * $rep->invest->plan->percent) / 100,$basic->deci);
                    RepeatLog::create($rLog);

                    $rep->total_repeat = $rep->total_repeat + 1;
                    $rep->made_time = Carbon::now();
                    $rep->repeat_time = Carbon::parse()->addHours($rep->invest->plan->compound->compound);
                    if ($rep->total_repeat == $rep->invest->plan->time){
                        $rep->status = 1;
                        $inv = Investment::findOrFail($rep->investment_id);
                        $inv->status = 1;
                        $inv->save();
                    }

                    $rep->save();

                    $amo = $rLog['amount'];
                    $plan = $rep->invest->plan->name;
                    $trx = $rLog['trx_id'];

                    $mem = User::findOrFail($rep->user_id);

                    $ul['user_id'] = $rep->user_id;
                    $ul['amount'] = $rLog['amount'];
                    $ul['charge'] = null;
                    $ul['post_bal'] = $mem->balance + $amo;
                    $ul['amount_type'] = 15;
                    $ul['description'] = "Repeat ".$amo." ".$basic->currency.". For Investment Plan - $plan.";
                    $ul['transaction_id'] = $trx;
                    UserLog::create($ul);

                    $mem->balance = $mem->balance + $amo;
                    $mem->save();

                    if ($basic->email_notify == 1){
                        $text = $amo." - ". $basic->currency ." Repeat For Investment Plan - $plan. <br> Transaction ID Is : <b>#".$trx."</b>";
                        $this->sendMail($mem->email,$mem->name,'Investment Repeat Bonus.',$text);
                    }
                    if ($basic->phone_notify == 1){
                        $text = $amo." - ".$basic->currency ." Repeat For Investment Plan - $plan. <br> Transaction ID Is : <b>#".$trx."</b>";
                        $this->sendSms($mem->phone,$text);
                    }

                }
            }
        }
    }
    public function paypalIpn()
    {
        $payment_type		=	$_POST['payment_type'];
        $payment_date		=	$_POST['payment_date'];
        $payment_status		=	$_POST['payment_status'];
        $address_status		=	$_POST['address_status'];
        $payer_status		=	$_POST['payer_status'];
        $first_name			=	$_POST['first_name'];
        $last_name			=	$_POST['last_name'];
        $payer_email		=	$_POST['payer_email'];
        $payer_id			=	$_POST['payer_id'];
        $address_country	=	$_POST['address_country'];
        $address_country_code	= $_POST['address_country_code'];
        $address_zip		=	$_POST['address_zip'];
        $address_state		=	$_POST['address_state'];
        $address_city		=	$_POST['address_city'];
        $address_street		=	$_POST['address_street'];
        $business			=	$_POST['business'];
        $receiver_email		=	$_POST['receiver_email'];
        $receiver_id		=	$_POST['receiver_id'];
        $residence_country	=	$_POST['residence_country'];
        $item_name			=	$_POST['item_name'];
        $item_number		=	$_POST['item_number'];
        $quantity			=	$_POST['quantity'];
        $shipping			=	$_POST['shipping'];
        $tax				=	$_POST['tax'];
        $mc_currency		=	$_POST['mc_currency'];
        $mc_fee				=	$_POST['mc_fee'];
        $mc_gross			=	$_POST['mc_gross'];
        $mc_gross_1			=	$_POST['mc_gross_1'];
        $txn_id				=	$_POST['txn_id'];
        $notify_version		=	$_POST['notify_version'];
        $custom				=	$_POST['custom'];

        $ip = gethostbyaddr($_SERVER['REMOTE_ADDR']);

        $paypal = PaymentMethod::whereId(1)->first();

        $paypal_email = $paypal->val1;

        if($payer_status=="verified" && $payment_status=="Completed" && $receiver_email==$paypal_email && $ip=="notify.paypal.com"){

            $data = PaymentLog::where('custom' , $custom)->first();

            $totalamo = $data->usd;

            if($totalamo == $mc_gross)
            {
                $basic = BasicSetting::first();
                $mem = User::findOrFail($data->member_id);
                $de['user_id'] = $mem->id;
                $de['amount'] = $data->amount;
                $de['payment_type'] = 1;
                $de['charge'] = $data->charge;
                $de['rate'] = $data->payment->rate;
                $de['net_amount'] = $data->net_amount;
                $de['transaction_id'] = $data->custom;
                $de['status'] = 1;
                Deposit::create($de);

                $ul['user_id'] = $mem->id;
                $ul['amount'] = $data->amount;
                $ul['charge'] = $data->charge;
                $ul['post_bal'] = $mem->balance + $data->amount;
                $ul['amount_type'] = 1;
                $ul['description'] = "Deposit ".$data->amount." ".$basic->currency." . By Paypal.";
                $ul['transaction_id'] = $data->custom;
                UserLog::create($ul);

                if ($mem->under_reference != 0){
                    $refMem = User::findOrFail($mem->under_reference);
                    $refAmo = round(($data->amount * $basic->reference_percent) / 100,$basic->deci);

                    $ul['user_id'] = $refMem->id;
                    $ul['amount'] = $refAmo;
                    $ul['charge'] = Null;
                    $ul['post_bal'] = $refMem->balance + $refAmo;
                    $ul['amount_type'] = 3;
                    $ul['description'] = "Reference Deposit Bonus ".$refAmo." ".$basic->currency." . From - $mem->username.";
                    $ul['transaction_id'] = $data->custom;
                    UserLog::create($ul);

                    $refMem->balance = $refMem->balance + $refAmo;
                    $refMem->save();
                    if ($basic->email_notify == 1){
                        $text = $refAmo." - ". $basic->currency ." Reference Deposit Bonus From - $mem->username. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendMail($refMem->email,$refMem->name,'Reference Deposit Bonus.',$text);
                    }
                    if ($basic->phone_notify == 1){
                        $text = $refAmo." - ".$basic->currency ." Reference Deposit Bonus From - $mem->username.. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendSms($refMem->phone,$text);
                    }

                }

                $mem->balance = $mem->balance + ($data->amount);

                $mem->save();

                if ($basic->email_notify == 1){
                    $text = $data->amount." - ". $basic->currency ." Deposit via Paypal Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendMail($mem->email,$mem->name,'Deposit Completed.',$text);
                }
                if ($basic->phone_notify == 1){
                    $text = $data->amount." - ".$basic->currency ." Deposit Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendSms($mem->phone,$text);
                }

                $data->status = 1;
                $data->save();
                session()->flash('message','Deposit Successfully Complete.');
                session()->flash('type','success');
                session()->flash('title','Completed');
                return redirect()->route('deposit-fund');
            }
        }
    }
    public function perfectIPN()
    {
        $pay = PaymentMethod::whereId(2)->first();
        $passphrase=strtoupper(md5($pay->val2));

        define('ALTERNATE_PHRASE_HASH',  $passphrase);
        define('PATH_TO_LOG',  '/somewhere/out/of/document_root/');
        $string=
            $_POST['PAYMENT_ID'].':'.$_POST['PAYEE_ACCOUNT'].':'.
            $_POST['PAYMENT_AMOUNT'].':'.$_POST['PAYMENT_UNITS'].':'.
            $_POST['PAYMENT_BATCH_NUM'].':'.
            $_POST['PAYER_ACCOUNT'].':'.ALTERNATE_PHRASE_HASH.':'.
            $_POST['TIMESTAMPGMT'];

        $hash=strtoupper(md5($string));
        $hash2 = $_POST['V2_HASH'];

        if($hash==$hash2){

            $amo = $_POST['PAYMENT_AMOUNT'];
            $unit = $_POST['PAYMENT_UNITS'];
            $custom = $_POST['PAYMENT_ID'];


            $data = PaymentLog::where('custom' , $custom)->first();

            if($_POST['PAYEE_ACCOUNT']=="$pay->val1" && $unit=="USD" && $amo == $data->usd){

                $basic = BasicSetting::first();
                $mem = User::findOrFail($data->member_id);
                $de['user_id'] = $mem->id;
                $de['amount'] = $data->amount;
                $de['payment_type'] = 2;
                $de['charge'] = $data->charge;
                $de['rate'] = $data->payment->rate;
                $de['net_amount'] = $data->net_amount;
                $de['status'] = 1;
                $de['transaction_id'] = $data->custom;
                Deposit::create($de);

                $ul['user_id'] = $mem->id;
                $ul['amount'] = $data->amount;
                $ul['charge'] = $data->charge;
                $ul['amount_type'] = 1;
                $ul['post_bal'] = $mem->balance + $data->amount;
                $ul['description'] = "Deposit ".($data->amount)." - ".$basic->currency." . By Perfect Money.";
                $ul['transaction_id'] = $data->custom;
                UserLog::create($ul);

                if ($mem->under_reference != 0){
                    $refMem = User::findOrFail($mem->under_reference);
                    $refAmo = round(($data->amount * $basic->reference_percent) / 100,$basic->deci);

                    $ul['user_id'] = $refMem->id;
                    $ul['amount'] = $refAmo;
                    $ul['charge'] = null;
                    $ul['post_bal'] = $refMem->balance + $refAmo;
                    $ul['amount_type'] = 3;
                    $ul['description'] = "Reference Deposit Bonus ".$refAmo." ".$basic->currency." . From - $mem->username.";
                    $ul['transaction_id'] = $data->custom;
                    UserLog::create($ul);

                    $refMem->balance = $refMem->balance + $refAmo;
                    $refMem->save();
                    if ($basic->email_notify == 1){
                        $text = $refAmo." - ". $basic->currency ." Reference Deposit Bonus From - $mem->username. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendMail($refMem->email,$refMem->name,'Reference Deposit Bonus.',$text);
                    }
                    if ($basic->phone_notify == 1){
                        $text = $refAmo." - ".$basic->currency ." Reference Deposit Bonus From - $mem->username.. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendSms($refMem->phone,$text);
                    }

                }


                $mem->balance = $mem->balance + ($data->amount);
                $mem->save();

                if ($basic->email_notify == 1){
                    $text = $data->amount." - ". $basic->currency ." Deposit via Perfect Money Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendMail($mem->email,$mem->name,'Deposit Completed.',$text);
                }
                if ($basic->phone_notify == 1){
                    $text = $data->amount." - ".$basic->currency ." Deposit Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendSms($mem->phone,$text);
                }

                $data->status = 1;
                $data->save();
                session()->flash('message', 'Deposit Completed Successfully');
                session()->flash('type','success');
                session()->flash('title','Completed');
                return redirect()->route('deposit-fund');
            }else{
                session()->flash('message', 'Something error....');
                Session::flash('type', 'warning');
                return redirect()->route('deposit-fund');
            }
        }
    }
    public function btcPreview(Request $request)
    {
        $data['amount'] = $request->amount;
        $data['custom'] = $request->custom;
        $pay = PaymentMethod::whereId(3)->first();
        $tran = PaymentLog::whereCustom($data['custom'])->first();

        $blockchain_root = "https://blockchain.info/";
        $blockchain_receive_root = "https://api.blockchain.info/";
        $mysite_root = url('/');
        $secret = "ABIR";
        $my_xpub = $pay->val2;
        $my_api_key = $pay->val1;
        $invoice_id = $tran->custom;
        $callback_url = route('btc_ipn',['invoice_id'=>$invoice_id,'secret'=>$secret]);

        if ($tran->btc_acc == null){

            $resp = file_get_contents($blockchain_receive_root . "v2/receive?key=" . $my_api_key . '&callback=' . urlencode($callback_url) . '&xpub=' . $my_xpub);

            $response = json_decode($resp);

            $sendto = $response->address;

            if ($sendto!="") {
                $api = "https://blockchain.info/tobtc?currency=USD&value=".$tran->usd;
                $usd = file_get_contents($api);
                $tran->btc_amo = $usd;
                $tran->btc_acc = $sendto;
                $tran->save();
            }else{
                session()->flash('message', "SOME ISSUE WITH API");
                Session::flash('type', 'warning');
                Session::flash('title', 'Opps');
                return redirect()->back();
            }
        }else{
            $usd = $tran->btc_amo;
            $sendto = $tran->btc_acc;
        }
        /*$sendto = "1HoPiJqnHoqwM8NthJu86hhADR5oWN8qG7";
        $usd =100;*/


        $var = "bitcoin:$sendto?amount=$usd";
        $data['code'] =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$var&choe=UTF-8\" title='' style='width:300px;' />";

        $data['site_currency'] = "USD";
        $data['page_title'] = "Block Chain Deposit Preview";
        $data['paypal'] = PaymentMethod::whereId(1)->first();
        $data['perfect'] = PaymentMethod::whereId(2)->first();
        $data['btc'] = PaymentMethod::whereId(3)->first();
        $data['stripe'] = PaymentMethod::whereId(4)->first();
        $data['amount'] = $request->amount;
        $data['payment_type'] = $tran->payemnt_type;
        $data['fund'] = $tran;
        $data['usd'] = $usd;
        $data['add'] = $sendto;
        return view('user.btc-preview',$data);
    }
    public function btcIPN(){

        $depoistTrack = $_GET['invoice_id'];
        $secret = $_GET['secret'];
        $address = $_GET['address'];
        $value = $_GET['value'];
        $confirmations = $_GET['confirmations'];
        $value_in_btc = $_GET['value'] / 100000000;

        $trx_hash = $_GET['transaction_hash'];

        $data = PaymentLog::whereCustom($depoistTrack)->first();

        if($data->status == 0){

            if ($data->btc_amo == $value_in_btc && $data->btc_acc == $address && $secret=="ABIR" && $confirmations>2){

                $basic = BasicSetting::first();
                $mem = User::findOrFail($data->member_id);
                $de['user_id'] = $mem->id;
                $de['amount'] = $data->amount;
                $de['payment_type'] = 3;
                $de['charge'] = $data->charge;
                $de['rate'] = $data->payment->rate;
                $de['net_amount'] = $data->net_amount;
                $de['status'] = 1;
                $de['transaction_id'] = $data->custom;
                Deposit::create($de);

                $ul['user_id'] = $mem->id;
                $ul['amount'] = ($data->amount);
                $ul['charge'] = $data->charge;
                $ul['post_bal'] = $mem->balance + $data->amount;
                $ul['amount_type'] = 1;
                $ul['description'] = "Deposit ".($data->amount)." ".$basic->currency." . By BTC - BlockChain.";
                $ul['transaction_id'] = $data->custom;
                UserLog::create($ul);

                if ($mem->under_reference != 0){
                    $refMem = User::findOrFail($mem->under_reference);
                    $refAmo = round(($data->amount * $basic->reference_percent) / 100,$basic->deci);

                    $ul['user_id'] = $refMem->id;
                    $ul['amount'] = $refAmo;
                    $ul['charge'] = null;
                    $ul['post_bal'] = $refMem->balance + $refAmo;
                    $ul['amount_type'] = 3;
                    $ul['description'] = "Reference Deposit Bonus ".$refAmo." ".$basic->currency." . From - $mem->username.";
                    $ul['transaction_id'] = $data->custom;
                    UserLog::create($ul);

                    $refMem->balance = $refMem->balance + $refAmo;

                    $refMem->save();

                    if ($basic->email_notify == 1){
                        $text = $refAmo." - ". $basic->currency ." Reference Deposit Bonus From - $mem->username. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendMail($refMem->email,$refMem->name,'Reference Deposit Bonus.',$text);
                    }
                    if ($basic->phone_notify == 1){
                        $text = $refAmo." - ".$basic->currency ." Reference Deposit Bonus From - $mem->username.. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendSms($refMem->phone,$text);
                    }

                }

                $mem->balance = $mem->balance + ($data->amount);
                $mem->save();

                if ($basic->email_notify == 1){
                    $text = $data->amount." - ". $basic->currency ." Deposit via Bitcoin - (Blockchain) Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendMail($mem->email,$mem->name,'Deposit Completed.',$text);
                }
                if ($basic->phone_notify == 1){
                    $text = $data->amount." - ".$basic->currency ." Deposit Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendSms($mem->phone,$text);
                }

                $data->status = 1;
                $data->save();
                session()->flash('message','Successfully Deposit Completed Wait For Confirmation');
                session()->flash('type','success');
                session()->flash('title','Completed');
                return redirect()->route('deposit-fund');
            }
        }
    }
    public function stripePreview(Request $request)
    {
        $data['site_currency'] = "USD";
        $data['page_title'] = "Credit Card Deposit Preview";
        $data['paypal'] = PaymentMethod::whereId(1)->first();
        $data['perfect'] = PaymentMethod::whereId(2)->first();
        $data['btc'] = PaymentMethod::whereId(3)->first();
        $data['stripe'] = PaymentMethod::whereId(4)->first();
        $data['payment_type'] = 4;
        $data['amount'] = $request->amount;
        $data['custom'] = $request->custom;
        $data['fund'] = PaymentLog::whereCustom($request->custom)->first();
        return view('user.stripe-preview',$data);
    }
    public function submitStripe(Request $request)
    {
        $this->validate($request,[
            'amount' => 'required',
            'custom' => 'required',
            'cardNumber' => 'required|numeric',
            'cardExpiryMonth' => 'required|numeric',
            'cardExpiryYear' => 'required|numeric',
            'cardCVC' => 'required|numeric',
        ]);
        $data = PaymentLog::whereCustom($request->custom)->first();
        $amm = $data->usd;
        $cc = $request->cardNumber;
        $emo = $request->cardExpiryMonth;
        $eyr = $request->cardExpiryYear;
        $cvc = $request->cardCVC;
        $basic = PaymentMethod::whereId(4)->first();
        Stripe::setApiKey($basic->val1);
        try{
            $token = Token::create(array(
                "card" => array(
                    "number" => "$cc",
                    "exp_month" => $emo,
                    "exp_year" => $eyr,
                    "cvc" => "$cvc"
                )
            ));
            if (!isset($token['id'])) {
                session()->flash('message','The Stripe Token was not generated correctly');
                return Redirect::to($request->url);
            }

            $charge = Charge::create(array(
                'card' => $token['id'],
                'currency' => 'USD',
                'amount' => $data->usd * 100,
                'description' => 'item',
            ));

            if ($charge['status'] == 'succeeded' ) {

                $basic = BasicSetting::first();
                $mem = User::findOrFail($data->member_id);
                $de['user_id'] = $mem->id;
                $de['amount'] = $data->amount;
                $de['payment_type'] = 4;
                $de['charge'] = $data->charge;
                $de['rate'] = $data->payment->rate;
                $de['net_amount'] = $data->net_amount;
                $de['status'] = 1;
                $de['transaction_id'] = $data->custom;
                Deposit::create($de);

                $ul['user_id'] = $mem->id;
                $ul['amount'] = ($data->amount);
                $ul['charge'] = $data->charge;
                $ul['amount_type'] = 1;
                $ul['post_bal'] = $mem->balance + $data->amount;
                $ul['description'] = "Deposit ".($data->amount)." ".$basic->currency." . By Credit Card.";
                $ul['transaction_id'] = $data->custom;
                UserLog::create($ul);

                if ($mem->under_reference != 0){
                    $refMem = User::findOrFail($mem->under_reference);
                    $refAmo = round(($data->amount * $basic->reference_percent) / 100,$basic->deci);

                    $ul['user_id'] = $refMem->id;
                    $ul['amount'] = $refAmo;
                    $ul['charge'] = null;
                    $ul['post_bal'] = $refMem->balance + $refAmo;
                    $ul['amount_type'] = 3;
                    $ul['description'] = "Reference Deposit Bonus ".$refAmo." ".$basic->currency." . From - $mem->username.";
                    $ul['transaction_id'] = $data->custom;
                    UserLog::create($ul);

                    $refMem->balance = $refMem->balance + $refAmo;
                    $refMem->save();
                    if ($basic->email_notify == 1){
                        $text = $refAmo." - ". $basic->currency ." Reference Deposit Bonus From - $mem->username. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendMail($refMem->email,$refMem->name,'Reference Deposit Bonus.',$text);
                    }
                    if ($basic->phone_notify == 1){
                        $text = $refAmo." - ".$basic->currency ." Reference Deposit Bonus From - $mem->username.. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                        $this->sendSms($refMem->phone,$text);
                    }

                }

                $mem->balance = $mem->balance + ($data->amount);
                $mem->save();

                if ($basic->email_notify == 1){
                    $text = $data->amount." - ". $basic->currency ." Deposit via Credit Card Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendMail($mem->email,$mem->name,'Deposit Completed.',$text);
                }
                if ($basic->phone_notify == 1){
                    $text = $data->amount." - ".$basic->currency ." Deposit Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                    $this->sendSms($mem->phone,$text);
                }

                $data->status = 1;
                $data->save();

                session()->flash('message','Card Successfully Charged.');
                session()->flash('title','Success');
                session()->flash('type','success');
                return redirect()->route('deposit-fund');
            }else{
                session()->flash('message','Something Is Wrong.');
                session()->flash('title','Opps..');
                session()->flash('type','warning');
                return redirect()->route('deposit-fund');
            }

        }catch (\Exception $e){
            echo $e->getLine();
            session()->flash('message',$e->getMessage());
            session()->flash('title','Opps..');
            session()->flash('type','warning');
            return redirect()->route('deposit-fund');
        }
    }

}
