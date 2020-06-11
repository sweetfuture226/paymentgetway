@extends('layouts.user-frontend.user-dashboard')

@section('style')
    <link href="{{ asset('assets/admin/css/bootstrap-fileinput.css') }}" rel="stylesheet">

    <style>
        input[type="text"] {
            width: 100%;
        }

        input[type="email"] {
            wi
        }
    </style>
@endsection
@section('content')


    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h3 class="page_title">{!! $page_title  !!} </h3>
            <hr>
        </div>
    </div>


    {!! Form::open(['method'=>'post','role'=>'form','class'=>'form-horizontal','files'=>true]) !!}
    <div class="form-body full_input_types">

        <div class="row">
            <div class="col-md-10 col-md-offset-1">

                <div class="form-group">
                    <label class="col-md-8  col-md-offset-2"><strong style="text-transform: uppercase;">Name :</strong></label>
                    <div class="col-md-8 col-md-offset-2">
                        <input type="text" name="name" id="" value="{{ $user->name }}" class="form-control input-lg" required placeholder="Name">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-8  col-md-offset-2"><strong style="text-transform: uppercase;">User Name :</strong></label>
                    <div class="col-md-8 col-md-offset-2">
                        <input type="text" name="username" id="" value="{{ $user->username }}" class="form-control input-lg" required placeholder="Username">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-8  col-md-offset-2"><strong style="text-transform: uppercase;">Profile Image :</strong></label>
                    <div class="col-md-8 col-md-offset-2">
                        <div class="fileinput fileinput-new" data-provides="fileinput">
                            <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;" data-trigger="fileinput">
                                <img style="width: 200px" src="{{ asset('assets/images') }}/{{ $user->image }}" alt="...">
                            </div>
                            <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
                            <div>
                                                <span class="btn btn-info btn-file">
                                                    <span class="fileinput-new bold uppercase"><i class="fa fa-file-image-o"></i> Select image</span>
                                                    <span class="fileinput-exists bold uppercase"><i class="fa fa-edit"></i> Change</span>
                                                    <input type="file" name="image" accept="image/*">
                                                </span>
                                <a href="#" class="btn btn-danger fileinput-exists bold uppercase" data-dismiss="fileinput"><i class="fa fa-trash"></i> Remove</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-8  col-md-offset-2"><strong style="text-transform: uppercase;">Email :</strong></label>
                    <div class="col-md-8 col-md-offset-2">
                        <input type="email" name="email" id="" value="{{ $user->email }}" class="form-control input-lg" required placeholder="Email">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-8  col-md-offset-2"><strong style="text-transform: uppercase;">Phone :</strong></label>
                    <div class="col-md-8 col-md-offset-2">
                        <input type="text" name="phone" id="" value="{{ $user->phone }}" class="form-control input-lg" required placeholder="Phone">
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-8 col-md-offset-2">
                        <button type="submit" class="btn blue btn-block btn-lg bold"><i class="fa fa-send"></i> UPDATE PROFILE</button>
                    </div>
                </div>

            </div>
        </div>

    </div>
    {!! Form::close() !!}

@endsection
@section('script')
    <script src="{{ asset('assets/admin/js/bootstrap-fileinput.js') }}"></script>

    @if (session('message'))

        <script type="text/javascript">

            $(document).ready(function(){

                swal("Success!", "{{ session('message') }}", "success");

            });

        </script>

    @endif



    @if (session('alert'))

        <script type="text/javascript">

            $(document).ready(function(){

                swal("Sorry!", "{!! session('alert') !!}", "error");

            });

        </script>

    @endif
@endsection
