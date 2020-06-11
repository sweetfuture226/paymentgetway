@extends('layouts.user-frontend.user-dashboard')

@section('content')


        <div class="row">
            <div class="col-md-12">


                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <a href="{{ route('support-open') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Open New Support Ticket</a>
                        </div>
                        <div class="tools"> </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="sample_1">

                            <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Date</th>
                                <th>Ticket Number</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php $i=0;@endphp
                            @foreach($support as $p)
                                @php $i++;@endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->created_at)->format('d F Y h:i A') }}</td>
                                    <td>{{ $p->ticket_number }}</td>
                                    <td>{{ $p->subject }}</td>
                                    <td>
                                        @if($p->status == 1)
                                            <span class="label bold label-info"><i class="fa fa-eye"></i> Open</span>
                                        @elseif($p->status == 2)
                                            <span class="label bold label-success"><i class="fa fa-check"></i> Answer</span>
                                        @elseif($p->status == 3)
                                            <span class="label bold bg-purple bg-font-purple"><i class="fa fa-mail-reply"></i> Customer Reply</span>
                                        @elseif($p->status == 9)
                                            <span class="label bold label-danger"><i class="fa fa-times"></i> Close</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('support-message',$p->ticket_number) }}" class="btn btn-primary"><i class="fa fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div><!-- ROW-->



@endsection
@section('script')

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>


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

