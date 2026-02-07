<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Ambil atau buat cart aktif user
    private function ensureCart()
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => Auth::id(), 'status' => 'active'],
            ['status' => 'active']
        );

        return $cart;
    }

    // TAMPILKAN CART
    public function index()
    {
        $cart = $this->ensureCart()->load('items.product');

        $total = $cart->items->sum('subtotal');

        return view('front.cart.index', compact('cart', 'total'));
    }

    // TAMBAH PRODUK KE CART
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $cart = $this->ensureCart();

        $item = CartItem::where('cart_id', $cart->id)
                        ->where('product_id', $product->id)
                        ->first();

        if ($item) {
            // Tambah jumlah jika sudah ada
            $item->quantity += $request->quantity;
            $item->subtotal = $item->quantity * $item->price;
            $item->save();
        } else {
            // Buat baru jika belum ada
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $request->quantity,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke keranjang.',
                'cart_count' => $cart->items()->sum('quantity'),
                'redirect' => route('checkout.index'),
            ]);
        }

        return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    // UPDATE QUANTITY
    public function update(Request $request, CartItem $item)
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $item->update([
            'quantity' => $request->quantity,
            'subtotal' => $request->quantity * $item->price,
        ]);

        return redirect()->route('cart.index')->with('success', 'Jumlah diperbarui.');
    }

    // REMOVE ITEM
    public function remove(CartItem $item)
    {
        $item->delete();

        return redirect()->back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
