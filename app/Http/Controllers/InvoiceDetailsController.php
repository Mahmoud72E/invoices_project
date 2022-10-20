<?php

namespace App\Http\Controllers;

use App\Models\Invoice_Attachments;
use App\Models\Invoice_Details;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceDetailsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:الفواتير', ['only' => ['edit']]);
        $this->middleware('permission:حذف المرفق', ['only' => ['destroy']]);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice_Details  $invoice_Details
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice_Details $invoice_Details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice_Details  $invoice_Details
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Notifications Update
        $getIdNotifi = DB::table('notifications')->where('data->id', $id)->pluck('id');
        if ($getIdNotifi != '[]') {
            DB::table('notifications')->where('id', $getIdNotifi)->update(['read_at'=>now()]);
            $invoices = Invoices::findOrFail($id);
            $details = Invoice_Details::where('invoices_id', $id)->get();
            $attachments = Invoice_Attachments::where('invoice_id', $id)->get();
            return view('invoices.details_invoices', compact('invoices','details','attachments'));
        }

        // Show Details Waithout Notification
        else {
            $invoices = Invoices::findOrFail($id);
            $details = Invoice_Details::where('invoices_id', $id)->get();
            $attachments = Invoice_Attachments::where('invoice_id', $id)->get();
            return view('invoices.details_invoices', compact('invoices','details','attachments'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice_Details  $invoice_Details
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice_Details $invoice_Details)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice_Details  $invoice_Details
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoices = Invoice_Attachments::findOrFail($request->id_file);

        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'. $request->file_name);

        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }

    /**
     * Download The Invoices.
     *
     * @param  $invoice_number, $file_name From Details Blade
     * @return \Illuminate\Http\Response
     */
    public function get_file($invoice_number, $file_name)
    {
        $st = "Attachments";
        $pathToFile = public_path($st.'/'.$invoice_number.'/'.$file_name);

        return response()->download($pathToFile);
    }

    /**
     * Open The Invoices.
     *
     * @param  $invoice_number, $file_name From Details Blade
     * @return \Illuminate\Http\Response
     */
    public function open_file($invoice_number, $file_name)
    {
        $st = "Attachments";
        $pathToFile = public_path($st.'/'.$invoice_number.'/'.$file_name);

        return response()->file($pathToFile);
    }
}
