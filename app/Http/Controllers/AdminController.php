<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Artisan;
use Cache;
use DB;
use CoreComponentRepository;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MySellerPackage;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function admin_dashboard(Request $request)
    {   
        CoreComponentRepository::initializeCache();
        $root_categories = Category::where('level', 0)->get();

        $cached_graph_data = Cache::remember('cached_graph_data', 86400, function() use ($root_categories){
            $num_of_sale_data = null;
            $qty_data = null;
            foreach ($root_categories as $key => $category){
                $category_ids = \App\Utility\CategoryUtility::children_ids($category->id);
                $category_ids[] = $category->id;

                $products = Product::with('stocks')->whereIn('category_id', $category_ids)->get();
                $qty = 0;
                $sale = 0;
                foreach ($products as $key => $product) {
                    $sale += $product->num_of_sale;
                    foreach ($product->stocks as $key => $stock) {
                        $qty += $stock->qty;
                    }
                }
                $qty_data .= $qty.',';
                $num_of_sale_data .= $sale.',';
            }
            $item['num_of_sale_data'] = $num_of_sale_data;
            $item['qty_data'] = $qty_data;

            return $item;
        });

        return view('backend.dashboard', compact('root_categories', 'cached_graph_data'));
    }

    function clearCache(Request $request)
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        flash(translate('Cache cleared successfully'))->success();
        return back();
    }

    public function packageTransactons()
    {
        $package_transactions = DB::table('package_transactions')->get();
        // dd($package_transactions);
        return view('backend.sellers.package_transactions.index', compact('package_transactions'));
    }

    public function packageTransactonsEdit($id)
    {
        $transactions = DB::table('package_transactions')->where('id', $id)->first();
        // dd($transactions);
        return view('backend.sellers.package_transactions.edit', compact('transactions'));
    }
    public function packageTransactonsAcpect(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);
        
        $transactions = DB::table('package_transactions')->where('id', $id)->first();

        $packages = MySellerPackage::where('id', $transactions->package_id)->first();
        
        $package_validate = Carbon::now()->addMonth($packages->subscription_days);
        $available_days_validate = Carbon::now()->addDays($packages->available_days);
        $withdraw_days = Carbon::now()->addDay($packages->withdraw_days);
        // dd($withdraw_days);

        User::where('id', $transactions->user_id)->update([
            'package_validate' => $package_validate,
            'available_days_validate' => $available_days_validate,
            'withdraw_days_validate' => $withdraw_days,
            'total_post' => $packages->post_limit,
            'total_storage' => $packages->storage_limit*1024,
        ]);

        if($request->status == 1) {
            $payment_status = "Paid";
        }else {
            $payment_status = "Unpaid";
        }

        DB::table('package_transactions')->where('id', $id)->update([
            'status' => $request->status,
            'payment_status' => $payment_status,
        ]);

        
        flash('Package status change successfully done.')->success();

        return redirect()->route('admin.package.transactions');

    }
}
