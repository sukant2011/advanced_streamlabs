<?php

namespace App\Http\Controllers;
use App\Traits\BrainTreePaymentTrait;
use Illuminate\Http\Request;

class BraintreeTokenController extends Controller
{
    use BrainTreePaymentTrait;

    public function token()
    {
        return response()->json([
            'token' => $this->generateToken()
        ]);
    }
}
