<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>{{ $site_title }} - {{ $page_title }}{{--{{ $site_title .' - '. $page_title }}--}}</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1" name="viewport"/>
    <meta content="" name="description"/>
    <meta content="" name="author"/>

    <!-- ASSETS -->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/admin/css/font-awesome.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/admin/css/simple-line-icons.min.css')}}" rel="stylesheet"
          type="text/css"/>
    <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/admin/css/uniform.default.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/admin/css/components-rounded.min.css')}}" rel="stylesheet" id="style_components"
          type="text/css"/>
    <link href="{{ asset('assets/admin/css/jquery.fileupload.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ asset('assets/admin/css/jquery.fileupload-ui.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/admin/css/plugins.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/admin/css/layout.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/admin/css/darkblue.min.css')}}" rel="stylesheet" type="text/css"
          id="style_color"/>
    <link href="{{asset('assets/admin/css/custom.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/admin/css/datatables.min.css')}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset('assets/admin/css/datatables.bootstrap.css')}}"
          rel="stylesheet" type="text/css"/>
    <!-- END ASSETS -->

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/sweetalert.css') }}">

    <link rel="shortcut icon" href="{{asset('assets/images/favicon.png')}}"/>


    <style>
        .page-title{
            font-weight:bold;
            text-transform: uppercase;
        }
        td,th{
            font-weight: bold;
        }
        .panel-body.no-side-padding{
            padding-left: 0;
            padding-right: 0;
        }
        .dashboard-stat .desc.small14, .small14{
            font-size: 14px;
        }
        .dashboard-stat .desc.small13, .small13{
            font-size: 13px;
        }
        .small12{
            font-size: 12px;
        }
    </style>
    @yield('style')

</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo">

<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
    <div class="page-header-inner ">


        <!-- BEGIN LOGO -->
        <div class="page-logo">
            <a href="{{--{{  }}--}}">
                <img src="{!! asset('assets/images/logo.png') !!}" class="logo-default" alt="-"
                     style="filter: brightness(0) invert(1); width: 150px;height: 45px" />

            </a>

            <div class="menu-toggler sidebar-toggler"></div>
        </div>
        <!-- END LOGO -->


        <!-- BEGIN RESPONSIVE MENU TOGGLER -->
        <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse"
           data-target=".navbar-collapse"> </a>

        <div class="top-menu">
            <ul class="nav navbar-nav pull-right">
                <li class="dropdown dropdown-user">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
                       data-close-others="true">


                        <span class="username"> Welcome, {!! Auth::guard('admin')->user()->name !!} </span>
                        <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-default">

                        <li><a href="{!! route('admin-change-password') !!}"><i class="fa fa-cogs"></i> Change Password </a>
                        </li>
                        <li><a href="{!! route('admin.logout') !!}"><i class="fa fa-sign-out"></i> Log Out </a></li>

                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- END HEADER -->


