<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\User;
use App\Deposit;
use App\UserLog;
use Stripe\Token;
use Carbon\Carbon;
use Stripe\Charge;
use Stripe\Stripe;
use App\PaymentLog;
use App\Lib\BlockIo;
use App\Transaction;
use App\BasicSetting;
use App\PaymentMethod;
use App\Lib\coinPayments;
use Illuminate\Http\Request;
use App\Lib\CoinPaymentHosted;

class PaymentController extends Controller
{

    public function userDataUpdate($data)
    { 
        if($data->status==0)
        {
            $totalamo = $data->usd;

            $basic = BasicSetting::first();
            $mem = User::findOrFail($data->member_id);

            $de['user_id'] = $mem->id;
            $de['amount'] = $data->amount;
            $de['payment_type'] = $data->payment_type;
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
            $ul['description'] = "Deposit ".$data->amount." ".$basic->currency." by". $data->payment->name;
            $ul['transaction_id'] = $data->custom;
            UserLog::create($ul);

            if ($mem->under_reference != 0)
            {
                $refMem = User::findOrFail($mem->under_reference);
                $refAmo = round(($data->amount * $basic->reference_percent) / 100,$basic->deci);

                $ul['user_id'] = $refMem->id;
                $ul['amount'] = $refAmo;
                $ul['charge'] = Null;
                $ul['post_bal'] = $refMem->balance + $refAmo;
                $ul['amount_type'] = 3;
                $ul['description'] = "Reference Deposit Bonus ".$refAmo." ".$basic->currency." . From -". $mem->username;
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
                $text = $data->amount." - ". $basic->currency ." Deposit Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                $this->sendMail($mem->email,$mem->name,'Deposit Completed.',$text);
            }
            if ($basic->phone_notify == 1){
                $text = $data->amount." - ".$basic->currency ." Deposit Successfully Completed. <br> Transaction ID Is : <b>#".$data->custom."</b>";
                $this->sendSms($mem->phone,$text);
            }

            $data->status = 1;
            $data->save();
            session()->flash('message','Deposit Successfuly Complete');
            session()->flash('type','success');
            session()->flash('title','Completed');
        }
       
    }
    
