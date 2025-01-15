<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $YOUR_DOMAIN = 'http://localhost:4242';

        $checkout_session = Session::create([
            'line_items' => [
                [
                    'price' =>$request->item_price,
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
            'automatic_tax' => [
                'enabled' => true,
            ],
        ]);

        return response()->json(['id' => $checkout_session->id]);
    }
}