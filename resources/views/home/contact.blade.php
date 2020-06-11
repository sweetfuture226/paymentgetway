@extends('layouts.fontEnd')
@section('content')

    <!-- breadcrump begin-->
    <div class="hyip-breadcrump">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8">
                    <div class="breadcrump-title text-center">
                        <h2>Contact Us</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrump end -->

<center><!-- contact begin-->
    <div class="contact">
        <div class="part-address">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-adress">
                            <div class="part-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="part-text">
                                <h3>Email Address</h3>
                                <ul>
                                    <li>contact@hyiprex.com</li>
                                    <li>support@hyiprex.com</li>
                                </ul>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-adress">
                            <div class="part-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="part-text">
                                <h3>Phone Number</h3>
                                <ul>
                                    <li>+012-345-67890</li>
                                    <li>+012-345-67890</li>
                                </ul>
                            </div>
                        </div>
                    </div>
    
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="single-adress">
                            <div class="part-icon">
                                <i class="fas fa-map"></i>
                            </div>
                            <div class="part-text">
                                <h3>Office Address</h3>
                                <ul>
                                    <li>ABC road street, Cool City</li>
                                    <li>My State, Our Country</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="get-in-touch">
            <div class="container">
               
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="part-form">
                            <form>
                                <div class="col-md-8 col-md-offset-2">

                                <div class="contact-icon">
                                    <i class="fa fa-envelope-o"></i>
                                </div>
                                <h2 class="title">Send Your Message</h2>
                                <br>
                                @if($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissable">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            {!!  $error !!}
                                        </div>
                                    @endforeach
                                @endif
                                @if (session()->has('message'))
                                    <div class="alert alert-success alert-dismissable">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                                <br>
                                <form action="{{ route('contact-submit') }}" method="post">
                                    {!! csrf_field() !!}
                                    <input type="text" class="name" name="name" required placeholder="Name">
                                    <input type="email" class="email" name="email" required placeholder="Email">
                                    <br>
                                    <br>
                                    <input type="text" class="name" name="subject" required placeholder="Subject">
                                    <input type="text" class="email" name="phone" required placeholder="Phone Number">
                                    <textarea name="message" id="message" cols="30" rows="10" required placeholder="Message"></textarea>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="submit" value="Send Message Now!">
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!--Contact Form end--></center>

@endsection