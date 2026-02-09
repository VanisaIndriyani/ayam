<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Construction - Bohri Farm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #fff5f5 0%, #ffe3e3 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #333;
        }
        .construction-card {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 600px;
            width: 90%;
            position: relative;
            overflow: hidden;
        }
        .construction-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: #dc3545; /* Red for Bohri Farm brand */
        }
        .logo-container {
            margin-bottom: 2rem;
        }
        .logo-container i {
            font-size: 4rem;
            color: #dc3545;
        }
        h1 {
            font-weight: 700;
            margin-bottom: 1rem;
            color: #2d3436;
        }
        p {
            color: #636e72;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .btn-contact {
            background-color: #25D366; /* WhatsApp Green */
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: none;
        }
        .btn-contact:hover {
            background-color: #128C7E;
            transform: translateY(-2px);
            color: white;
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }
        .illustration {
            width: 100%;
            max-width: 250px;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

    <div class="construction-card">
        <div class="logo-container">
            <!-- Icon Ayam/Farm -->
            <i class="fa-solid fa-cow fa-bounce"></i> 
            <i class="fa-solid fa-egg fa-bounce" style="animation-delay: 0.2s"></i>
        </div>
        
        <h1>Website Sedang Dibangun</h1>
        
        <p class="lead">
            Halo! Saat ini website <strong>Bohri Farm</strong> sedang dalam tahap pengembangan dan penyempurnaan sistem.
            <br><br>
            Kami sedang bekerja keras untuk memberikan pengalaman berbelanja hasil peternakan terbaik untuk Anda. Silakan kembali lagi nanti!
        </p>

        <div class="mt-4">
            <p class="mb-2 small text-muted">Butuh info produk atau pemesanan mendesak?</p>
            <a href="https://wa.me/6281234567890" class="btn-contact" target="_blank">
                <i class="fa-brands fa-whatsapp fa-lg"></i> Hubungi Kami di WhatsApp
            </a>
        </div>
        
        <div class="mt-5 text-muted small">
            &copy; {{ date('Y') }} Bohri Farm. All Rights Reserved.
        </div>
    </div>

</body>
</html>
