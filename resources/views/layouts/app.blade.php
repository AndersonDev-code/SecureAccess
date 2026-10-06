<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', 'SecureAccess')
    </title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
        rel="stylesheet"
    >

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <style>

        :root {
            --sa-primary: #2563eb;
            --sa-primary-dark: #1d4ed8;
            --sa-dark: #111827;
            --sa-sidebar: #172033;
            --sa-bg: #f5f7fb;
            --sa-success: #16a34a;
            --sa-danger: #dc2626;
            --sa-warning: #f59e0b;
            --sa-border: #e5e7eb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--sa-bg);
            font-family: 'Inter', sans-serif;
            color: #1f2937;
        }

        a {
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--sa-primary);
            border-color: var(--sa-primary);
        }

        .btn-primary:hover {
            background-color: var(--sa-primary-dark);
            border-color: var(--sa-primary-dark);
        }

        .sa-card {
            background: #ffffff;
            border: 1px solid var(--sa-border);
            border-radius: 16px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.04);
        }

    </style>

    @stack('styles')

</head>

<body>

    @yield('content')


    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>
</html>