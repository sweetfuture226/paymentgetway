@extends('layouts.fontEnd')
@section('content')
<!-- breadcrump begin-->
    <div class="hyip-breadcrump extra_padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-8">
                    <div class="breadcrump-title text-center">
                        <h2 class="add-space faq-title">{{ $page_title}}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrump end -->

<!-- faq begin-->
    <div class="faq">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-10">
                    <div class="section-title">
                        <h2 class="add-space">Frequently Asked Question</h2>
                    </div>
                </div>
            </div>
        @foreach($faqs as $key => $f)
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="accordion" id="accordionExample">
                        <div class="row">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                {{ $f->title }}
                                            </button>
                                        </h5>
                                    </div>
    
                                    <div id="collapseOne" class="collapse" data-parent="#accordionExample">
                                        <div class="card-body">
                                            {!!  $f->description !!}
                                        </div>
                                    </div>
                                </div>
    
                            </div>
    
                        </div>
                    </div>
    
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection