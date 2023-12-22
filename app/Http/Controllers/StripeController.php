<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripeController extends Controller
{
    /**
     * Creates an intent for payment so we can capture the payment
     * method for the user.
     *
     * @param Request $request The request data from the user.
     */
    public function getSetupIntent(Request $request)
    {
        return $request->user()->createSetupIntent();
    }

    public function postPaymentMethods(Request $request)
    {

        $paymentMethodID = $request->get('payment_method');

        $user = $request->user();
        if($user->stripe_id == null) {
            $user->createAsStripeCustomer();
        }

        $user->addPaymentMethod($paymentMethodID);
        $user->updateDefaultPaymentMethod($paymentMethodID);

        return response()->json(null, 204);
    }
}