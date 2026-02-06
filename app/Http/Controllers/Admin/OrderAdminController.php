<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')
            ->latest()
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load([
            'items',
            'payments',
            'statusHistories.user'
        ]);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processing,packed,shipped,completed,cancelled',
            'note'   => 'nullable|string'
        ]);

        $oldStatus = $order->status;
        $order->status = $request->status;

        // ------ OPTIONAL TIMESTAMPS ------
        if ($request->status == 'paid') {
            $order->payment_status = 'paid';
            $order->paid_at = now();
        }

        if ($request->status == 'processing') {
            $order->processing_at = now();
        }

        if ($request->status == 'packed') {
            $order->packed_at = now();
        }

        if ($request->status == 'shipped') {
            $order->shipped_at = now();
        }

        if ($request->status == 'completed') {
            $order->completed_at = now();
        }

        $order->save();

        // ------ LOG HISTORY STATUS ------
        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'status_from' => $oldStatus,
            'status_to'   => $request->status,
            'changed_by'  => Auth::id(),
            'note'        => $request->note
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }


    public function updateResi(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'tracking_url'    => 'nullable|string',
        ]);

        $order->tracking_number = $request->tracking_number;
        $order->tracking_url    = $request->tracking_url;

        // Saat input resi → update status menjadi shipped
        if ($order->status !== 'shipped') {
            $old = $order->status;
            $order->status = 'shipped';
            $order->shipped_at = now();
        }

        $order->save();

        // log history
        OrderStatusHistory::create([
            'order_id'    => $order->id,
            'status_from' => $old ?? $order->status,
            'status_to'   => 'shipped',
            'changed_by'  => Auth::id(),
            'note'        => "Nomor resi: {$request->tracking_number}"
        ]);

        return back()->with('success', 'Nomor resi berhasil diperbarui.');
    }

    public function updateLocation(Request $request, Order $order)
{
    $request->validate([
        'latitude' => 'required',
        'longitude' => 'required',
    ]);

    $order->latitude = $request->latitude;
    $order->longitude = $request->longitude;
    $order->save();

    return back()->with('success', 'Lokasi kurir diperbarui.');
}

 public function invoice($id)
    {
        $order = Order::with(['items', 'payments', 'user'])->findOrFail($id);

        $pdf = Pdf::loadView('pdf.invoice', [
            'order'   => $order,
            'user'    => $order->user,
            'payment' => $order->payments->first(),
        ])->setPaper('A4', 'portrait');

        $fileName = 'Invoice-' . $order->code . '.pdf';

        return $pdf->download($fileName);
    }

}
