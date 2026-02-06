@extends('layouts.front')

@section('title', 'Keranjang Belanja')

@section('content')

<div class="container py-5">

    <h1 class="fw-bold mb-4">Keranjang Belanja</h1>

    @if ($cart->items->isEmpty())

        <div class="alert alert-info">
            Keranjang belanja kosong.
        </div>

    @else

        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($cart->items as $item)
                            <tr>
                                <td class="align-middle">
                                    {{ $item->product->name }}
                                </td>

                                <td class="align-middle">
                                    Rp {{ number_format($item->price) }}
                                </td>

                                {{-- UPDATE QTY --}}
                                <td class="align-middle">
                                    <form action="{{ route('cart.update', $item->id) }}" method="POST">
                                        @csrf @method('PATCH')

                                        <div class="input-group" style="width: 140px;">
                                            <input 
                                                type="number" 
                                                name="quantity"
                                                value="{{ $item->quantity }}"
                                                min="1"
                                                class="form-control"
                                            >
                                            <button class="btn btn-outline-primary">
                                                <i class="bi bi-arrow-repeat"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>

                                {{-- SUBTOTAL --}}
                                <td class="align-middle fw-bold">
                                    Rp {{ number_format($item->subtotal) }}
                                </td>

                                {{-- DELETE --}}
                                <td class="align-middle text-end">
                                    <form 
                                        action="{{ route('cart.remove', $item->id) }}" 
                                        method="POST"
                                        onsubmit="return confirm('Hapus produk ini dari keranjang?')"
                                    >
                                        @csrf @method('DELETE')

                                        <button class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        {{-- TOTAL --}}
        <div class="text-end mb-4">
            <span class="fs-4 fw-bold">
                Total: Rp {{ number_format($total) }}
            </span>
        </div>

        {{-- CHECKOUT BUTTON --}}
        <div class="text-end">
            <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg px-4">
                <i class="bi bi-bag-check me-1"></i> Lanjut ke Checkout
            </a>
        </div>

    @endif

</div>

@endsection
