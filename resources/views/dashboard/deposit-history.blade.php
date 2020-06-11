@extends('layouts.dashboard')
@section('content')




        <div class="row">
            <div class="col-md-12">


                <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption font-dark">
                        </div>
                        <div class="tools"> </div>
                    </div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover" id="samle_1">

                            <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Deposit Date</th>
                                <th>Transaction ID</th>
                                <th>Depositor User Name</th>
                                <th>Deposit Method</th>
                                <th>Send Amount</th>
                                <th>Deposit Charge</th>
                                <th>Deposit Balance</th>
                                <th>Status</th>
                            </tr>
                            </thead>

                            <tbody>
                            @php $i=0;@endphp
                            @foreach($deposit as $p)
                                @php $i++;@endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ date('d-F-Y h:i A',strtotime($p->created_at))  }}</td>
                                    <td>{{ $p->transaction_id }}</td>
                                    <td>{{ $p->member->username }}</td>
                                    <td>
									<span class="label bold label-primary">{{$p->bank->name}}</span>
                                    </td>
                                    <td>
                    
                                         {{ $p->net_amount }} - {{ $basic->currency }}
                 
                                    </td>
                                    <td>
                   
                                            {{ $p->charge }} - {{ $basic->currency }}
                               
                                    </td>
                                    <td>
                     
                                            {{ $p->amount }} - {{ $basic->currency }}
                                       
                                    </td>
                                    <td>
                                        @if($p->status == 1)
                                            <span class="label label-primary  bold uppercase"><i class="fa fa-check"></i> Completed</span>
                                        @elseif($p->status == 2)
                                            <span class="label label-danger  bold uppercase"><i class="fa fa-times"></i> Cancel</span>
                                        @else
                                            <span class="label label-warning  bold uppercase"><i class="fa fa-spinner"></i> Pending</span>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {!! $deposit->links() !!}
                        </div>
                    </div>
                </div>

            </div>
        </div><!-- ROW-->



@endsection
