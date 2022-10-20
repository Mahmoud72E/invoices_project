<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use App\Models\Section;
use Illuminate\Http\Request;

class Customers_ReportController extends Controller
{
    /**
     * Cheack The User.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware('permission:تقرير العملاء', ['only' => ['index']]);
        $this->middleware('permission:تقرير العملاء', ['only' => ['Search_customers']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::all();
        return view('reports.customers_report',compact('sections'));
    }

    /**
     * Search Proudects To Make Report.
     *
     * @return \Illuminate\Http\Response
     */
    public function Search_customers(Request $request)
    {

        // لو بدون تاريخ
        if($request->Section && $request->product && $request->start_at == '' && $request->end_at == ''){
            $invoices = Invoices::select('*')->where('section_id','=' ,$request->Section)->where('product', '=' , $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        }

        // لو بتاريخ محدد
        else{
            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = Invoices::whereBetween('invoice_date', [$start_at, $end_at])->where('section_id','=' ,$request->Section)->where('product', '=' , $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_report', compact('sections'))->withDetails($invoices);
        }
    }
}
