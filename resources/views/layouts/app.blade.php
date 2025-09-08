<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Modern Network Investment System</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @yield('styles')

    <!-- Additional PWA Meta Tags -->
    <meta name="theme-color" content="#6366f1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Network Investment">
</head>
<body>
    <div id="app">
        <!-- Mobile-First Navigation (Non-sticky) -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Brand -->
                <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ url('/') }}">
                    <img src="https://i.postimg.cc/xj3zjCcD/logo.png" alt="Network Investment" class="me-2" style="height: 60px; width: auto;">


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

                        <!-- Network Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('network.*') || request()->routeIs('mlm.*') ? 'active' : '' }}" href="#" id="networkDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-users"></i>
                                Network
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="networkDropdown">
                                <li><a class="dropdown-item" href="{{ route('network.index') }}">
                                    <i class="fas fa-sitemap"></i> Team Network Tree
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('network.genealogy') }}">
                                    <i class="fas fa-list"></i> Network List
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('network.referrals') }}">
                                    <i class="fas fa-user-friends"></i> Direct Referrals
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('network.team') }}">
                                    <i class="fas fa-users-cog"></i> Team Overview
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('network.commissions') }}">
                                    <i class="fas fa-hand-holding-usd"></i> Commission History
                                </a></li>
                                <li>                    <a href="{{ route('network.referral-link') }}" class="dropdown-item">
                        <i class="fas fa-share-alt"></i> Referral Link
                    </a></li>
                            </ul>
                        </li>

                        <li class="nav-item">
                                                        <a class="nav-link {{ request()->routeIs('wallet*') ? 'active' : '' }}" href="{{ route('wallet') }}">
                                <i class="fas fa-wallet me-2"></i>Wallet
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
                                    <a class="dropdown-item" href="{{ route('wallet.index') }}">
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
                                    <a class="dropdown-item" href="{{ route('wallet.index') }}">
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
                                            <div class="mt-1">
                                                {!! Auth::user()->getRankBadge() !!}
                                            </div>
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
                                        <div class="mt-2">
                                            {!! Auth::user()->getRankBadge() !!}
                                        </div>
                                    </div>

                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="fas fa-user"></i> Profile Settings
                                    </a>
                                    <a class="dropdown-item" href="{{ route('wallet.index') }}">
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

                            <!-- Theme Toggle -->
                            <li class="nav-item ms-2">
                                <button id="themeToggle" class="btn btn-sm btn-outline-secondary d-flex align-items-center" title="Toggle Dark/Light Mode">
                                    <i id="themeIcon" class="fas fa-moon"></i>
                                    <span class="d-none d-md-inline ms-1">Dark</span>
                                </button>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main class="pt-3">
            <div class="container">
                <!-- Global Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <div class="flex-grow-1">{{ session('success') }}</div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
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
                @endif

                @yield('content')
            </div>
        </main>

        <!-- Mobile Bottom Navigation (Optional) -->
        @auth
        <nav class="navbar navbar-light bg-white d-block d-lg-none border-top mt-4">
            <div class="container">
                <div class="d-flex justify-content-around w-100">
                    <a href="{{ route('dashboard') }}" class="nav-link text-center {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-tachometer-alt d-block"></i>
                        <small>Dashboard</small>
                    </a>
                    <a href="{{ route('investments.index') }}" class="nav-link text-center {{ request()->routeIs('investments.*') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-chart-line d-block"></i>
                        <small>Invest</small>
                    </a>
                    <a href="{{ route('network.referral-link') }}" class="nav-link text-center {{ request()->routeIs('network.*') || request()->routeIs('mlm.*') ? 'text-primary' : 'text-muted' }}">
                        <i class="fas fa-users d-block"></i>
                        <small>Network</small>
                    </a>
                    <a href="{{ route('wallet.index') }}" class="nav-link text-center {{ request()->routeIs('wallet*') ? 'text-primary' : 'text-muted' }}">
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

            .theme-dark .navbar-collapse {
                background: rgba(30, 41, 59, 0.98) !important;
                border-color: rgba(71, 85, 105, 0.6) !important;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3) !important;
            }
        }

        .navbar-toggler {
            padding: 0.5rem;
            border: 2px solid rgba(255, 102, 0, 0.2);
            background: rgba(255, 102, 0, 0.1);
            border-radius: 0.5rem;
            color: #ff6600;
        }

        .theme-dark .navbar-toggler {
            border-color: rgba(255, 102, 0, 0.5) !important;
            background: rgba(255, 102, 0, 0.2) !important;
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

        /* Dark mode text overrides */
        .theme-dark .text-muted {
            color: #94a3b8 !important;
        }

        .theme-dark .text-primary {
            color: #ff6600 !important;
        }

        /* Ensure proper text colors in both modes */
        body:not(.theme-dark) .text-dark {
            color: #2d2d2d !important;
        }

        body:not(.theme-dark) .text-muted {
            color: #64748b !important;
        }

        /* Bottom mobile navigation dark mode */
        .theme-dark .navbar-light {
            background: rgba(30, 41, 59, 0.95) !important;
            border-top: 1px solid rgba(71, 85, 105, 0.6) !important;
        }

        .theme-dark .navbar-light .nav-link {
            color: #f1f5f9 !important;
        }

        .theme-dark .navbar-light .nav-link.text-primary {
            color: #ff6600 !important;
        }

        .theme-dark .navbar-light .nav-link.text-muted {
            color: #94a3b8 !important;
        }
    </style>

    <!-- Bootstrap JS and Custom Scripts -->
    <script>
        // Theme toggle functionality
        (function() {
            const toggle = document.getElementById('themeToggle');
            const icon = document.getElementById('themeIcon');
            const body = document.body;

            function applyTheme(theme) {
                if (theme === 'dark') {
                    body.classList.add('theme-dark');
                    if (icon) {
                        icon.className = 'fas fa-sun';
                        const text = toggle.querySelector('span');
                        if (text) text.textContent = 'Light';
                    }
                } else {
                    body.classList.remove('theme-dark');
                    if (icon) {
                        icon.className = 'fas fa-moon';
                        const text = toggle.querySelector('span');
                        if (text) text.textContent = 'Dark';
                    }
                }
            }

            // Initialize theme on page load
            const storedTheme = localStorage.getItem('jafran_theme');
            if (storedTheme) {
                applyTheme(storedTheme);
            } else {
                // Default to light mode
                applyTheme('light');
                localStorage.setItem('jafran_theme', 'light');
            }

            // Toggle theme on button click
            if (toggle) {
                toggle.addEventListener('click', function() {
                    const currentTheme = body.classList.contains('theme-dark') ? 'dark' : 'light';
                    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    localStorage.setItem('jafran_theme', newTheme);
                    applyTheme(newTheme);
                });
            }
        })();

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

        // Global CSRF token refresh for all forms
        function setupGlobalCSRFProtection() {
            // Refresh CSRF token every 15 minutes for active users
            setInterval(async function() {
                try {
                    const response = await fetch('/csrf-token', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Update all CSRF tokens in forms
                        document.querySelectorAll('input[name="_token"]').forEach(function(input) {
                            input.value = data.csrf_token;
                        });

                        // Update meta tag
                        const metaTag = document.querySelector('meta[name="csrf-token"]');
                        if (metaTag) {
                            metaTag.setAttribute('content', data.csrf_token);
                        }

                        console.log('CSRF token refreshed successfully');
                    }
                } catch (error) {
                    console.warn('Failed to refresh CSRF token:', error);
                }
            }, 15 * 60 * 1000); // 15 minutes

            // Handle form submissions to refresh token before submitting
            document.addEventListener('submit', async function(e) {
                const form = e.target;
                const csrfInput = form.querySelector('input[name="_token"]');

                if (csrfInput) {
                    try {
                        const response = await fetch('/csrf-token');
                        if (response.ok) {
                            const data = await response.json();
                            csrfInput.value = data.csrf_token;
                        }
                    } catch (error) {
                        console.warn('Failed to refresh CSRF token before form submission:', error);
                    }
                }
            });
        }

        // Initialize CSRF protection
        setupGlobalCSRFProtection();
    </script>

    @yield('scripts')
</body>
</html>
