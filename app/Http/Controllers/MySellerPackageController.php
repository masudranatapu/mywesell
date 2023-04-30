<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MySellerPackage;
use Illuminate\Support\Str;

class MySellerPackageController extends Controller
{
    // public function __construct() {
    //     // Staff Permission Check
    //     $this->middleware(['permission:view_all_brands'])->only('index');
    //     $this->middleware(['permission:add_brand'])->only('create');
    //     $this->middleware(['permission:edit_brand'])->only('edit');
    //     $this->middleware(['permission:delete_brand'])->only('destroy');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $brands = MySellerPackage::orderBy('id', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $brands = $brands->where('name', 'like', '%'.$sort_search.'%');
        }
        $brands = $brands->paginate(15);
        return view('backend.product.my-seller-package.index', compact('brands', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd(1);
        return view('backend.product.my-seller-package.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $brand = new MySellerPackage;
        $brand->name = $request->name;
        $brand->amount = $request->amount;
        $brand->subscription_days = $request->subscription_days;
        $brand->available_days = $request->available_days;
        $brand->withdraw_days = $request->withdraw_days;
        $brand->post_limit = $request->post_limit;
        $brand->storage_limit = $request->storage_limit;
        $brand->promoting_status = $request->promoting_status;
        $brand->save();

        flash(translate('Package has been inserted successfully'))->success();
        return redirect()->route('my-seller-package.index');

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
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $brand  = MySellerPackage::findOrFail($id);
        return view('backend.product.my-seller-package.edit', compact('brand','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $brand = MySellerPackage::findOrFail($id);
        $brand->name = $request->name;
        $brand->amount = $request->amount;
        $brand->subscription_days = $request->subscription_days;
        $brand->available_days = $request->available_days;
        $brand->withdraw_days = $request->withdraw_days;
        $brand->post_limit = $request->post_limit;
        $brand->storage_limit = $request->storage_limit;
        $brand->promoting_status = $request->promoting_status;
        $brand->save();

        flash(translate('Package has been updated successfully'))->success();
        return redirect()->route('my-seller-package.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        MySellerPackage::destroy($id);

        flash(translate('Package has been deleted successfully'))->success();
        return redirect()->route('my-seller-package.index');

    }
}
