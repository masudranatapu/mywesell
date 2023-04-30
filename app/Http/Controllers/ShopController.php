<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\User;
use App\Models\BusinessSetting;
use App\Models\MySellerPackage;
use Auth;
use Hash;
use App\Notifications\EmailVerificationNotification;
use Carbon\Carbon;

class ShopController extends Controller
{

    public function __construct()
    {
        $this->middleware('user', ['only' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shop = Auth::user()->shop;
        return view('seller.shop', compact('shop'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::check()) {
			if((Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'customer')) {
				flash(translate('Admin or Customer can not be a seller'))->error();
				return back();
			} if(Auth::user()->user_type == 'seller'){
				flash(translate('This user already a seller'))->error();
				return back();
			}
            
        } else {
            $packages = MySellerPackage::orderBy('id', 'asc')->get();
            return view('frontend.seller_form', compact('packages'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $packages = MySellerPackage::where('id', $request->my_seller_package_id)->first();
        $package_validate = Carbon::now()->addMonth($packages->subscription_days);
        $available_days_validate = Carbon::now()->addDays($packages->available_days);
        $withdraw_days = Carbon::now()->addDay($packages->withdraw_days);
        
        $user = null;

        if (!Auth::check()) {
            if (User::where('email', $request->email)->first() != null) {
                flash(translate('Email already exists!'))->error();
                return back();
            }
            if ($request->password == $request->password_confirmation) {
                $user = new User;
                $user->name = $request->name;
                $user->email = $request->email;
                $user->customer_package_id = $request->my_seller_package_id;
                $user->package_validate = $package_validate;
                $user->available_days_validate = $available_days_validate;
                $user->withdraw_days_validate = $withdraw_days;
                $user->total_post = $packages->post_limit;
                $user->total_storage = $packages->storage_limit*1024;
                $user->user_type = "seller";
                $user->password = Hash::make($request->password);
                $user->save();
            } else {
                flash(translate('Sorry! Password did not match.'))->error();
                return back();
            }
        } else {
            $user = Auth::user();
            if ($user->customer != null) {
                $user->customer->delete();
            }
            $user->user_type = "seller";
            $user->save();
        }

        if (Shop::where('user_id', $user->id)->first() == null) {
            $shop = new Shop;
            $shop->seller_package_id = $request->my_seller_package_id;
            $shop->user_id = $user->id;
            $shop->name = $request->shop_name;
            $shop->address = $request->address;
            $shop->slug = preg_replace('/\s+/', '-', $request->shop_name);

            if ($shop->save()) {
                auth()->login($user, false);
                if (BusinessSetting::where('type', 'email_verification')->first()->value != 1) {
                    $user->email_verified_at = date('Y-m-d H:m:s');
                    $user->save();
                } else {
                    $user->notify(new EmailVerificationNotification());
                }

                flash(translate('Your Shop has been created successfully!'))->success();

                return redirect()->route('shops.index');
            } else {
                $user->user_type == 'customer';
                $user->save();
            }
        }

        flash(translate('Sorry! Something went wrong.'))->error();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
