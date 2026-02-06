<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user'])->latest();

        // Filter berdasarkan status transaksi (optional)
        if ($request->filled('status')) {
            $query->where('transaction_status', $request->status);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        $payments = $query->paginate(20);

        $statuses = ['pending', 'settlement', 'capture', 'deny', 'expire', 'cancel'];
        $types    = ['credit_card', 'bank_transfer', 'gopay', 'qris', 'shopeepay'];

        return view('admin.payments.index', compact('payments', 'statuses', 'types'));
    }
}
