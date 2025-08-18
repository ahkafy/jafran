<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Modern MLM Investment System</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Additional PWA Meta Tags -->
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="MLM Investment">
</head>
<body>
    <div id="app">
        <!-- Mobile-First Navigation (Non-sticky) -->
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <!-- Brand -->
                <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="d-none d-sm-inline">MLM Investment</span>
                    <span class="d-sm-none">MLM</span>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @auth
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i>
                                <span class="d-lg-inline d-none">Dashboard</span>
                            </a>
                        </li>

                        <!-- Investments Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('investments.*') ? 'active' : '' }}" href="#" id="investmentDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-chart-line"></i>
                                Investments
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="investmentDropdown">
                                <li><a class="dropdown-item" href="{{ route('investments.index') }}">
                                    <i class="fas fa-box"></i> Investment Packages
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('investments.history') }}">
                                    <i class="fas fa-history"></i> Investment History
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('investments.daily-returns') }}">
                                    <i class="fas fa-calendar-day"></i> Daily Returns
                                </a></li>
                            </ul>
                        </li>

                        <!-- MLM Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('mlm.*') ? 'active' : '' }}" href="#" id="mlmDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-users"></i>
                                MLM
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="mlmDropdown">
                                <li><a class="dropdown-item" href="{{ route('mlm.index') }}">
                                    <i class="fas fa-tachometer-alt"></i> MLM Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('mlm.genealogy') }}">
                                    <i class="fas fa-sitemap"></i> Genealogy Tree
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mlm.referrals') }}">
                                    <i class="fas fa-user-friends"></i> Direct Referrals
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mlm.team') }}">
                                    <i class="fas fa-users-cog"></i> Team Overview
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('mlm.commissions') }}">
                                    <i class="fas fa-hand-holding-usd"></i> Commission History
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('mlm.referral-link') }}">
                                    <i class="fas fa-share-alt"></i> Referral Link
                                </a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('wallet') ? 'active' : '' }}" href="{{ route('wallet') }}">
                                <i class="fas fa-wallet"></i>
                                <span class="d-lg-inline d-none">Wallet</span>
                            </a>
                        </li>
                    </ul>
                    @endauth

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i> {{ __('Login') }}
                                    </a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- Wallet Balance (Mobile Friendly) -->
                            <li class="nav-item dropdown d-block d-lg-none">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-wallet"></i>
                                    ${{ number_format(Auth::user()->wallet_balance + Auth::user()->commission_balance, 2) }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="px-3 py-2">
                                        <small class="text-muted d-block">Wallet Balance</small>
                                        <strong>${{ number_format(Auth::user()->wallet_balance, 2) }}</strong>
                                    </div>
                                    <div class="px-3 py-2">
                                        <small class="text-muted d-block">Commission Balance</small>
                                        <strong>${{ number_format(Auth::user()->commission_balance, 2) }}</strong>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('wallet') }}">
                                        <i class="fas fa-wallet"></i> Manage Wallet
                                    </a>
                                </div>
                            </li>

                            <!-- Desktop Wallet Display -->
                            <li class="nav-item dropdown d-none d-lg-block">
                                <a id="walletDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-wallet text-success"></i>
                                    <span class="fw-semibold">${{ number_format(Auth::user()->wallet_balance + Auth::user()->commission_balance, 2) }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="walletDropdown">
                                    <div class="px-3 py-2 border-bottom">
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Wallet:</small>
                                            <small class="fw-semibold text-primary">${{ number_format(Auth::user()->wallet_balance, 2) }}</small>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted">Commission:</small>
                                            <small class="fw-semibold text-success">${{ number_format(Auth::user()->commission_balance, 2) }}</small>
                                        </div>
                                    </div>
                                    <a class="dropdown-item" href="{{ route('wallet') }}">
                                        <i class="fas fa-wallet"></i> Manage Wallet
                                    </a>
                                </div>
                            </li>

                            <!-- User Profile Dropdown -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar me-2">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="d-none d-lg-block">
                                            <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                            <small class="text-muted">{{ Auth::user()->referral_code }}</small>
                                        </div>
                                        <div class="d-lg-none">
                                            {{ Auth::user()->name }}
                                        </div>
                                    </div>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <div class="px-3 py-2 border-bottom d-lg-none">
                                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                        <small class="text-muted">{{ Auth::user()->email }}</small><br>
                                        <small class="text-primary">Code: {{ Auth::user()->referral_code }}</small>
                                    </div>

                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user"></i> Profile Settings
                                    </a>
                                    <a class="dropdown-item" href="{{ route('wallet') }}">
                                        <i class="fas fa-wallet"></i> My Wallet
                                    </a>
                                    <a class="dropdown-item" href="{{ route('mlm.referral-link') }}">
                                        <i class="fas fa-share-alt"></i> Referral Link
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="pt-3">
            <!-- Global Alert Messages -->
            @if(session('success'))
                <div class="container-fluid mb-4">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="flex-grow-1">{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="container-fluid mb-4">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <div class="flex-grow-1">
                                @if($errors->count() == 1)
                                    {{ $errors->first() }}
                                @else
                                    <ul class="mb-0 ps-3">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Mobile Bottom Navigation (Optional) -->
        @auth
        <nav class="navbar navbar-light bg-white d-block d-lg-none border-top mt-4">
            <div class="container-fluid">
                <div class="d-flex justify-content-around w-100">
                    <a href="{{ route('dashboard') }}" class="nav-link text-center {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-tachometer-alt d-block"></i>
                        <small>Dashboard</small>
                    </a>
                    <a href="{{ route('investments.index') }}" class="nav-link text-center {{ request()->routeIs('investments.*') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-chart-line d-block"></i>
                        <small>Invest</small>
                    </a>
                    <a href="{{ route('mlm.index') }}" class="nav-link text-center {{ request()->routeIs('mlm.*') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-users d-block"></i>
                        <small>MLM</small>
                    </a>
                    <a href="{{ route('wallet') }}" class="nav-link text-center {{ request()->routeIs('wallet') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-wallet d-block"></i>
                        <small>Wallet</small>
                    </a>
                </div>
            </div>
        </nav>
        @endauth
    </div>

    <!-- Custom Styles -->
    <style>
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6600, #ff8c00);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }

        .nav-link.active {
            background: rgba(255, 102, 0, 0.1);
            color: #ff6600 !important;
            border-radius: 0.5rem;
        }

        .text-primary {
            color: #ff6600 !important;
        }

        .text-muted {
            color: #64748b !important;
        }

        @media (max-width: 991.98px) {
            body {
                padding-bottom: 0; /* Remove bottom padding since navbar is not fixed */
            }

            .navbar-collapse {
                background: rgba(255, 255, 255, 0.98);
                margin: 1rem -1rem 0;
                padding: 1rem;
                border-radius: 0.75rem;
                box-shadow: 0 4px 6px -1px rgba(255, 102, 0, 0.1);
                border: 1px solid rgba(255, 102, 0, 0.1);
            }
        }

        .navbar-toggler {
            padding: 0.5rem;
            border: 2px solid rgba(255, 102, 0, 0.2);
            background: rgba(255, 102, 0, 0.1);
            border-radius: 0.5rem;
            color: #ff6600;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 3px rgba(255, 102, 0, 0.25);
        }

        .navbar-toggler i {
            color: #ff6600;
        }

        /* Orange theme for mobile navigation */
        .nav-link.text-primary {
            color: #ff6600 !important;
        }

        /* Update dropdown menu colors */
        .dropdown-menu .dropdown-item:hover {
            background-color: rgba(255, 102, 0, 0.1);
            color: #ff6600;
        }
    </style>

    <!-- Bootstrap JS and Custom Scripts -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        // Close mobile nav when clicking outside
        document.addEventListener('click', function(event) {
            const navbar = document.querySelector('.navbar-collapse');
            const toggler = document.querySelector('.navbar-toggler');

            if (navbar.classList.contains('show') && !navbar.contains(event.target) && !toggler.contains(event.target)) {
                const bsCollapse = new bootstrap.Collapse(navbar, {toggle: false});
                bsCollapse.hide();
            }
        });
    </script>
</body>
</html>
