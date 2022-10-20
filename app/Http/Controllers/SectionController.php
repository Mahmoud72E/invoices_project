<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['auth']);
         $this->middleware('permission:الاقسام', ['only' => ['index']]);
         $this->middleware('permission:اضافة قسم', ['only' => ['store']]);
         $this->middleware('permission:تعديل قسم', ['only' => ['update']]);
         $this->middleware('permission:حذف قسم', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'section_name' => 'required|max:255|unique:sections',
            'description' => 'max:255'
        ],[
            'section_name.required' => 'يجب ادخال اسم القسم',
            'section_name.unique' => 'اسم القسم مسجل مسبقاً',
            'section_name.max' => 'عدد احرف اسم القسم اكثر من المطلوب',
            'description.max' => 'عدد احرف اسم القسم اكثر من المطلوب',
        ]);
        Section::create([
            'section_name'=>$request->section_name,
            'description'=>$request->description,
            'created_by'=> (Auth::user()->name),
        ]);
        session()->flash('Add', 'تم اضافة القسم بنجاح');
        return redirect('/sections');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function edit(Section $section)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $validatedData = $request->validate([
            'section_name' => '|required|max:255|unique:sections,section_name,'.$id,
            'description' => 'max:255'
        ],[
            'section_name.unique' => 'اسم القسم مسجل مسبقاً',
            'section_name.max' => 'عدد احرف اسم القسم اكثر من المطلوب',
            'description.max' => 'عدد احرف اسم القسم اكثر من المطلوب',
        ]);

        $sections = Section::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);
        session()->flash('edit', 'تم التعديل القسم بنجاح');
        return redirect('/sections');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Section::find($id)->delete();
        session()->flash('delete', 'تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
