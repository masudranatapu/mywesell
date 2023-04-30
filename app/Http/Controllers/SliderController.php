<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Product;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $sliders = Slider::orderBy('id', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $sliders = $sliders->where('text', 'like', '%'.$sort_search.'%');
        }
        $sliders = $sliders->paginate(15);
        return view('backend.sliders.index', compact('sliders', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $slider = new slider;
        $slider->text = $request->text;
        $slider->image = $request->logo;
        $slider->save();

        flash(translate('slider has been inserted successfully'))->success();
        return redirect()->route('sliders.index');

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
        $slider  = Slider::findOrFail($id);
        return view('backend.sliders.edit', compact('slider','lang'));
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
        $slider = Slider::findOrFail($id);
        $slider->text = $request->text;
        $slider->image = $request->logo;
        $slider->save();

        flash(translate('slider has been updated successfully'))->success();
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Slider::destroy($id);

        flash(translate('slider has been deleted successfully'))->success();
        return redirect()->route('sliders.index');

    }
}
