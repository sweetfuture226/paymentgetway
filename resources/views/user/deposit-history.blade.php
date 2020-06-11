@extends('layouts.user-frontend.user-dashboard')
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
                    <table class="table table-striped table-bordered table-hover" id="sample_1">

                        <thead>
                            <tr>
                                <th>ID#</th>
                                <th>Deposit Date</th>
                                <th>Transaction ID</th>
                                <th>Deposit Method</th>
                                <th>Send Amount</th>
                                <th>Deposit Charge</th>
                                <th>Deposit Amount</th>
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
                                            <span class="label bold label-primary"><i class="fa fa-check"></i> Completed</span>
                                        @elseif($p->status == 0)
                                            <span class="label bold label-warning"><i class="fa fa-spinner"></i> Pending</span>
                                        @elseif($p->status == 2)
                                            <span class="label bold label-danger"><i class="fa fa-times"></i> Cancel</span>
                                        @endif
                                    </td>
    
                                </tr>
                            @endforeach
    
                            </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>



@endsection
