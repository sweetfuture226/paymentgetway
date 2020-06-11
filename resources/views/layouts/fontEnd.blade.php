<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $site_title }} | {{ $page_title }}</title>
    <!--Favicon add-->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    <!-- bootstrap -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <!--font-awesome Css-->
    <link href="{{ asset('assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <!--owl.carousel Css-->
    <link href="{{ asset('assets/css/owl.carousel.css') }}" rel="stylesheet">
    <!-- rangeslider Css-->
    <link href="{{ asset('assets/css/asRange.css') }}" rel="stylesheet">
    <!-- magnific popup -->
    <link href="{{ asset('assets/css/magnific-popup.css')}}">
    <!--Style Css-->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <!--Responsive Css-->
    <link href="{{ asset('assets/css/color.php?color='.$basic->color) }}" rel="stylesheet">
    <!--Responsive Css-->
    <link href="{{ asset('assets/css/responsive.css') }}" rel="stylesheet">
    <!-- flaticon css -->
    <link href="{{ asset('assets/fonts/flaticon.css') }}" rel="stylesheet">
    <!-- animate.css -->
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <script type="text/javascript">
function googleTranslateElementInit() { new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
    @yield('style')
</head>
<body >
  <!-- preloader begin-->
        <div class="sec">
            <div class="loader">
                <div class="circle item0"></div>
                <div class="circle item1"></div>
                <div class="circle item2"></div>
            </div>
        </div>
        <!-- preloader end -->
            <div class="header">
            <div class="header-top">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-5 col-lg-5">
                            <div class="support-bar-left">
                                <ul>
                                    <li><span><i class="fas fa-phone-square"></i></span>+0123456789</li>
                                    <li><span><i class="fas fa-envelope"></i></span>{{ $basic->email }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5">

                            <div id="google_translate_element" class="support-bar-right">
                                <ul> 
                                    <li><a href="{{ route('support-open') }}"><i class="fa fa-comment"></i> Get Support</a></li>
                                    <li>Running Since: {{ \Carbon\Carbon::parse($basic->start_date)->diffInDays() }} Days</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-5 col-lg-5">
                        </div>
                        
                    </div>
                </div>
            </div>
            <div class="header-bottom">
                <div class="container">
                    <div class="row justify-content-between">
                        <div class="col-xl-3 col-lg-3 d-xl-flex d-lg-flex d-block align-items-center">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-7 d-xl-block d-lg-block d-flex align-items-center">
                                    <div class="logo-img">
                                        <a href="{{ route('home') }}"><img src="assets/images/logo.png" alt=""></a>
                                    </div>
                                </div>
                                <div class="d-xl-none d-lg-none d-block col-5">
                                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav"
                                        aria-expanded="false" aria-label="Toggle navigation">
                                        <i class="fas fa-bars"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8 col-lg-9 d-xl-flex d-lg-flex d-block align-items-center">
                            <div class="mainmenu">
                                <nav class="navbar navbar-expand-lg">
                                    
                                    <div class="collapse navbar-collapse" id="navbarNav">
                                        <ul class="navbar-nav" class="nav-item">
                                             <li class="nav-item">
                                                <a class="nav-link" href="{{ route('home') }}">Home</a></li>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="{{ route('about') }}">About</a></li>
                                            </li>
                                            <li class="nav-item">
                                                @foreach($menu as $m)
                                                <li class="nav-item">
                                                <a class="nav-link" href="{{ url('menu') }}/{{ $m->id }}/{{ urldecode(strtolower(str_slug($m->name))) }}">{{ $m->name }}</a></li>
                                                @endforeach
                                            </li>
                                           <li class="nav-item">
                                                <a class="nav-link"href="{{ route('faqs') }}">Faq</a>
                                            </li>
                                           <li class="nav-item">
                                                <a class="nav-link"href="{{ route('contact') }}">Contact</a>
                                            </li>
                                           @if(Auth::check())
                                           <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Hi. {{ Auth::user()->name }}
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="navbarDropdown3">
                                                    <a class="dropdown-item" href="{{ route('user-dashboard') }}">Dashboard</a>
                                                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="">Log Out</a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                    {{ csrf_field() }}
                                                    </form>
                                                </div>
                                            </li>
                                            @else
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown3" role="button" data-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">
                                                    Account
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="navbarDropdown3">
                                                    <a class="dropdown-item" href="{{ route('login') }}">Login</a>
                                                    <a class="dropdown-item" href="{{ route('register') }}">Register</a>
                                                </div>
                                            </li>
                                             @endif
                                        </ul>
                                    </div>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- header end -->


@yield('content')

        <!-- footer begin-->
        <div class="footer">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="about-area">
                                <a href="{{ route('home') }}"><img src="{{ asset('assets/images/logo.png') }}" alt="Footer Logo"></a>
                                <p>{{ $basic->footer_text }}</p>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3">
                            <div class="useful-link">
                                <h3>Company Info</h3>
                                <ul>
                                    <li><a href="#"><span><i class="fas fa-chevron-right"></i></span>About Company</a></li>
                                    <li><a href="#"><span><i class="fas fa-chevron-right"></i></span>Investment Plans</a></li>
                                    <li><a href="#"><span><i class="fas fa-chevron-right"></i></span>Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-md-3">
                            <div class="useful-link">
                                <h3>Member Area</h3>
                                <ul>
                                    <li><a href="#"><span><i class="fas fa-chevron-right"></i></span>About Company</a></li>
                                    <li><a href="#"><span><i class="fas fa-chevron-right"></i></span>Investment Plans</a></li>
                                    <li><a href="#"><span><i class="fas fa-chevron-right"></i></span>Privacy Policy</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-6">
                            <div class="secure-area">
                                <h3>We Are Secure</h3>
                                <div class="logo">
                                    <div class="row">
                                        @foreach($pay as $key => $p)
                                <div class="col-md-3 {{ $key==0? 'col-md-offset-3' : '' }} col-sm-6">
                                    <div class="payment-logo">
                                        <img style="width: 190px" src="{{ asset('assets/images') }}/{{ $p->image }}"  alt="Payment Method Logo">
                                    </div>
                                </div>
                                @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 d-xl-flex d-lg-flex align-items-center">
                            <div class="copyright">
                                <p>{!! $basic->copy_text !!}</p>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6">
                            <div class="social">
                                <div class="social-link">
                            @foreach($social as $s)
                                <a href="{{ $s->link }}">{!!  $s->code  !!}</a>
                            @endforeach
                        </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- footer end -->























    <!-- jquery -->
        <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery-migrate-3.0.1.js') }}"></script>
        <!-- bootstrap -->
        <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <!-- owl carousel -->
        <script src="{{ asset('assets/js/owl.carousel.js') }}"></script>

        <!-- magnific popup -->
        <script src="{{ asset('assets/js/jquery.magnific-popup.js') }}"></script>
        <!-- counter up js -->
        <script src="{{ asset('assets/js/jquery.counterup.min.js') }}"></script>
        <!-- way poin js-->
        <script src="{{ asset('assets/js/waypoints.min.js') }}"></script>
        <!--Main js file load-->
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <!--js file load-->
        <script src="{{ asset('assets/js/jquery.js') }}"></script>

        <!-- wow js-->
        <script src="{{ asset('assets/js/wow.min.js') }}"></script>



</body>
</html>
