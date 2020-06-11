@extends('layouts.user-frontend.user-dashboard')

@section('style')

    <link href="{{ asset('assets/admin/css/bootstrap-toggle.min.css') }}" rel="stylesheet">

@endsection
@section('content')


    <div class="row">
        <div class="col-md-12">

            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption">
                        <strong>{{ $page_title }}</strong>
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"> </a>
                        <a href="javascript:;" class="remove"> </a>
                    </div>
                </div>
                <div class="portlet-body" style="overflow: hidden;">
               
                    <div class="col-md-8 col-sm-12 col-md-offset-2">
                        {!! Form::open(['role'=>'form','id'=>'formSubmit','class'=>'form-horizontal','files'=>true]) !!}

                                <div class="form-body">

 <div class="row">

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;"> SUBJECT :</strong></label>
                                            <div class="col-md-12">
                                                
                                                    <input class="form-control bold input-lg" id="subject" name="subject" value="" type="text" placeholder="Subject" required style="width: 100%" >
                                                  
                                               
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="col-md-12"><strong style="text-transform: uppercase;"> MESSAGE :</strong></label>
                                            <div class="col-md-12">
                                                <textarea name="message" id="" cols="30" rows="6"
                                                          class="form-control bold input-lg" placeholder="Message" required></textarea style="width: 100%" >
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-primary btn-lg btn-block btn-block margin-top-10 delete_button"
                                                        data-toggle="modal" data-target="#DelModal">
                                                    <i class="fa fa-send"></i> Confirm and Open
                                                </button>
                                            </div>
                                        </div>
                                    </div>
</div>

                                </div>
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>

        </div>
    </div><!---ROW-->

    <div class="modal fade" id="DelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel"> <i class='fa fa-exclamation-triangle'></i><strong>Confirmation..!</strong> </h4>
                </div>
                <div class="modal-body">
                    <strong>Are you sure you want to Open a Support Ticket..?</strong>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" id="btnYes" class="btn btn-primary"><i class="fa fa-check"></i> Yes I'm Sure..!</button>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('script')

    <script type='text/javascript'>
        $('#btnYes').click(function() {
            $('#formSubmit').submit();
        });
    </script>

    <script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js') }}"></script>




@endsection