    public function depositConfirm()
    {
        $gnl = BasicSetting::first();
        
        $track = Session::get('Track');

        $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
        
        if(is_null($data))
        {
            return redirect()->route('deposit-fund')->with('alert', 'Invalid Deposit Request');            
        }
        if ($data->status != 0) 
        {
            return redirect()->route('deposit-fund')->with('alert', 'Invalid Deposit Request');
        }
        
        $gatewayData = PaymentMethod::find($data->payment_type);
        
        if ($data->payment_type == 101) 
        {        
            $paypal['amount'] = $data->usd;
            $paypal['sendto'] = $gatewayData->val1;
            $paypal['track'] = $track;
            return view('user.payment.paypal', compact('paypal','gnl'));
        } 
        elseif ($data->payment_type == 102) 
        {
            $perfect['amount'] = $data->usd;
            $perfect['value1'] = $gatewayData->val1;
            $perfect['value2'] = $gatewayData->val2;
            $perfect['track'] = $track;
            return view('user.payment.perfect', compact('perfect','gnl'));
        } 
        elseif ($data->payment_type == 103) 
        {
            $page_title = $gatewayData->name;
            return view('user.payment.stripe', compact('track','page_title'));
        }
        elseif ($data->payment_type == 104) 
        {
            return view('user.payment.skrill',compact('gnl','gatewayData','data'));
        } 
        elseif ($data->payment_type == 501) 
        {
            $page_title = $gatewayData->name;

            $all = file_get_contents("https://blockchain.info/ticker");
			$res = json_decode($all);
            $btcrate = $res->USD->last;
            
			$usd = $data->usd;
			$btcamount = $usd/$btcrate;
			$btc = round($btcamount, 8);

            if($data->btc_amo==0 || $data->btc_acc=="")
            {
                $blockchain_root = "https://blockchain.info/";
                $blockchain_receive_root = "https://api.blockchain.info/";
                $mysite_root = url('/');
                $secret = "ABIR";
                $my_xpub = $gatewayData->val2;
                $my_api_key = $gatewayData->val1;

                $invoice_id = $track;
                $callback_url = $mysite_root . "/ipnbtc?invoice_id=" . $invoice_id . "&secret=" . $secret;
                
                $resp = @file_get_contents($blockchain_receive_root . "v2/receive?key=" . $my_api_key . "&callback=" . urlencode($callback_url) . "&xpub=" . $my_xpub);

                if(!$resp) 
                {
                    return redirect()->route('deposit-fund')->with('alert', 'BLOCKCHAIN API HAVING ISSUE. PLEASE TRY LATER');
                }

                $response = json_decode($resp);
                $sendto = $response->address;

                $data['btc_acc'] = $sendto;
                $data['btc_amo'] = $btc;
                $data->update();

             }

            $DepositData = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
    
            $bitcoin['amount'] = $DepositData->btc_amo;
            $bitcoin['sendto'] = $DepositData->btc_acc;

            $var = "bitcoin:$DepositData->btc_acc?amount=$DepositData->btc_amo";
            $bitcoin['code'] =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$var&choe=UTF-8\" title='' style='width:300px;' />";

            $page_title = $gatewayData->name;
            return view('user.payment.blockchain', compact('bitcoin','page_title'));
             
             
        }
        elseif($data->payment_type ==502)
		{
            $method = PaymentMethod::find(502);
            $apiKey = $method->val1;
            $version = 2; 
            $pin =  $method->val2;
            $block_io = new BlockIo($apiKey, $pin, $version);
            $btcdata = $block_io->get_current_price(array('price_base' => 'USD'));
            if($btcdata->status!='success')
            {
                return back()->with('alert', 'Failed to Process');
            }
            $btcrate = $btcdata->data->prices[0]->price;
    
			$usd = $data->usd;
			$bcoin = round($usd/$btcrate,8);
			
            if($data->btc_amo==0 || $data->btc_acc=="")
            {
                $ad = $block_io->get_new_address();

                if ($ad->status == 'success') 
                { 
                    $blockad = $ad->data;
                    $wallet = $blockad->address;
                    $data['btc_acc'] = $wallet;
                    $data['btc_amo'] = $bcoin;
                    $data->update();
                }
                else
                {
                    return back()->with('alert', 'Failed to Process');
                }
            }

            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;
            $varb = "bitcoin:". $wallet ."?amount=".$bcoin;
            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.blockbtc', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type ==503)
		{
            $method = PaymentMethod::find(503);
            $apiKey = $method->val1;
            $version = 2; 
            $pin =  $method->val2;
            $block_io = new BlockIo($apiKey, $pin, $version);
            $btcdata = $block_io->get_current_price(array('price_base' => 'USD'));
            if($btcdata->status!='success')
            {
                return back()->with('alert', 'Failed to Process');
            }
            $btcrate = $btcdata->data->prices[0]->price;
    
			$usd = $data->usd;
			$bcoin = round($usd/$btcrate,8);

            if($data->btc_acc=="")
            {
                $ad = $block_io->get_new_address();

                if ($ad->status == 'success') 
                { 
                    $blockad = $ad->data;
                    $wallet = $blockad->address;
                    $data['btc_acc'] = $wallet;
                    $data['btc_amo'] = $bcoin;
                    $data->update();
                }
                else
                {
                    return back()->with('alert', 'Failed to Process');
                }
            }

            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;
            $varb = "litecoin:". $wallet;
            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.blocklite', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type ==504)
		{
            $method = PaymentMethod::find(504);
            $apiKey = $method->val1;
            $version = 2; 
            $pin =  $method->val2;
            $block_io = new BlockIo($apiKey, $pin, $version);
                     
            $dogeprice = file_get_contents("https://api.coinmarketcap.com/v1/ticker/dogecoin");
            $dresult = json_decode($dogeprice);
            $doge_usd = $dresult[0]->price_usd;

			$usd = $data->usd;
			$bcoin = round($usd/$doge_usd,8);
			
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {
                $ad = $block_io->get_new_address();

                if ($ad->status == 'success') 
                { 
                    $blockad = $ad->data;
                    $wallet = $blockad->address;
                    $data['btc_acc'] = $wallet;
                    $data['btc_amo'] = $bcoin;
                    $data->update();
                }
                else
                {
                    return back()->with('alert', 'Failed to Process');
                }
            }

            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;
            $varb = $wallet;
            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$varb&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.blockdog', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 505)
        {

            $method = PaymentMethod::find(505);
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.btc');

                $req = array(
                'amount' => $data->usd,
                'currency1' => 'USD',
                'currency2' => 'BTC',
                'custom' => $data->custom,
                'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);
                if ($result['error'] == 'ok') {

                $bcoin = sprintf('%.08f', $result['result']['amount']);
                $sendadd = $result['result']['address'];

                $data['btc_amo'] = $bcoin;
                $data['btc_acc'] = $sendadd;
                $data->update();

                } 
                else 
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }	
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=bitcoin:$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaybtc', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 506)
        {

            $method = PaymentMethod::find(506);
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.eth');

                $req = array(
                'amount' => $data->usd,
                'currency1' => 'USD',
                'currency2' => 'ETH',
                'custom' => $data->custom,
                'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') 
                {

                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_acc'] = $sendadd;
                    $data->update();

                } 
                else 
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }	
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpayeth', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 507)
        {

            $method = PaymentMethod::find(507);
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.bch');

                $req = array(
                'amount' => $data->usd,
                'currency1' => 'USD',
                'currency2' => 'BCH',
                'custom' => $data->custom,
                'ipn_url' => $callbackUrl,
                );

                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') 
                {

                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_acc'] = $sendadd;
                    $data->update();

                } 
                else 
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }	
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaybch', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 508)
        {
            $method = PaymentMethod::find(508);
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.dash');

                $req = array(
                'amount' => $data->usd,
                'currency1' => 'USD',
                'currency2' => 'DASH',
                'custom' => $data->custom,
                'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') 
                {

                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_acc'] = $sendadd;
                    $data->update();

                } 
                else 
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }	
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaydash', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 509)
        {

            $method = PaymentMethod::find(509);
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.doge');

                $req = array(
                'amount' => $data->usd,
                'currency1' => 'USD',
                'currency2' => 'DOGE',
                'custom' => $data->custom,
                'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') 
                {

                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_acc'] = $sendadd;
                    $data->update();

                } 
                else 
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }	
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpaydoge', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 510)
        {

            $method = PaymentMethod::find(510);
            if($data->btc_amo==0 ||$data->btc_acc=="")
            {

                $cps = new CoinPaymentHosted();
                $cps->Setup($method->val2,$method->val1);
                $callbackUrl = route('ipn.coinPay.ltc');

                $req = array(
                'amount' => $data->usd,
                'currency1' => 'USD',
                'currency2' => 'LTC',
                'custom' => $data->custom,
                'ipn_url' => $callbackUrl,
                );


                $result = $cps->CreateTransaction($req);

                if ($result['error'] == 'ok') 
                {

                    $bcoin = sprintf('%.08f', $result['result']['amount']);
                    $sendadd = $result['result']['address'];

                    $data['btc_amo'] = $bcoin;
                    $data['btc_acc'] = $sendadd;
                    $data->update();

                } 
                else 
                {
                    return back()->with('alert', 'Failed to Process');
                }

            }	
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            $wallet = $data['btc_acc'];
            $bcoin = $data['btc_amo'];
            $page_title = $method->name;


            $qrurl =  "<img src=\"https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=$wallet&choe=UTF-8\" title='' style='width:300px;' />";
            return view('user.payment.coinpayltc', compact('bcoin','wallet','qrurl','page_title'));

        }
        elseif($data->payment_type == 512) 
        {
			$usd = $data->usd;

			\CoinGate\CoinGate::config(array(
				'environment'               => 'live', // sandbox OR live
				'auth_token'                => $gatewayData->val1
			));

			$post_params = array(
				'order_id'          => $data->custom,
				'price_amount'      => $usd,
				'price_currency'    => 'USD',
				'receive_currency'  => 'USD',
				'callback_url'      => route('ipn.coingate'),
				'cancel_url'        => route('deposit-fund'),
				'success_url'       => route('deposit-fund'),
				'title'             => 'Deposit' . $data->custom,
				'description'       => 'Deposit'
			);

			$order = \CoinGate\Merchant\Order::create($post_params);

            if ($order) 
            {

				return redirect($order->payment_url);
				exit();

            } 
            else 
            {
                return redirect()->route('deposit-fund')->with('alert','Unexpected Error! Please Try Again');
				exit();
			}

		}
        elseif($data->payment_type == 513)
		{
			$all = file_get_contents("https://blockchain.info/ticker");
			$res = json_decode($all);
			$btcrate = $res->USD->last;
			$amon = $data->amount;
			$usd = $data->usd;
			$bcoin = round($usd/$btcrate,8);
			$method = PaymentMethod::find(513);

			$callbackUrl = route('ipn.coinpay');
			$CP = new coinPayments();
			$CP->setMerchantId($method->val1);
			$CP->setSecretKey($method->val2);
			$ntrc = $data->custom;

			$form = $CP->createPayment('Deposit', 'BTC',  $bcoin, $ntrc, $callbackUrl);
            $page_title = $method->name;
			return view('user.payment.coinpay', compact('bcoin','form','page_title','amon','gnl'));
        }
                
    }


    //IPN Functions //////     
        
    public function ipnpaypal()
    {
        
        $raw_post_data = file_get_contents('php://input');
        $raw_post_array = explode('&', $raw_post_data);
        $myPost = array();
        foreach ($raw_post_array as $keyval)
        {
            $keyval = explode ('=', $keyval);
            if (count($keyval) == 2)
            $myPost[$keyval[0]] = urldecode($keyval[1]);
        }
                    
        $req = 'cmd=_notify-validate';
        if(function_exists('get_magic_quotes_gpc'))
        {
            $get_magic_quotes_exists = true;
        }
        foreach ($myPost as $key => $value)
        {
            if($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) 
            {
                $value = urlencode(stripslashes($value));
            } else 
            {
                $value = urlencode($value);
            }
            $req .= "&$key=$value";
        }

        $paypalURL = "https://ipnpb.paypal.com/cgi-bin/webscr?";
        $callUrl = $paypalURL.$req;
        $verify = file_get_contents($callUrl);


            if($verify=="VERIFIED"){

            //PAYPAL VERIFIED THE PAYMENT 
            $receiver_email  = $_POST['receiver_email'];
            $mc_currency  = $_POST['mc_currency'];
            $mc_gross  = $_POST['mc_gross'];
            $track = $_POST['custom'];
            
            //GRAB DATA FROM DATABASE!!
            $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
            $gatewayData = PaymentMethod::find(101);
            $amount = $data->usd;
            
            if($receiver_email==$gatewayData->val1 && $mc_currency=="USD" && $mc_gross ==$amount && $data->status=='0')
            {    
                //Update User Data
                $this->userDataUpdate($data);                
            }
        }
    }
    
    public function ipnperfect()
    {
        
        $gatewayData = PaymentMethod::find(102);
        $passphrase = strtoupper(md5($gatewayData->val2));
        
        define('ALTERNATE_PHRASE_HASH', $passphrase);
        define('PATH_TO_LOG', '/somewhere/out/of/document_root/');
        $string =
        $_POST['PAYMENT_ID'] . ':' . $_POST['PAYEE_ACCOUNT'] . ':' .
        $_POST['PAYMENT_AMOUNT'] . ':' . $_POST['PAYMENT_UNITS'] . ':' .
        $_POST['PAYMENT_BATCH_NUM'] . ':' .
        $_POST['PAYER_ACCOUNT'] . ':' . ALTERNATE_PHRASE_HASH . ':' .
        $_POST['TIMESTAMPGMT'];
        
        $hash = strtoupper(md5($string));
        $hash2 = $_POST['V2_HASH'];

        if ($hash == $hash2) 
        {
            $amo = $_POST['PAYMENT_AMOUNT'];
            $unit = $_POST['PAYMENT_UNITS'];
            $track = $_POST['PAYMENT_ID'];
            
            $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
            
            if ($_POST['PAYEE_ACCOUNT'] == $gatewayData->val1 && $unit == "USD" && $amo == $data->usd && $data->status == '0')
            {
                //Update User Data
                $this->userDataUpdate($data);               
            }
        }
            
    }
                
    public function ipnstripe(Request $request)
    {
        $track = Session::get('Track');
        $data = PaymentLog::where('custom', $track)->orderBy('id', 'DESC')->first();
        
        $this->validate($request,
        [
            'cardNumber' => 'required',
            'cardExpiry' => 'required',
            'cardCVC' => 'required',
        ]);
            
        $cc = $request->cardNumber;
        $exp = $request->cardExpiry;
        $cvc = $request->cardCVC;
        
        $exp = $pieces = explode("/", $_POST['cardExpiry']);
        $emo = trim($exp[0]);
        $eyr = trim($exp[1]);
        $cnts = round($data->usd,2) * 100;
            
        $gatewayData = PaymentMethod::find(103);
        $gnl = BasicSetting::first();
        
        Stripe::setApiKey($gatewayData->val1);
        
        try 
        {
            $token = Token::create(array(
                "card" => array(
                    "number" => "$cc",
                    "exp_month" => $emo,
                    "exp_year" => $eyr,
                    "cvc" => "$cvc"
                    )
                ));
            
            try 
            {
                $charge = Charge::create(array(
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $cnts,
                    'description' => 'item',
                ));
            
                if ($charge['status'] == 'succeeded') {
                    
                    //Update User Data
                    $this->userDataUpdate($data);
                    return redirect()->route('deposit-fund')->with('success', 'Deposit Successful');
                        
                }
                    
            } 
            catch (Exception $e) 
            {
                return redirect()->route('deposit-fund')->with('alert', $e->getMessage());
            }
                
        } 
        catch (Exception $e) 
        {
            return redirect()->route('deposit-fund')->with('alert', $e->getMessage());
        }
                    
    }

    public function skrillIPN()
    {
		$track = Session::get('Track');

        $skrill = PaymentMethod::find(104);
        $concatFields = $_POST['merchant_id']
        . $_POST['transaction_id']
        . strtoupper(md5($skrill->val2))
        . $_POST['mb_amount']
        . $_POST['mb_currency']
        . $_POST['status'];
        
        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
 
        
        if(strtoupper(md5($concatFields)) == $_POST['md5sig'] && $_POST['status'] == 2 && $_POST['pay_to_email'] == $skrill->val1 && $data->status = '0') 
        {
            //Update User Data
            $this->userDataUpdate($data); 
           
        }
    }
    
    public function ipnBchain()
    {
        $gatewayData = PaymentMethod::find(501);
        $track = $_GET['invoice_id'];
        $secret = $_GET['secret'];
        $address = $_GET['address'];
        $value = $_GET['value'];
        $confirmations = $_GET['confirmations'];
        $value_in_btc = $_GET['value'] / 100000000;
    
        $trx_hash = $_GET['transaction_hash'];
    
        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
    
    
        if ($data->status==0) 
        {
            if($data->btc_amo==$value_in_btc && $data->btc_acc==$address && $secret=="ABIR" && $confirmations>2)
            {
               //Update User Data
               $this->userDataUpdate($data);
    
            }
    
        }
    
    }
   
    public function blockIpnBtc(Request $request)
    {
        $DepositData = PaymentLog::where('status', 0)->where('payment_type', 502)->where('try','<=',100)->get();

        $method = PaymentMethod::find(502);
        $apiKey = $method->val1;
        $version = 2; 
        $pin =  $method->val2;
        $block_io = new BlockIo($apiKey, $pin, $version);

        foreach($DepositData as $data)
        {
            $balance = $block_io->get_address_balance(array('addresses' => $data->btc_acc));
            $bal = $balance->data->available_balance;

            if($bal > 0 && $bal >= $data->btc_amo)
            {
               //Update User Data
               $this->userDataUpdate($data);
            }	
            $data['try'] = $data->try + 1;
            $data->update();
        }
    }

    public function blockIpnLite(Request $request)
    {

        $DepositData = PaymentLog::where('status', 0)->where('payment_type', 503)->where('try','<=',100)->get();

        $method = PaymentMethod::find(503);
        $apiKey = $method->val1;
        $version = 2; 
        $pin =  $method->val2;
        $block_io = new BlockIo($apiKey, $pin, $version);


        foreach($DepositData as $data)
        {
            $balance = $block_io->get_address_balance(array('addresses' => $data->btc_acc));
            $bal = $balance->data->available_balance;

            if($bal > 0 && $bal >= $data->btc_amo)
            {
               //Update User Data
               $this->userDataUpdate($data);
            }	
            $data['try'] = $data->try + 1;
            $data->update();
        }
    }
    public function blockIpnDog(Request $request)
    {
        $DepositData = PaymentLog::where('status', 0)->where('payment_type', 504)->where('try','<=',100)->get();

        $method = PaymentMethod::find(504);
        $apiKey = $method->val1;
        $version = 2; 
        $pin =  $method->val2;
        $block_io = new BlockIo($apiKey, $pin, $version);


        foreach($DepositData as $data)
        {
            $balance = $block_io->get_address_balance(array('addresses' => $data->btc_acc));
            $bal = $balance->data->available_balance;

            if($bal > 0 && $bal >= $data->btc_amo)
            {
               //Update User Data
               $this->userDataUpdate($data);
            }	
            $data['try'] = $data->try + 1;
            $data->update();
        }
    }

    public function ipnCoinPayBtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) 
        {
            if ($currency2 == "BTC" && $data->status == '0' && $data->btc_amo<=$amount2) 
            {
                $this->userDataUpdate($data);
            }
        }
    }
    
    public function ipnCoinPayEth(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) 
        {
            if ($currency2 == "ETH" && $data->status == '0' && $data->btc_amo<=$amount2) 
            {
                $this->userDataUpdate($data);
            }
        }
    }
    public function ipnCoinPayBch(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) 
        {
            if ($currency2 == "BCH" && $data->status == '0' && $data->btc_amo<=$amount2) 
            {
                $this->userDataUpdate($data);
            }
        }
    }
    public function ipnCoinPayDash(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) 
        {
            if ($currency2 == "DASH" && $data->status == '0' && $data->btc_amo<=$amount2) 
            {
                $this->userDataUpdate($data);
            }
        }
    }
    public function ipnCoinPayDoge(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) 
        {
            if ($currency2 == "DOGE" && $data->status == '0' && $data->btc_amo<=$amount2) 
            {
                $this->userDataUpdate($data);
            }
        }
    }
    public function ipnCoinPayLtc(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount2 = floatval($request->amount2);
        $currency2 = $request->currency2;

        $data = PaymentLog::where('custom',$track)->orderBy('id', 'DESC')->first();
        $bcoin = $data->btc_amo;
        if ($status>=100 || $status==2) 
        {
            if ($currency2 == "LTC" && $data->status == '0' && $data->btc_amo<=$amount2) 
            {
                $this->userDataUpdate($data);
            }
        }
    }

    public function ipnCoinGate() 
    {
        $data = PaymentLog::where('custom',$_POST['order_id'])->orderBy('id', 'DESC')->first();

        if($_POST['status'] == 'paid' && $_POST['price_amount'] == $data->usd && $data->status == '0') 
        {
			$this->userDataUpdate($data);
		}

	}

    public function ipnCoin(Request $request)
    {
        $track = $request->custom;
        $status = $request->status;
        $amount1 = floatval($request->amount1);
        $currency1 = $request->currency1;

        $data = PaymentLog::where('custom', $track)->orderBy('id','DESC')->first();
        $bcoin = $data->btc_amo;

        if ($currency1 == "BTC" && $amount1 >= $bcoin && $data->status == '0') 
        {
            if ($status>=100 || $status==2) 
            {
                //Update User Data
               $this->userDataUpdate($data);
            }
        }

    }

    
}	
                                        