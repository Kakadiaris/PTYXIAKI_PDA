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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

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
        <div class="d-flex desktop-sidebar-wrapper" style="min-height: 100vh;">
            {{-- Sidebar --}}
            <nav id="sidebarNav" class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar pan_back"
                style="width: 280px;">
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <button class="btn btn-dark btn-toggle-menu" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
                    <i class="bi bi-list" style="font-size:1.2rem;"></i> Μενού
                </button>

                <div><strong>Γεια σου {{ auth()->user()->name ?? 'Χρήστη' }}</strong></div>
                <div>{{ \Carbon\Carbon::now()->locale('el')->isoFormat('dddd D MMMM YYYY') }}</div>
            </div>


            @yield('content')
        </main>
        @stack('scripts')
        <div class="offcanvas offcanvas-start pan_back text-white" tabindex="-1" id="mobileSidebar"
            aria-labelledby="mobileSidebarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="mobileSidebarLabel">PDA Panel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Κλείσιμο"></button>
            </div>
            <div class="offcanvas-body" id="mobileSidebarBody">
                <!-- Θα γεμίσει αυτόματα από το desktop sidebar -->
            </div>
        </div>




        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var desktop = document.getElementById('sidebarNav');
                var mobileBody = document.getElementById('mobileSidebarBody');
                var offcanvasEl = document.getElementById('mobileSidebar');

                if (!desktop || !mobileBody || !offcanvasEl) return;

                // 1) Αντιγραφή περιεχομένου
                mobileBody.innerHTML = desktop.innerHTML;

                // 2) Αφαίρεση διπλών id
                mobileBody.querySelectorAll('[id]').forEach(function(el) {
                    el.removeAttribute('id');
                });

                var offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasEl);
                var isDark = offcanvasEl.classList.contains('pan_back'); // αν έχεις σκούρο φόντο

                // 3) Ρύθμιση links για πλοήγηση + σωστό χρώμα
                mobileBody.querySelectorAll('a.nav-link').forEach(function(a) {
                    // Αν είναι dark, κράτα λευκό· αλλιώς βγάλε το text-white
                    if (isDark) {
                        a.classList.add('text-white');
                    } else {
                        a.classList.remove('text-white');
                    }

                    if (!a.classList.contains('dropdown-toggle')) {
                        a.addEventListener('click', function(e) {
                            var href = a.getAttribute('href');
                            if (href && href !== '#') {
                                e.preventDefault();
                                offcanvas.hide();
                                setTimeout(function() {
                                    window.location.assign(href);
                                }, 150);
                            }
                        });
                    }
                });

                // 4) Dropdown items
                mobileBody.querySelectorAll('.dropdown-menu a').forEach(function(a) {
                    a.addEventListener('click', function(e) {
                        var href = a.getAttribute('href');
                        if (href && href !== '#') {
                            e.preventDefault();
                            offcanvas.hide();
                            setTimeout(function() {
                                window.location.assign(href);
                            }, 150);
                        }
                    });
                });

                // 5) Αν έχεις dropdown menu, δώσ’ του dark theme όταν είναι dark
                if (isDark) {
                    mobileBody.querySelectorAll('.dropdown-menu').forEach(function(m) {
                        m.classList.add('dropdown-menu-dark');
                    });
                }
            });
        </script>

</body>

</html>
