<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Admin Panel — @yield('title')</title>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ICONS --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f6fa;
            overflow-x: hidden;
        }

        /* SIDEBAR */
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: #1e293b;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 20px;
            transition: all 0.3s ease;
            z-index: 1050;
        }

        .sidebar h4 {
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: 700;
        }

        .sidebar .menu-item a {
            padding: 10px 16px;
            color: #e2e8f0;
            border-radius: 8px;
            display: block;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .sidebar .menu-item a.active,
        .sidebar .menu-item a:hover {
            background: #334155;
            color: #ffffff;
        }

        /* PAGE WRAPPER */
        .content {
            margin-left: 260px;
            transition: all 0.3s ease;
        }

        /* TOPBAR */
        .topbar {
            background: white;
            padding: 15px 25px;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 1040;
        }
        
        /* MOBILE RESPONSIVE */
        @media (max-width: 992px) {
            .sidebar {
                left: -260px;
            }

            .sidebar.show {
                left: 0;
            }

            .content {
                margin-left: 0;
            }

            .menu-toggle {
                display: inline-block !important;
            }
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            cursor: pointer;
            color: #1e293b;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    @include('admin.components.sidebar')

    {{-- MAIN CONTENT --}}
    <div class="content">

        {{-- TOPBAR --}}
        @include('admin.components.topbar')

        {{-- PAGE CONTENT --}}
        <main class="p-4">
            @yield('content')
        </main>

    </div>

    {{-- JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- SIDEBAR TOGGLE FOR MOBILE --}}
    <script>
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('#menuToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });
        }
    </script>

    @stack('scripts')
</body>

</html>
