@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Subscriptions</div>

                <div class="panel-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Subscription ID</th>
                            <th scope="col">Plan Name</th>
                            <th scope="col">Price</th>
                            <th scope="col">Customer Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscriptions as $subscription)
                            <tr>
                                <th scope="row">{{ $subscription->subscription_id }}</th>
                                <td>{{ $subscription->plan->name }}</td>
                                <td>{{ $subscription->plan->cost }}</td>
                                <td>{{ $subscription->user->name }}</td>
                                <td>{{ $subscription->status == '2' ? 'Cancelled' : 'Active'  }}</td>
                                @if ($subscription->status == '1')
                                    <td><a href="{{ url('/subscription/cancel', $subscription->id) }}" class="btn btn-default pull-right">Cancel</a></td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection