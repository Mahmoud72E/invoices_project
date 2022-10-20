<?php

namespace App\Http\Controllers;

use App\Models\Invoice_Attachments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceAttachmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:اضافة مرفق', ['only' => ['store']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * Store a newly created Invoice in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'file_name' => 'mimes:pdf|required',
        ],[
            'file_name.mimes'=>'يجب ان يكون المرفق بصيغة ملف PDF',
        ]);
        //$invoice_id = Invoices::latest()->first()->id;
        $image = $request->file('file_name');
        $file_name = $image->getClientOriginalName();

        Invoice_Attachments::create([
            'file_name'=> $file_name,
            'invoice_number'=> $request->invoice_number,
            'created_by'=> (Auth::user()->name),
            'invoice_id'=> $request->invoice_id,
        ]);

        //move pic

        $imageName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('Attachments/'. $request->invoice_number), $imageName);

        session()->flash('Add', 'تم اضافة المرفق بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice_Attachments  $invoice_Attachments
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice_Attachments $invoice_Attachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice_Attachments  $invoice_Attachments
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice_Attachments $invoice_Attachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice_Attachments  $invoice_Attachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice_Attachments $invoice_Attachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice_Attachments  $invoice_Attachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice_Attachments $invoice_Attachments)
    {
        //
    }
}
