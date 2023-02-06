<?php
 
namespace App\Traits;
use Braintree;
use Illuminate\Support\Facades\Auth;

trait BrainTreePaymentTrait {

    private function getAllPlans(){
        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $result = $gateway->plan()->all();
        return $result;
    }

    private function generateToken(){
        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $braintreeToken = $gateway->ClientToken()->generate();
        return $braintreeToken;
    }


    private function createSubscription($planId, $braintree_nonce)
    {
        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);
        $name = Auth::user()->name;

        $extract_name = explode(' ',$name);

        $customer['first_name'] = @$extract_name[0];
        $customer['last_name'] = @$extract_name[1];
        $customer['email'] = Auth::user()->email;
        $customer['paymentMethodNonce'] = $braintree_nonce;

        $customerData = json_decode($this->createCustomer($customer), True);
    
        $postData = [
                        'paymentMethodToken' => $customerData['payment_method_token'],
                        'planId' => $planId
                    ];

        $response = $gateway->subscription()->create($postData);
        return $response;
    } 

    private function createCustomer($customer){
        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);
        $customerResult = $gateway->customer()->create([
            'firstName' => $customer['first_name'],
            'lastName' => $customer['last_name'],
            'email' => $customer['email'],
            'paymentMethodNonce' => $customer['paymentMethodNonce'],
        ]);

        if($customerResult->success){
            $customer_id = $customerResult->customer->id;
            $braintree_method_token = $customerResult->customer->paymentMethods[0]->token;
        }
        return json_encode([
            'customer_id' => $customer_id,
            'payment_method_token' => $braintree_method_token
        ]);
    }

    public function cancelSubscription($subscriptionId)
    {
        $gateway = new Braintree\Gateway([
            'environment' => env('BRAINTREE_ENV'),
            'merchantId' => env('BRAINTREE_MERCHANT_ID'),
            'publicKey' => env('BRAINTREE_PUBLIC_KEY'),
            'privateKey' => env('BRAINTREE_PRIVATE_KEY')
        ]);

        $result = $gateway->subscription()->cancel($subscriptionId);
    }
}
 