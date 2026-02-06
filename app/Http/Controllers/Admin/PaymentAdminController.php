<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentAdminController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');

        $payments = Payment::with('order')
            ->when($status, function ($query) use ($status) {

                // PAID
                if ($status === '00') {
                    $query->where('transaction_status', '00');
                }

                // PENDING
                elseif ($status === '01') {
                    $query->where('transaction_status', '01');
                }

                // FAILED = selain 00 & 01
                elseif ($status === 'FAILED') {
                    $query->whereNotIn('transaction_status', ['00', '01'])
                        ->orWhereNull('transaction_status');
                }

            })
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments', 'status'));
    }


    public function show(Payment $payment)
    {
        $payment->load('order.items');

        return view('admin.payments.show', compact('payment'));
    }
}
