@extends('layouts.user-frontend.user-dashboard')

@section('content')

    <div class="row">
        <div class="col-md-12">


            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption font-dark"><strong>Deposit Method</strong>
                    </div>
                    <div class="tools"> </div>
                </div>
                <div class="portlet-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 text-center">

                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <h3 style="font-size: 28px;"><b>
                                            @if($fund->payment_type == 1)
                                                Paypal
                                            @elseif($fund->payment_type == 2)
                                                Perfect Money
                                            @elseif($fund->payment_type == 3)
                                                BTC - ( BlockChain )
                                            @else
                                                Credit Card
                                            @endif
                                        </b></h3>
                                </div>
                                <div style="font-size: 18px;padding: 18px;" class="panel-body text-center">
                                    @if($fund->payment_type == 1)
                                        @php $img = $paypal->image @endphp
                                    @elseif($fund->payment_type == 2)
                                        @php $img = $perfect->image @endphp
                                    @elseif($fund->payment_type == 3)
                                        @php $img = $btc->image @endphp
                                    @else
                                        @php $img = $stripe->image @endphp
                                    @endif
                                    <img width="100%" class="image-responsive" src="{{ asset('assets/images') }}/{{ $img }}" alt="">
                                </div>
                                <div class="panel-footer">
                                    <a href="{{ route('deposit-fund') }}" class="btn btn-info btn-block btn-icon icon-left"><i
                                                class="fa fa-arrow-left"></i> Back to Payment Method Page</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="panel panel-info panel-shadow" data-collapsed="0"><!-- to apply shadow add class "panel-shadow" -->

                                <!-- panel head -->
                                <div class="panel-heading">
                                    <div class="panel-title"><i class="fa fa-money"></i> <strong>{{ $page_title }}</strong></div>
                                </div>
                                <!-- panel body -->
                                <div class="panel-body">

                                    <h4 style="text-align: center;"> SEND EXACTLY <strong>{{ $usd }} BTC </strong> TO <strong>{{ $add }}</strong><br>
                                        {!! $code !!} <br>
                                        <strong>SCAN TO SEND</strong> <br><br>
                                        <strong style="color: red;">NB: 3 Confirmation required to Credited your Account</strong>
                                    </h4>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div><!-- ROW-->


@endsection
