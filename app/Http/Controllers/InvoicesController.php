<?php

namespace App\Http\Controllers;

use App\Models\Invoice_Attachments;
use App\Models\Invoice_Details;
use App\Models\Invoices;
use App\Models\Section;
use App\Models\User;
use App\Notifications\Add_invoices_new;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['auth']);
         $this->middleware('permission:قائمة الفواتير', ['only' => ['index']]);
         $this->middleware('permission:اضافة فاتورة', ['only' => ['create','store']]);
         $this->middleware('permission:تعديل الفاتورة', ['only' => ['edit','update']]);
         $this->middleware('permission:حذف الفاتورة', ['only' => ['destroy']]);
         $this->middleware('permission:تغير حالة الدفع', ['only' => ['show']]);
         $this->middleware('permission:تغير حالة الدفع', ['only' => ['Status_Update']]);
         //Invoice_Paid Invoice_Partial
         $this->middleware('permission:الفواتير المدفوعة', ['only' => ['Invoice_Paid']]);
         $this->middleware('permission:الفواتير المدفوعة جزئيا', ['only' => ['Invoice_Partial']]);
         $this->middleware('permission:الفواتير الغير مدفوعة', ['only' => ['Invoice_UnPaid']]);
         $this->middleware('permission:طباعةالفاتورة', ['only' => ['Print_invoice']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoices::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoices', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number'    => 'required|max:255|unique:invoices,invoice_number',
            'invoice_Date'      => 'required|date',
            'Due_date'          => 'required|date',
            'product'           => 'required|max:255',
            'Section'        => 'required|min:1',
            'Amount_collection' => 'required|max:255',
        ], [
            'invoice_number.unique' => 'الفاتورة مسجلة مسبقاً',
            'invoice_number.required' => 'يجب اضافة رقم الفاتورة',
            'Due_date.required'   => 'يحب اضافة تاريخ استحقاق الفاتورة',
            'product.required' => 'يجب اختيار اسم المنتج',
            'Amount_collection.required'   => 'يجب كتابةالقيمة لفاتورة',
        ]);
        Invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = Invoices::latest()->first()->id;
        Invoice_Details::create([
            'invoices_id' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile("pic")) {
            $validatedpic = $request->validate([
                'pic' => 'required|mimes:pdf',
            ], [
                'pic.required' => 'تم اضافة الفاتورة لكن بدون مرفق',
                'pic.mimes' => 'يجب ان يكون المرفق بصيغة ملف و تم اضافة الفاتورة بدون مرفق',
            ]);
            $invoice_id = Invoices::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            Invoice_Attachments::create([
                'file_name' => $file_name,
                'invoice_number' => $invoice_number,
                'created_by' => (Auth::user()->name),
                'invoice_id' => $invoice_id,
            ]);

            //move pic

            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

        //Start SEND Notification By Email

            //$user = User::get();
            //$user->notify(new AddInvoice($invoice_id));
            //Notification::send($user, new AddInvoice($invoice_id));

        //End SEND Notification By Email

        //Start Send Notification By Database

            $user = User::where('id', '!=', Auth::user()->id)->get();

            $invoices = Invoices::latest()->first();

            Notification::send($user,new Add_invoices_new($invoices));

        //End Send Notification By Database
        session()->flash('Add', 'تم اضافة الفاتورة بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoices = Invoices::find($id);
        return view('invoices.status_update', compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoices = Invoices::findOrFail($id);
        $sections = Section::all();
        return view('invoices.edit_invoice', compact('invoices', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $invoices = Invoices::findOrFail($request->invoice_id);
        $invoicesd = Invoice_Details::where('invoices_id', $request->invoice_id)->first();
        $invoicesa = Invoice_Attachments::where('invoice_id', $request->invoice_id)->first();
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'note' => $request->note,
        ]);
        $invoicesd->update([
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section' => $request->Section,
            'note' => $request->note,
        ]);
        $invoicesa->update([
            'invoice_number' => $request->invoice_number,
        ]);
        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->invoice_id;
        $invoices = Invoices::find($id);
        $attacment = Invoice_Attachments::where('invoice_id', $id)->first();
        if ($request->id_page == 2) {
            $invoices->Delete();
            session()->flash('archive_invoice');
            return redirect('/archive');
        } else {
            if (!empty($attacment->invoice_number)) {
                // [You Have To Swap first() to get() And Make LOOP to Delete All File And Let Derictory Exist]
                // Storage::disk('public_uploads')->delete($attacment->invoice_number.'/'. $attacment->file_name);

                Storage::disk('public_uploads')->deleteDirectory($attacment->invoice_number); // [Delete Dir and All File]
            }

            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return redirect('/invoices');
        }
    }

    /**
     * Get Products from Database.
     *
     * @param  $id -> Section Id
     * @return json_encode($products);
     */
    public function getproducts($id)
    {
        $products = DB::table('products')->where('section_id', $id)->pluck('product_name', 'id');
        return json_encode($products);
    }

    /**
     * Change Payment Status
     *
     * @param  $id -> Invoices Id,
     *         Request $request
     * @return flash('Status_Update') , redirect('/invoices')
     */
    public function Status_Update($id, Request $request)
    {
        $invoices = Invoices::findOrFail($id);
        if ($request->Status == "مدفوعة") {
            $invoices->update([
                'status' => $request->Status,
                'value_status' => 1,
                'payment_date' => $request->Payment_Date,
            ]);
            Invoice_Details::create([
                'invoices_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'payment_date' => $request->Payment_Date,
                'status' => 'مدفوعة',
                'value_status' => 2,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $invoices->update([
                'status' => $request->Status,
                'value_status' => 3,
                'payment_date' => $request->Payment_Date,
            ]);
            Invoice_Details::create([
                'invoices_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'section' => $request->Section,
                'payment_date' => $request->Payment_Date,
                'status' => ' مدفوعة جزئياً',
                'value_status' => 3,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');
    }

    /**
     * Invoice Paid Page
     *
     * @return view
     */
    public function Invoice_Paid()
    {
        $invoices = Invoices::where('value_status', 1)->get();
        return view('invoices.invoice_paid', compact('invoices'));
    }

    /**
     * Invoice Unpaid Page
     *
     * @return view
     */
    public function Invoice_UnPaid()
    {
        $invoices = Invoices::where('value_status', 2)->get();
        return view('invoices.invoice_unpaid', compact('invoices'));
    }

    /**
     * Invoice Partial Paid Page
     *
     * @return view
     */
    public function Invoice_Partial()
    {
        $invoices = Invoices::where('value_status', 3)->get();
        return view('invoices.invoice_partial', compact('invoices'));
    }

    /**
     * Print Invoice
     *
     * @return view
     */
    public function Print_invoice($id)
    {
        $invoices = Invoices::where('id', $id)->first();
        return view('invoices.print_invoice', compact('invoices'));
    }
}
