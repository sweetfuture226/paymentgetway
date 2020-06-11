@extends('layouts.user-frontend.user-dashboard')
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="page_title">{!! $page_title  !!} </h3>
                    <hr>
                </div>
            </div>
            <div class="row">

                @foreach($plan as $p)

                    <div class="col-sm-4 text-center">
                        <div class="panel panel-primary panel-pricing">
                            <div class="panel-heading">
                                <h3 style="font-size: 28px;"><b>{{ $p->name }}</b></h3>
                            </div>
                            <div style="font-size: 18px;padding: 18px;" class="panel-body text-center">
                                <p><strong>{{ $p->minimum }} {{ $basic->currency }} - {{ $p->maximum }} {{ $basic->currency }}</strong></p>
                            </div>
                            <ul style='font-size: 15px;' class="list-group text-center bold">
                                <li class="list-group-item"><i class="fa fa-check"></i> Commission - {{ $p->percent }} <i class="fa fa-percent"></i> </li>
                                <li class="list-group-item"><i class="fa fa-check"></i> Repeat - {{ $p->time }} times </li>
                                <li class="list-group-item"><i class="fa fa-check"></i> Compound - <span class="aaaa">{{ $p->compound->name }}</span></li>
                            </ul>
                            <div class="panel-footer" style="overflow: hidden">
                                <div class="col-sm-12">
                                    <form method="POST" action="{{ route('investment-post') }}" class="form-inline">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="{{ $p->id }}">
                                        <button type="submit" class="btn btn-primary bold uppercase btn-block btn-icon icon-left">
                                            <i class="fa fa-send"></i> Invest Under This Package
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div><!---ROW-->


    {{--<div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">--}}
        {{--<div class="modal-dialog">--}}
            {{--<div class="modal-content">--}}
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>--}}
                    {{--<h4 class="modal-title bold uppercase" id="myModalLabel"> <i class='fa fa-exclamation-triangle'></i> Confirmation..!</h4>--}}
                {{--</div>--}}

                {{--<div class="modal-body">--}}
                    {{--<strong>Are you sure you want to Invest this Package.?</strong>--}}
                {{--</div>--}}

                {{--<div class="modal-footer">--}}
                    {{--<form method="post" action="{{ route('investment-post') }}" class="form-inline">--}}
                        {{--{!! csrf_field() !!}--}}
                        {{--<input type="hidden" name="id" class="abir_id" value="0">--}}

                        {{--<button type="button" class="btn btn-default bold uppercase" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>--}}
                        {{--<button type="submit" class="btn btn-success bold uppercase"><i class="fa fa-check"></i> Yes Sure.</button>--}}
                    {{--</form>--}}
                {{--</div>--}}

            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}


@endsection
@section('script')

    {{--<script>--}}
        {{--$(document).ready(function () {--}}

            {{--$(document).on("click", '.delete_button', function (e) {--}}
                {{--var id = $(this).data('id');--}}
                {{--$(".abir_id").val(id);--}}
            {{--});--}}
        {{--});--}}
    {{--</script>--}}

    @if (session('success'))
        <script type="text/javascript">
            $(document).ready(function(){

                swal("Success!", "{{ session('success') }}", "success");

            });
        </script>

    @endif



    @if (session('alert'))

        <script type="text/javascript">
            $(document).ready(function(){
                swal("Sorry!", "{{ session('alert') }}", "error");
            });

        </script>

    @endif

@endsection

