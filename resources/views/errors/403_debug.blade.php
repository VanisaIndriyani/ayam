<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 Forbidden Debug</title>
    <style>
        body { font-family: sans-serif; padding: 50px; text-align: center; }
        .debug-box { background: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 8px; display: inline-block; text-align: left; }
        code { background: #eee; padding: 2px 5px; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>Akses Ditolak (403)</h1>
    <p>Terjadi ketidakcocokan data pengguna saat memproses pembayaran.</p>
    
    <div class="debug-box">
        <h3>Debug Info:</h3>
        <p><strong>Order ID:</strong> {{ $order_id }}</p>
        <p><strong>Order Owner ID:</strong> {{ $order_user_id }}</p>
        <p><strong>Current User ID:</strong> {{ $current_user_id }}</p>
        <p><strong>Current User Name:</strong> {{ $user_name }}</p>
        <hr>
        <p><small>Jika Order Owner ID berbeda dengan Current User ID, berarti Anda mencoba mengakses pesanan milik orang lain, atau sesi login Anda berubah.</small></p>
    </div>

    <br><br>
    <a href="{{ url('/') }}">Kembali ke Beranda</a>
</body>
</html>