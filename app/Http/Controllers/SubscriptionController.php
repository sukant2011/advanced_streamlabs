<?php

namespace App\Http\Controllers;
use App\Models\Plan;
use App\Models\Subscription;
use App\Traits\BrainTreePaymentTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    use BrainTreePaymentTrait;

    public function index()
    {
        $d = Subscription::with(['user','plan'])->get();
        return view('subscriptions.index')->with(['subscriptions' => Subscription::get()]);
    }

    public function store(Request $request)
    {
        // get the plan after submitting the form
        $plan = Plan::findOrFail($request->plan);    
        // subscribe the user
        $subscriptionData = $this->createSubscription($plan->braintree_plan,$request->payment_method_nonce);  
        $data = array (
                        'user_id' => Auth::user()->id,
                        'plan_id' => $plan->id,
                        'subscription_id' => $subscriptionData->subscription->id,
                        'ends_at' => $subscriptionData->subscription->billingPeriodEndDate,
                        'status' => 1
                    );
        Subscription::create($data);

        // redirect to home after a successful subscription
        return redirect('subscriptions');
    }

    public function cancel(Subscription $subscription)
    {
        //echo '<pre>';print_r($subscription->subscription_id);die;

        $this->cancelSubscription($subscription->subscription_id);

        $subscription->status = 2;
        $subscription->save();

        return redirect('subscriptions');
    }
}
