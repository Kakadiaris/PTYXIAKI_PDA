<!DOCTYPE html>
<html lang="el">

<head>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta charset="UTF-8">
    <title>PDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap ή Tailwind --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
@php
    $role = auth()->user()->role;
    $homeRoute = match ($role) {
        'superuser' => '/superuser',
        'admin' => '/admin',
        'kitchen' => '/kitchen',
        'waiter' => '/waiter',
        'bar' => '/bar',
    };
@endphp

<body>

    <div class="d-flex pan_back">
        {{-- Sidebar --}}
        <div class="d-flex" style="min-height: 100vh;">
            {{-- Sidebar --}}
            <nav class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar pan_back" style="width: 280px;">
                <a href="/"
                    class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32">
                        <use xlink:href="#bootstrap" />
                    </svg>
                    <span class="fs-4">PDA Panel</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ url($homeRoute) }}" class="nav-link active">
                            <i class="bi bi-house me-2"></i> Αρχική
                        </a>
                    </li>
                    @php
                        $rolesWithTablesAccess = ['admin', 'waiter', 'superuser'];
                    @endphp
                    @if (in_array($role, $rolesWithTablesAccess))
                        <li>
                            <a href="/pda-app/public/tables" class="nav-link text-white">
                                <i class="bi bi-speedometer2 me-2"></i> Tables
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="/pda-app/public/orders" class="nav-link text-white">
                            <i class="bi bi-table me-2"></i> Παραγγελίες
                        </a>
                    </li>
                    <li>
                        <a href="/pda-app/public/menu" class="nav-link text-white">
                            <i class="bi bi-box-seam me-2"></i> Προϊόντα
                        </a>
                    </li>
                    <li>
                        <a href="#" class="nav-link text-white">
                            <i class="bi bi-people me-2"></i> Πελάτες
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong>{{ auth()->user()->name ?? 'Χρήστης' }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">Ρυθμίσεις</a></li>
                        <li><a class="dropdown-item" href="#">Προφίλ</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="#">Αποσύνδεση</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        {{-- Main Content --}}
        <main class="flex-grow-1 p-4 pan_main">
            <div class="d-flex justify-content-between mb-4">
                <div><strong>Γεια σου {{ auth()->user()->name ?? 'Χρήστη' }}</strong></div>
                <div>{{ \Carbon\Carbon::now()->locale('el')->isoFormat('dddd D MMMM YYYY') }}</div>
            </div>

            @yield('content')
        </main>
</body>

</html>
