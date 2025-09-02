<!DOCTYPE html>
<html lang="el">

<head>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ filemtime(public_path('css/sidebar.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <meta charset="UTF-8">
    <title>PDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap ή Tailwind --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS (για modal, dropdowns κ.λπ.) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
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
                @php
                    $rolesWithTablesAccess = ['admin', 'waiter', 'superuser'];
                @endphp
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="{{ url($homeRoute) }}"
                            class="nav-link {{ Request::is(trim($homeRoute, '/') . '*') ? 'active' : 'text-white' }}">
                            <i class="bi bi-house me-2"></i> Αρχική
                        </a>
                    </li>

                    @if (in_array($role, $rolesWithTablesAccess))
                        <li>
                            <a href="/pda-app/public/zones"
                                class="nav-link {{ Request::is('zones*') ? 'active' : 'text-white' }}">
                                <i class="bi bi-speedometer2 me-2"></i> Ζώνες
                            </a>
                        </li>
                    @endif

                    @if (in_array($role, $rolesWithTablesAccess))
                        <li>
                            <a href="/pda-app/public/tables"
                                class="nav-link {{ Request::is('tables*') ? 'active' : 'text-white' }}">
                                <i class="bi bi-speedometer2 me-2"></i> Τραπέζια
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="/pda-app/public/orders"
                            class="nav-link {{ Request::is('orders*') ? 'active' : 'text-white' }}">
                            <i class="bi bi-table me-2"></i> Παραγγελίες
                        </a>
                    </li>

                    @if (Auth::user() && in_array(Auth::user()->role, ['superuser', 'admin']))
                        <li>
                            <a href="/pda-app/public/reservations"
                                class="nav-link {{ Request::is('reservations*') ? 'active' : 'text-white' }}">
                                <i class="bi bi-people me-2"></i> Κρατήσεις
                            </a>
                        </li>
                    @endif

                    <li>
                        <a href="/pda-app/public/menu"
                            class="nav-link {{ Request::is('menu*') ? 'active' : 'text-white' }}">
                            <i class="bi bi-box-seam me-2"></i> Προϊόντα
                        </a>
                    </li>

                    @if (in_array($role, $rolesWithTablesAccess))
                        <li>
                            <a href="/pda-app/public/statistics/week"
                                class="nav-link {{ Request::is('statistics*') ? 'active' : 'text-white' }}">
                                <i class="bi bi-people me-2"></i> Στατιστικά
                            </a>
                        </li>
                    @endif

                </ul>
                <hr>
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                        id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong>{{ auth()->user()->name ?? 'Χρήστης' }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item text-white bg-transparent border-0 w-100 text-start">
                                    Αποσύνδεση
                                </button>
                            </form>
                        </li>
                        @if ($role == 'superuser')
                            <li>
                                @csrf
                                <button type="submit"
                                    class="dropdown-item text-white bg-transparent border-0 w-100 text-start">
                                    <a class="pan_a" href="/pda-app/public/register">
                                        Εγγραφή
                                    </a>
                                </button>
                            </li>
                        @endif
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
        @stack('scripts')

</body>

</html>
