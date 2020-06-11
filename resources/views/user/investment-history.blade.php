@extends('layouts.user-frontend.user-dashboard')
@section('style')
    <style>
        span.label{
            font-size: 12px; !important;
        }
    </style>
@endsection
@section('content')


    <table class="table table-striped table-bordered table-hover" id="sample_1">
        <thead>
        <tr>
            <th>Sl No</th>
            <th>Date Time</th>
            <th>Transaction ID</th>
            <th>Invest Plan</th>
            <th>Invest Amount</th>
            <th>Invest Commission</th>
            <th>Repeat Time</th>
            <th>Repeat Compound</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @php $i = 0;@endphp
        @foreach($history as $p)
            @php $i++;@endphp
            <tr>
                <td>{{ $i }}</td>
                <td width="10%">{{ date('d-F-Y h:s:i A',strtotime($p->created_at)) }}</td>
                <td>#{{ $p->trx_id }}</td>
                <td><span class="aaaa"><strong>{{ $p->plan->name }}</strong></span></td>
                <td>{{ $p->amount }} - {{ $basic->currency }}</td>
                <td>{{ $p->plan->percent }} %</td>
                <td>{{ $p->plan->time }} - Times</td>
                <td><span class="aaaa"><strong>{{ $p->plan->compound->name }}</strong></span></td>
                <td>
                    @if($p->status == 0)
                        <span class="label label-warning bold uppercase"><i class="fa fa-spinner"></i> Running</span>
                    @else
                        <span class="label label-success bold uppercase"><i class="fa fa-check" aria-hidden="true"></i> Completed</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>

    </table>


@endsection
@section('script')


@endsection