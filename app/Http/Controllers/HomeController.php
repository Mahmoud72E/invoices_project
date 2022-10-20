<?php

namespace App\Http\Controllers;

use App\Models\Invoices;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Cheack The User.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_invoices = Invoices::count();
        $paid_count = Invoices::where('value_status', 1)->count();
        $not_paid_count = Invoices::where('value_status', 2)->count();
        $part_paid_count = Invoices::where('value_status', 3)->count();

        $paid = round($paid_count / $all_invoices * 100);
        $not_paid = round($not_paid_count / $all_invoices  * 100);
        $partpaid = round($part_paid_count / $all_invoices  * 100);

        $chartjs = app()->chartjs
         ->name('barChartTest')
         ->type('bar')
         ->size(['width' => 100, 'height' =>50])
         ->labels(['احصائيات الفواتير'])
         ->datasets([
             [
                 "label" => "المدفوعة",
                 'backgroundColor' => ['rgba(8, 155, 108, 1)'],
                 'data' => [$paid]
             ],
             [
                 "label" => "الغير مدفوعة",
                 'backgroundColor' => ['rgba(248, 90, 117, 1)'],
                 'data' => [$not_paid]
             ],
             [
                "label" => "مدفوعة جزئياً",
                'backgroundColor' => ['rgba(245, 119, 56, 1)'],
                'data' => [$partpaid]
             ],
             [
                "label" => " ",

                'data' => [0]
             ]
         ])
         ->options([]);

        $chartjs2 = app()->chartjs
        ->name('pieChartTest')
        ->type('pie')
        ->size(['width' => 400, 'height' => 290])
        ->labels(['المدفوعة', 'الغير مدفوعة', 'مدفوعة جزئياً'])
        ->datasets([
            [
                'backgroundColor' => ['rgba(8, 155, 108, 0.7)', 'rgba(248, 90, 117, 0.7)' ,'rgba(245, 119, 56, 0.7)'],
                'hoverBackgroundColor' => ['rgba(8, 155, 108, 1)', 'rgba(248, 90, 117, 1)' ,'rgba(245, 119, 56, 1)'],
                'data' => [$paid, $not_paid, $partpaid]
            ]
        ])
        ->options([]);

        return view('dashboard', compact('chartjs', 'chartjs2'));
    }
}
