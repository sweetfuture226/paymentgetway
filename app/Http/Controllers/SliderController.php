<?php

namespace App\Http\Controllers;

use App\BasicSetting;
use Illuminate\Http\Request;
use App\Slider;

class SliderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $general_all = BasicSetting::first();
        $data['site_title'] = $general_all->title;
        $data['page_title'] = "Manage Slider";
        $data['sliders'] = Slider::all();
        return view('webControl.slider', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8048',
                'title' => 'nullable',
                'subtitle' => 'nullable'
            ]);

        if($request->hasFile('image'))
        {

            $slider['image'] = uniqid().'.'.$request->image->getClientOriginalExtension();
            $request->image->move('assets/images/slider',$slider['image']);
        }
        $slider['title'] = $request->title;
        $slider['subtitle'] = $request->subtitle;

        Slider::create($slider);

        return back()->with('success', 'New Slide Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $slide = Slider::find($id);
        $this->validate($request,
            [
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8048',
                'title' => 'nullable',
                'subtitle' => 'nullable'
            ]);

        if($request->hasFile('image'))
        {
            unlink('assets/images/slider/'.$slide->image);
            $slide['image'] = uniqid().'.'.$request->image->getClientOriginalExtension();
            $request->image->move('assets/images/slider',$slide['image']);
        }


        $slide['title'] = $request->title;
        $slide['subtitle'] = $request-> subtitle;
        $slide->save();

        return back()->with('success', 'Slide Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * @param Slider $slider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Slider $slider)
    {
        $slider->delete();
        unlink('assets/images/slider/'.$slider->image);
        return back()->with('success', 'Slider Deleted Successfully!');
    }
}