<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"></div>
<div class="page-container">
    <div class="page-sidebar-wrapper">
        <div class="page-sidebar navbar-collapse collapse">


            <ul class="page-sidebar-menu  page-header-fixed " data-keep-expanded="false" data-auto-scroll="true"
                data-slide-speed="200" style="padding-top: 20px">
                <li class="sidebar-toggler-wrapper hide">
                    <div class="sidebar-toggler"></div>
                </li>


                <li class="nav-item start">
                    <a href="{!! route('dashboard') !!}" class="nav-link ">
                        <i class="icon-home"></i><span class="title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{!! route('admin-activity') !!}" class="nav-link ">
                        <i class="fa fa-indent"></i><span class="title">Transaction Log</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{!! route('repeat-history')  !!}" class="nav-link nav-toggle"><i class="fa fa-history"></i>
                        <span class="title">Repeat History</span></a>
                </li>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-cloud-download"></i>
                        <span class="title">Automated Deposit</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('deposit-method')  !!}" class="nav-link nav-toggle"><i class="fa fa-money"></i>
                                <span class="title">Automatic Method</span></a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-university"></i>
                        <span class="title">Bank Deposit</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('bank-method')  !!}" class="nav-link nav-toggle"><i class="fa fa-plus"></i>
                                <span class="title">Add Manual Method</span></a>
                        </li>

                        <li class="nav-item">
                            <a href="{!! route('bank-show')  !!}" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>
                                <span class="title">View Manual Method</span></a>
                        </li>

                        <li class="nav-item">
                            <a href="{!! route('pending-deposit')  !!}" class="nav-link nav-toggle"><i class="fa fa-spinner"></i>
                                <span class="title">Pending Request</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('request-deposit')  !!}" class="nav-link nav-toggle"><i class="fa fa-history"></i>
                                <span class="title">Manual Deposit History</span></a>
                        </li>
                    </ul>
                </li>


                {{--<li class="nav-item">--}}
                    {{--<a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-btc"></i>--}}
                        {{--<span class="title">Manually BTC Deposit</span><span class="arrow"></span></a>--}}
                    {{--<ul class="sub-menu">--}}
                        {{--<li class="nav-item">--}}
                            {{--<a href="{!! route('btc-manual-method-edit', ['id' => 5])  !!}" class="nav-link nav-toggle"><i class="fa fa-money"></i>--}}
                                {{--<span class="title">Edit Manual BTC</span></a>--}}
                        {{--</li>--}}

                        {{--<li class="nav-item">--}}
                            {{--<a href="{!! route('btc-manual-show')  !!}" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>--}}
                                {{--<span class="title">View Manual BTC</span></a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                {{--</li>--}}


                <li class="nav-item">
                    <a href="{!! route('user-deposit-history')  !!}" class="nav-link nav-toggle"><i class="fa fa-history"></i>
                        <span class="title">All Deposit History</span></a>
                </li>




                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-exchange"></i>
                        <span class="title">Manage Withdraw</span><span class="arrow"></span></a>
                    <ul class="sub-menu">

                        <li class="nav-item">
                            <a href="{!! route('withdraw-method')  !!}" class="nav-link nav-toggle"><i class="fa fa-plus"></i>
                                <span class="title">Add Method</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('withdraw-show')  !!}" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>
                                <span class="title">View Method</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('withdraw-confirm-show') !!}" class="nav-link nav-toggle"><i class="fa fa-check"></i>
                                <span class="title">Completed Withdraw</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('withdraw-pending')  !!}" class="nav-link nav-toggle"><i class="fa fa-spinner"></i>
                                <span class="title">Pending Withdraw</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('withdraw-refund-show')  !!}" class="nav-link nav-toggle"><i class="fa fa-times"></i>
                                <span class="title">Refunded Withdraw</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('withdraw-request-all')  !!}" class="nav-link nav-toggle"><i class="fa fa-cloud-upload"></i>
                                <span class="title">Withdraw History</span></a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-list"></i>
                        <span class="title">Investment Plan</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('plan-create') !!}" class="nav-link nav-toggle"><i class="fa fa-plus"></i>
                                <span class="title">Create New Plan</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('plan-show') !!}" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>
                                <span class="title">View All Plan</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-users"></i>
                        <span class="title">Manage User</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('manage-user') !!}" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>
                                <span class="title">All Users</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('show-block-user') !!}" class="nav-link nav-toggle"><i class="fa fa-user-times"></i>
                                <span class="title">Block Users</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('all-verify-user') !!}" class="nav-link nav-toggle"><i class="fa fa-check"></i>
                                <span class="title">Verified Users</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('phone-unverified-user') !!}" class="nav-link nav-toggle"><i class="fa fa-tty"></i>
                                <span class="title">Phone Unverified</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('email-unverified-user') !!}" class="nav-link nav-toggle"><i class="fa fa-envelope-open"></i>
                                <span class="title">Email Unverified</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{!! route('manage-compound') !!}" class="nav-link nav-toggle"><i class="fa fa-sort-amount-asc"></i>
                        <span class="title">Manage Compound</span></a>
                </li>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-handshake-o"></i>
                        <span class="title">Support Ticket</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('admin-support') !!}" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>
                                <span class="title">All Ticket</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('admin-support-pending') !!}" class="nav-link nav-toggle"><i class="fa fa-spinner"></i>
                                <span class="title">Pending Support</span></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-desktop"></i>
                        <span class="title">Web Control</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('basic-setting') !!}" class="nav-link nav-toggle"><i class="fa fa-cogs"></i>
                                <span class="title">Basic Setting</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('contact-setting') !!}" class="nav-link nav-toggle"><i class="fa fa-cogs"></i>
                                <span class="title">Contact Setting</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('email-setting') !!}" class="nav-link"><i class="fa fa-cogs"></i>
                                Email Setting </a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('sms-setting') !!}" class="nav-link"><i class="fa fa-cogs"></i>
                                SMS Setting </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-th"></i>
                        <span class="title">Interface Control</span><span class="arrow"></span></a>
                    <ul class="sub-menu">
                        <li class="nav-item">
                            <a href="{!! route('manage-logo') !!}" class="nav-link"><i class="fa fa-photo"></i>
                                Logo & Favicon</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('manage-footer') !!}" class="nav-link"><i class="fa fa-sitemap"></i>
                                Manage Footer</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('slider') }}" class="nav-link nav-toggle"><i class="fa fa-picture-o"></i>
                                <span class="title">Manage Slider</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-subtitle') }}" class="nav-link nav-toggle"><i class="fa fa-file-text-o"></i>
                                <span class="title">Manage Subtitle</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-about-text') }}" class="nav-link nav-toggle"><i class="fa fa-file-text-o"></i>
                                <span class="title">Manage About Text</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-service') }}" class="nav-link nav-toggle"><i class="fa fa-th"></i>
                                <span class="title">Manage Service</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('manage-social') }}" class="nav-link nav-toggle"><i class="fa fa-share-alt-square"></i>
                                <span class="title">Manage Social</span></a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('menu-control') !!}" class="nav-link"><i class="fa fa-desktop"></i>
                                Menu Control </a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('manage-breadcrumb') !!}" class="nav-link"><i class="fa fa-desktop"></i>
                                Breadcrumb Image</a>
                        </li>
                        <li class="nav-item">
                            <a href="{!! route('manage-about') !!}" class="nav-link"><i class="fa fa-address-card"></i>
                                About Page</a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-clone"></i>
                                <span class="title">Manage Testimonial</span><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="{!! route('testimonial-create') !!}" class="nav-link"><i class="fa fa-plus"></i>
                                        New Testimonial</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{!! route('testimonial-all') !!}" class="nav-link"><i class="fa fa-desktop"></i>
                                        All Testimonial</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle"><i class="fa fa-arrows"></i>
                                <span class="title">Manage FAQS</span><span class="arrow"></span></a>
                            <ul class="sub-menu">
                                <li class="nav-item">
                                    <a href="{!! route('faqs-create') !!}" class="nav-link"><i class="fa fa-plus"></i>
                                        New Question</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{!! route('faqs-all') !!}" class="nav-link"><i class="fa fa-desktop"></i>
                                        All Question</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>


            </ul>
            <!-- END SIDEBAR MENU -->
        </div>
    </div>
    <!-- END SIDEBAR -->


    <!-- BEGIN CONTENT -->
    <div class="page-content-wrapper" >
        <div class="page-content">
            <h3 class="page-title">{!! $page_title  !!} </h3>
            <hr>


            <!--  ==================================VALIDATION ERRORS==================================  -->
            @if($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {!!  $error !!}
                    </div>
                @endforeach
            @endif
            <!--  ==================================SESSION MESSAGES==================================  -->

            @yield('content')


        </div>
    </div>
    <!-- END CONTENT -->
</div>
<!-- END CONTAINER -->


<!-- BEGIN FOOTER -->
<div class="page-footer">
    <div class="page-footer-inner"> <?php echo date("Y")?> All Copyright &copy; Reserved. </div>
    <div class="scroll-to-top">
        <i class="icon-arrow-up"></i>
    </div>
</div>
<!-- END FOOTER -->


<!-- BEGIN SCRIPTS -->
<script src="{{asset('assets/admin/js/jquery.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/bootstrap.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/bootstrap-hover-dropdown.min.js')}}"type="text/javascript"></script>
<script src="{{asset('assets/admin/js/jquery.slimscroll.min.js')}}"type="text/javascript"></script>
<script src="{{asset('assets/admin/js/jquery.blockui.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/app.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/layout.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/demo.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/quick-sidebar.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/datatable.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/admin/js/datatables.bootstrap.js')}}"type="text/javascript"></script>
<script src="{{asset('assets/admin/js/table-datatables-buttons.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/sweetalert.min.js') }}"></script>
<script>
    @if (session()->has('message'))
        swal({
            title: "{!! session()->get('title')  !!}",
            text: "{!! session()->get('message')  !!}",
            type: "{!! session()->get('type')  !!}",
            confirmButtonText: "OK"
        });
        @php session()->forget('message') @endphp
    @endif

</script>

@yield('scripts')


</body>
</html>