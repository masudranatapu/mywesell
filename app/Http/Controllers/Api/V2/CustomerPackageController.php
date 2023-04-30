<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Resources\V2\CustomerPackageResource;
use App\Models\CustomerPackage;
use Illuminate\Http\Request;


class CustomerPackageController extends Controller
{
    public function customer_packages_list()
    {
            $customer_packages = CustomerPackage::all();
            return CustomerPackageResource::collection($customer_packages);
    }
/*
    public function purchase_free_package(Request $request)
    {
        $data['seller_package_id'] = $request->package_id;
        $data['payment_method'] = $request->payment_option;


        $seller_package = SellerPackage::findOrFail($request->seller_package_id);

        if ($seller_package->amount == 0) {
            seller_purchase_payment_done(auth()->user()->id, $request->package_id, $request->amount, 'Free Package', null);
            return $this->success(translate('Package purchasing successful'));
        } elseif (
            auth()->user()->shop->seller_package != null &&
            $seller_package->product_upload_limit < auth()->user()->shop->seller_package->product_upload_limit
        ) {
            return $this->failed(translate('You have more uploaded products than this package limit. You need to remove excessive products to downgrade.'));
        }
    }

    public function purchase_package_offline(Request $request)
    {
        $seller_package = SellerPackage::findOrFail($request->package_id);

        if (
           auth()->user()->shop->seller_package != null &&
            $seller_package->product_upload_limit < auth()->user()->shop->seller_package->product_upload_limit
        ) {
            return $this->failed(translate('You have more uploaded products than this package limit. You need to remove excessive products to downgrade.'));
        }

        $seller_package = new SellerPackagePayment;
        $seller_package->user_id = auth()->user()->id;
        $seller_package->seller_package_id = $request->package_id;
        $seller_package->payment_method = $request->payment_option;
        $seller_package->payment_details = $request->trx_id;
        $seller_package->approval = 0;
        $seller_package->offline_payment = 1;
        $seller_package->reciept = $request->photo;

        $seller_package->save();

        return $this->success(translate('Offline payment has been done. Please wait for response.'));
    }*/
}
