@extends('layouts.fontEnd')
@section('style')

    <link rel="stylesheet" href="{{ asset('assets/css/ion.rangeSlider.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ranger-style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ion.rangeSlider.skinFlat.css') }}">
    <style>
        .price-table {
            margin-bottom: 45px;
        }
    </style>

@endsection
@section('content')
<!-- banner begin-->
<div class="banner-slider">
     @foreach($slider as $s)
            <div class="banner slider header-bg" style="background-image: url('{{ asset('assets/images/slider') }}/{{ $s->image }}')">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-10">
                             <div class="banner-content">
                                <h1>{{ $s->title }}</h1>
                                <p>{{ $s->subtitle }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endforeach
        </div>
        <!-- banner end -->
 <!-- feature begin-->
        <div class="feature">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-8">
                        <div class="section-title">
                            <h2>Our Services</h2>
                            <p>{!! $page->service_subtitle !!}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach($service as $s)
                    <div class="col-xl-2 col-lg-2 col-md-6">
                        <div class="single-feature">
                            <div class="part-icon">
                                {!! $s->code !!}
                            </div>
                            <div class="part-text">
                                <p>{{ $s->title }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach 
                </div>
            </div>
        </div>
        <!-- feature end -->



<!-- about begin-->
        <div class="about">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-lg-10">
                        <div class="about-content">
                            <div class="part-text">
                                <h3>About - {{ $site_title }}</h3>
                                <p>{!! $page->about_leftText !!}</p>
                                <p>{!! $page->about_rightText !!}</p>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- about begin -->



 <!-- price begin-->
        <div class="price">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-8">
                        <div class="section-title">
                            <h2>Investment Plans</h2>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incint
                                ut labore et am, quis nostrud exercitation ullamco laboris nisi ut.</p>
                        </div>
                    </div>
                </div>
                       
                                <div class="row">
                                 @foreach($plan as $p)
                                    <div class="col-xl-4 col-lg-4 col-md-6">
                                        <div class="single-price">
                                            <div class="part-top">
                                                <h3>{{ $p->name }}</h3>
                                                <h4>2%<br /><span>Daily for 75 Days</span></h4>
                                            </div>
                                            <div class="part-bottom">
                                                <ul>
                                                    <li><p> <span class="color-text">{{ $p->compound->name }}</span> Return</p></li>
                                                    <li><p>for <span class="color-text">{{ $p->time }}</span> times</p></li>
                                                    <li><p> <span class="color-text">{{ $p->percent }}%</span> roi each time</p></li>
                                                    <li><h6>Minimum<span class="color-text">{{ $basic->symbol }} {{ $p->minimum }}</span></h6></li>
                                                    <li><h6>Maximum<span class="color-text">{{ $basic->symbol }} {{ $p->maximum }}</span></h6></li>
                                                    <li><h6>per time<span class="color-text"><b class="daily-profit-{{ $p->id }}">{{ $basic->symbol }} {{ (($p->minimum + $p->maximum) / 2 ) * $p->percent /100 }}.0</b></span></h6></li>
                                                    <li><h6>Total Return<span class="color-text"><b class="total-profit-{{ $p->id }}">{{ $basic->symbol }} {{ (((($p->minimum + $p->maximum) / 2) * $p->percent) /100 ) * $p->time }}.0</b></span></h6></li>
                                                    <li>24/7Support</li>
                                                </ul>
                                                <a href="{{ route('register') }}">Buy Now</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                        </div>
                </div>
<!--Priicing End -->


<!-- testimonial begin-->
        <div class="testimonial">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-8">
                        <div class="section-title">
                            <h2 class="add-space">What People Says</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="testimonial-slider">
                            @foreach($testimonial as $tes)
                            <div class="single-testimonial">
                                <div class="testimonial-top">
                                    <div class="part-icon">
                                        <i class="fas fa-quote-left"></i>
                                    </div>
                                    <div class="part-text">
                                        <p>{!! $tes->message !!}</p>
                                    </div>
                                </div>
                                <div class="part-details">
                                    <div class="user-pic">
                                        <img src="{{ asset('assets/images') }}/{{ $tes->image }}" class="img-circle img-responsive" alt="Client's Profile Pic">
                                    </div>
                                    <div class="user-data">
                                        <span class="name"><h4>{{ $tes->name }}<span>{{ $tes->position }}</span></h4></span>
                                    </div>
                                </div>
                            </div>
                             @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- testimonial end -->


<!--Deopsit and Payouts section start-->
<section class="section-padding">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-6">
                <div class="deposit-table">
                    <div class="deposit-title">
                        <h4>Latest Deposits</h4>
                    </div>
                    <div class="deposit-body">
                        <table class="table main-table">

                            <tbody>
                            <tr class="head">
                                <th>Name</th>
                                <th>Date</th>
                                <th>Currency</th>
                                <th>Amount</th>
                            </tr>
                            @foreach($latest_deposit as $ld)
                            <tr>
                                <td>{{ $ld->member->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($ld->created_at)->format('M d,Y') }}</td>
                                <td><strong>{{ $basic->currency }}</strong></td>
                                <td><strong>{{ $basic->symbol }}{{ $ld->amount }}</strong></td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="deposit-table">
                    <div class="deposit-title">
                        <h4>Latest Withdraw</h4>
                    </div>
                    <div class="deposit-body">
                        <table class="table main-table">

                            <tbody>
                            <tr class="head">
                                <th>Name</th>
                                <th>Date</th>
                                <th>Currency</th>
                                <th>Amount</th>
                            </tr>
                            @foreach($latest_withdraw as $ld)
                                <tr>
                                    <td>{{ $ld->user->name }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ld->created_at)->format('M d,Y') }}</td>
                                    <td><strong>{{ $basic->currency }}</strong></td>
                                    <td><strong>{{ $basic->symbol }}{{ $ld->amount }}</strong></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section><!--Deopsit and Payouts Section End-->


@endsection
@section('script')
    <script type="text/javascript" src="{{ asset('assets/js/ion.rangeSlider.js') }}"></script>
    <script>
        $.each($('.slider-input'), function() {
            var $t = $(this),

                    from = $t.data('from'),
                    to = $t.data('to'),

                    $dailyProfit = $($t.data('dailyprofit')),
                    $totalProfit = $($t.data('totalprofit')),

                    $val = $($t.data('valuetag')),

                    perDay = $t.data('perday'),
                    perYear = $t.data('peryear');


            $t.ionRangeSlider({
                input_values_separator: ";",
                prefix: '{{ $basic->symbol }} ',
                hide_min_max: true,
                force_edges: true,
                onChange: function(val) {
                    $val.val( '{{ $basic->symbol }} ' + val.from);

                    var profit = (val.from * perDay / 100).toFixed(1);
                    profit  = '{{ $basic->symbol }} ' + profit.replace('.', '.') ;
                    $dailyProfit.text(profit) ;

                    profit = ( (val.from * perDay / 100)* perYear ).toFixed(1);
                    profit  =  '{{ $basic->symbol }} ' + profit.replace('.', '.');
                    $totalProfit.text(profit);

                }
            });
        });
        $('.invest-type__profit--val').on('change', function(e) {

            var slider = $($(this).data('slider')).data("ionRangeSlider");

            slider.update({
                from: $(this).val().replace('{{ $basic->symbol }} ', "")
            });
        })
    </script>
@endsection