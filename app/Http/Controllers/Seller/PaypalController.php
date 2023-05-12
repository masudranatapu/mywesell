<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Order;
use App\Models\Product;
use App\Models\MySellerPackage;
use Auth;
use Carbon\Carbon;
use DB;
use App\Models\User;

class PaypalController extends Controller
{
    //

    public function processTransaction(Request $request)
    {
        // dd($request->package_id);

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
        $packages = MySellerPackage::findOrFail($request->package_id);

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
        // dd($paypalToken);
        try {
            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('seller.paypal.successTransaction', [
                        'package_id' => $request->package_id,
                        'amount' => $packages->amount,
                    ]),
                    "cancel_url" => route('seller.paypal.cancelTransaction'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => 'USD',
                            "value" => $packages->amount,
                        ]
                    ]
                ]
            ]);
        } catch (\Throwable $th) {
            dd($th);
        }

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
        // dd($request->all());
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);


        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // dd($request->all());
            // After Payment Successfully
            $package_id = $request->package_id;
            $amount = $request->amount;
            $transaction_id = $response['id'] ?? uniqid('tr_');
            $this->orderPlacing($package_id, $amount, $transaction_id);
            flash("Payment successfully done.")->success();

            return redirect()->route('seller.dashboard');
        } else {
            flash('error', 'Transaction is Invalid')->error();
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
        flash('error', 'Payment Failed')->error();
        return back();
    }
    public function orderPlacing($package_id, $amount, $transaction_id)
    {
        $latest_id = DB::table('package_transactions')->latest()->first();
        if(isset($latest_id)) {
            $order_code = "O-".sprintf('%05d', $latest_id->id + 1);
        }else {
            $order_code = "O-".sprintf('%05d', 1);
        }

        DB::table('package_transactions')->insert([
            'user_id' => Auth::user()->id,
            'order_id' => $order_code,
            'transactions_id' => $transaction_id,
            'package_id' => $package_id,
            'amount' => $amount,
            'payment_status' => 'paid',
            'payment_mathod' => 1,
            'status' => 1,
            'created_at' => Carbon::now(),
        ]);

        User::where('id', Auth::user()->id)->update([
            'customer_package_id' => $package_id,
        ]);


    }

}
