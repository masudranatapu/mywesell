<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\MySellerPackage;
use Auth;
use Carbon\Carbon;
use DB;
use App\Models\User;
use Srmklive\PayPal\Services\PayPal as PayPalClient;


class DashboardController extends Controller
{
    public function index()
    {
        $data['packageName'] = MySellerPackage::findOrFail(Auth::user()->customer_package_id);
        // dd($data['packageName']);
        $data['products'] = filter_products(Product::where('user_id', Auth::user()->id)->orderBy('num_of_sale', 'desc'))->limit(12)->get();
        $data['last_7_days_sales'] = Order::where('created_at', '>=', Carbon::now()->subDays(7))
                                ->where('seller_id', '=', Auth::user()->id)
                                ->where('delivery_status', '=', 'delivered')
                                ->select(DB::raw("sum(grand_total) as total, DATE_FORMAT(created_at, '%d %b') as date"))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->get()->pluck('total', 'date');  

        return view('seller.dashboard', $data);
    }

    public function packages()
    {
        $packages = MySellerPackage::latest()->get();
        $current_packageName = MySellerPackage::findOrFail(Auth::user()->customer_package_id);
        return view('seller.packages', compact('packages', 'current_packageName'));
    }

    public function packagesUpgrade()
    {
        $packages = MySellerPackage::findOrFail(request()->get('packages'));
        return view('seller.packages_upgrade', compact('packages'));
    }

    public function bankPayment($id)
    {
        $bank_info = DB::table('bank_info')->first();
        $packages = MySellerPackage::findOrFail($id);
        // dd($packages);
        return view('seller.bank_payment', compact('packages', 'bank_info'));
    }

    public function bankPaymentPost(Request $request, $id)
    {
        $request->validate([
            'account_number' => 'required',
            'branch_name' => 'required',
            'acccount_name' => 'required',
            'proved_image' => 'required',
        ]);

        $payment_proved_image = $request->file('proved_image');
        $slug = 'proved_image';
        if($payment_proved_image) {
            $payment_proved_image_name = $slug.'-'.uniqid().'.'.$payment_proved_image->getClientOriginalExtension();
            $upload_path = 'public/media/proved_image/';
            $payment_proved_image->move($upload_path, $payment_proved_image_name);
    
            $provedimage = $upload_path.$payment_proved_image_name;
    
        }else {
            $provedimage = null;
        }

        // dd($provedimage);

        $latest_id = DB::table('package_transactions')->latest()->first();
        if(isset($latest_id)) {
            $order_code = "O-".sprintf('%05d', $latest_id->id + 1);
        }else {
            $order_code = "O-".sprintf('%05d', 1);
        }

        DB::table('package_transactions')->insert([
            'user_id' => Auth::user()->id,
            'order_id' => $order_code,
            'transactions_id' => 1,
            'package_id' => $request->package_id,
            'amount' => $request->amount,
            'account_number'=> $request->account_number,
            'branch_name'=> $request->branch_name,
            'acccount_name'=> $request->acccount_name,
            'proved_image'=> $provedimage,
            'payment_status' => 'Unpaid',
            'payment_mathod' => 0,
            'status' => 0,
            'created_at' => Carbon::now(),
        ]);
        
        User::where('id', Auth::user()->id)->update([
            'customer_package_id' => $request->package_id,
        ]);
        
        flash("Bank payment successfully done. Please wait for admin approval")->success();

        return redirect()->route('seller.dashboard');
    }

    
    public function processTransaction(Request $request)
    {
        
        $promotion = MySellerPackage::find($request->package_id);
        dd($promotion);
        // $ad_id = $request->ad_id;
        // session()->put('ad_id', $ad_id);
        // session()->put('promotion_id', $promotion->id);
        $converted_amount = currencyConversion($promotion->price);

        session(['order_payment' => [
            'payment_provider' => 'paypal',
            'amount' =>  $converted_amount,
            'currency_symbol' => getCurrencySymbol(),
            'usd_amount' =>  $converted_amount,
        ]]);

        $provider = new PayPalClient;
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
