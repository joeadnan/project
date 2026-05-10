<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //display listing of the resource
    //@return \Illuminate\Http\Response
    public function index()
    {        
        //count invoice
        $pendding = Invoice::where('status', 'pending')->count();
        $success = Invoice::where('status', 'success')->count();
        $expired = Invoice::where('status', 'expired')->count();
        $failed = Invoice::where('status', 'failed')->count();

        //year and month
        $year = date('Y');

        //chart invoice
        $transaction = DB::table('invoices')
            ->addSelect(DB::raw('SUM(grand_total) as total'))
            ->addSelect(DB::raw('MONTH(created_at) as month'))
            ->addSelect(DB::raw('MONTHNAME(created_at) as month_name'))
            ->addSelect(DB::raw('YEAR(created_at) as year'))
            ->whereYear('created_at', $year)
            ->where('status', 'success')
            ->groupBy('month', 'month_name', 'year')
            ->orderByRaw('month ASC')
            ->get();
        $month_name = [];
        $grand_total = [];
        if (count($transaction)) {
            foreach ($transaction as $result) {
                $month_name[] = $result->month_name;
                $grand_total[] = $result->total;
            }
        }
        //response
        return response()->json([
            'success' => true,
            'massage' => 'Statistik Data',
            'data' => [
                'pendding' => $pendding,
                'success' => $success,
                'expired' => $expired,
                'failed' => $failed,
            ],
            'chart' => [
                'month_name' => $month_name,
                'grand_total' => $grand_total,
            ]
        ], 200);

        
    }
}