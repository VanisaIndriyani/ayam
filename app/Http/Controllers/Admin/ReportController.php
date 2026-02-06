<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal optional
        $from = $request->from;
        $to   = $request->to;

        $payments = Payment::with('order')
            ->when($from && $to, function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // summary top card
        $summary = [
            'total_transaksi' => Payment::count(),
            'total_pendapatan' => Payment::where('transaction_status','!=','failed')->sum('gross_amount'),
            'pending' => Payment::where('transaction_status','pending')->count(),
            'failed'  => Payment::where('transaction_status','failed')->count(),
        ];

        return view('admin.reports.index', compact('payments', 'summary', 'from', 'to'));
    }
}
