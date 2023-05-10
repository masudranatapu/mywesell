<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    //
    
    public function processTransaction(Request $request)
    {
        dd($request->all());

        // $promotion = Promotion::find($request->promotions_id);
        // $ad_id = $request->ad_id;
        // session()->put('ad_id', $ad_id);
        // session()->put('promotion_id', $promotion->id);
        // $converted_amount = currencyConversion($promotion->price);

        // session(['order_payment' => [
        //     'payment_provider' => 'paypal',
        //     'amount' =>  $converted_amount,
        //     'currency_symbol' => getCurrencySymbol(),
        //     'usd_amount' =>  $converted_amount,
        // ]]);

        $provider = new PayPalClient;
        dd($provider);
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('paypal.successTransaction', [
                    'plan_id' => $ad_id,
                    'amount' => $converted_amount
                ]),
                "cancel_url" => route('paypal.cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => getCurrencyCode(),
                        "value" => $converted_amount,
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            session()->flash('error', 'Something went wrong.');
            return back();
        } else {
            session()->flash('error', 'Something went wrong.');
            return back();
        }
    }


    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // After Payment Successfully
            session(['transaction_id' => $response['id'] ?? null]);
            $this->orderPlacing();
        } else {
            session()->flash('error', 'Transaction is Invalid');
            return back();
        }
    }

    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        session()->flash('error', 'Payment Failed');
        return back();
    }

}
