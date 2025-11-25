<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('ERP System') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <style>
    /* ──────────────────────────────
       BASIC SIDEBAR LAYOUT
       ────────────────────────────── */
    #sidebar {
        overflow-y: auto;
        scrollbar-width: thin;
        max-height: calc(100vh - 2rem);
        display: flex;
        flex-direction: column;
        contain: unset !important;
    }

    #sidebar > nav {
        overflow-y: auto;
        overflow-x: visible;
        flex: 1;
        -webkit-overflow-scrolling: touch;
    }

    /* Mobile overlay & slide-in */
    #sidebar-overlay.active { display: block; }
    #sidebar.active { transform: translateX(0); }

    /* ──────────────────────────────
       COLLAPSED SIDEBAR (72px icon-only)
       ────────────────────────────── */
    #sidebar.collapsed {
        width: 72px !important;
        overflow-x: visible !important;
    }

    #sidebar.collapsed .sidebar-text,
    #sidebar.collapsed nav .pl-8 a span { display: none !important; }

    #sidebar.collapsed #sidebar-logo { height: 32px; }

    #sidebar.collapsed nav a,
    #sidebar.collapsed nav button {
        justify-content: center !important;
        padding: 0.5rem !important;
        position: relative;
    }

    #sidebar.collapsed nav .pl-8,
    #sidebar.collapsed nav .pl-8 a {
        padding-left: 0 !important;
        justify-content: center !important;
    }

    /* ──────────────────────────────
       TOOLTIPS – DYNAMIC POSITION (WORK EVEN WHEN SCROLLED!)
       ────────────────────────────── */
    .sidebar-tooltip {
        position: fixed !important;
        left: 84px;                    /* 72px sidebar + 12px gap */
        background: rgba(15, 23, 42, 0.98);
        color: #fff;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        white-space: nowrap;
        z-index: 999999;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, transform 0.2s ease;
        transform: translateY(-50%);
        box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-tooltip.visible {
        opacity: 1;
        visibility: visible;
    }

    .sidebar-tooltip::before {
        content: "";
        position: absolute;
        top: 50%;
        left: -7px;
        transform: translateY(-50%);
        border: 7px solid transparent;
        border-right-color: rgba(15, 23, 42, 0.98);
    }
</style>
</head>
<body class="font-sans text-slate-900 antialiased transition-colors duration-300" id="app-body">
    @php
        $user = auth()->user();
    @endphp

    <!-- Main Layout -->
    @if($user && ($user->hasRole(['super_admin', 'super-admin', 'Admin', 'admin', 'client', 'Sales Manager', 'Sales Employee'])))
        <div class="flex min-h-screen">
            <!-- Mobile Hamburger Menu -->
            @if($user && ($user->hasRole(['super_admin', 'super-admin']) || ($user->hasRole(['Admin', 'admin']) && $user->is_remote != 1)))
                <button id="sidebar-toggle" class="md:hidden fixed top-4 left-4 z-50 p-2 bg-gray-800 text-white rounded-md" aria-label="{{ __('Toggle Sidebar') }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- Overlay for Mobile -->
                <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

                <!-- Sidebar -->
                <div id="sidebar" class="glass-dark text-slate-700 w-64 space-y-4 py-4 px-2 fixed inset-y-4 left-4 rounded-2xl transform -translate-x-[110%] transition-all duration-300 ease-in-out md:translate-x-0 md:static md:h-[calc(100vh-2rem)] z-50 shadow-2xl flex flex-col">
                    <div class="flex flex-col px-4 pt-2 sidebar-header-wrapper">
                        <div class="flex items-center justify-between w-full mb-2 sidebar-toggle-row">
                             <!-- Collapse Toggle Button (Desktop) -->
                            <button id="sidebar-collapse-toggle" class="hidden md:block p-2 hover:bg-slate-200 rounded-lg transition-all ml-auto" aria-label="{{ __('Toggle Sidebar') }}" title="{{ __('Collapse Sidebar') }}">
                                <svg class="w-5 h-5 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                                </svg>
                            </button>
                             <!-- Close Button for Mobile -->
                            <button id="sidebar-close" class="md:hidden p-2 text-slate-700 ml-auto" aria-label="{{ __('Close Sidebar') }}">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <a href="{{ route('dashboard') }}" class="flex flex-col items-center space-y-1 mb-2 sidebar-logo-link">
                            <span class="text-sm font-bold text-slate-700 sidebar-text text-center break-words w-full leading-tight px-1">
                                {{ \App\Models\Settings::get('company_name', 'ERP System') }}
                            </span>
                            @php
                                $logoPath = \App\Models\Settings::get('logo_path');
                                $logoUrl = $logoPath ? asset('storage/' . $logoPath) : asset('storage/login/white creative shoes logo.png');
                            @endphp
                            <img src="{{ $logoUrl }}" 
                                 alt="ERP Logo" 
                                 id="sidebar-logo"
                                 class="h-10 w-auto object-contain transition-all duration-300"
                                 onerror="this.style.display='none';">
                        </a>
                    </div>

                    <nav class="space-y-1">
                        @if(auth()->user()->hasRole(['super_admin', 'super-admin']) || (auth()->user()->hasRole(['Admin', 'admin']) && auth()->user()->is_remote != 1))
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}"
                               title="{{ __('Go to Dashboard') }}">
                                <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                <span class="sidebar-text ml-2">{{ __('Dashboard') }}</span>
                            </a>
                        @endif

                        @if(auth()->user()->hasRole(['super_admin', 'super-admin', 'Admin', 'admin']))
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('products.*') || request()->routeIs('inventory.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
                                        onclick="toggleDropdown(this)" 
                                        aria-expanded="{{ request()->routeIs('products.*') || request()->routeIs('inventory.*') ? 'true' : 'false' }}"
                                        aria-controls="inventory-dropdown"
                                        title="{{ __('Inventory Management') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2V7m16 0l-8 4-8-4"></path>
                                    </svg>
                                    <span class="sidebar-text ml-2">{{ __('Inventory') }}</span>
                                    <svg class="w-4 h-4 ml-auto transition-transform sidebar-dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="inventory-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('products.*') || request()->routeIs('inventory.*') ? '' : 'hidden' }}">
                                    @can('view inventory')
                                        <!-- Inventory Dashboard -->
    <a href="{{ route('inventory.dashboard') }}" 
        class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
        {{ request()->routeIs('inventory.dashboard') ? 'bg-gray-600' : '' }}">

        <!-- 📦 Inventory Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/>
        </svg>

        {{ __('Inventory Dashboard') }}
    </a>
                                         <!-- View Articles -->
    <a href="{{ route('products.index') }}" 
        class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
        {{ request()->routeIs('products.*') ? 'bg-gray-600' : '' }}">

        <!-- 📰 Articles Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 6h16M4 10h16M4 14h10M4 18h10"/>
        </svg>

        {{ __('View Articles') }}
    </a>
                                    @endcan
                                    <!-- Raw Materials -->
<a href="{{ route('raw-materials.index') }}" 
    class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
    {{ request()->routeIs('raw-materials.index') ? 'bg-gray-600 border-l-4 border-green-500' : '' }}">

    <!-- 🧱 Raw Materials Icon (Cube) -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73z"/>
    </svg>

    {{ __('Raw Materials') }}
</a>
                                </div>
                            </div>
                        @endhasanyrole

                        @if(auth()->user()->hasRole(['super_admin', 'super-admin', 'Admin', 'admin']))
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left 
    {{ request()->routeIs('production.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
    onclick="toggleDropdown(this)" 
    aria-expanded="{{ request()->routeIs('production.*') ? 'true' : 'false' }}"
    aria-controls="production-dropdown"
    title="{{ __('Production Management') }}">

    <!-- 🏭 Production SVG Icon -->
  <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 21v-7l5-3 4 2 4-2 5 3v7H3z" />
    <path stroke-linecap="round" stroke-linejoin="round"
          d="M12 3l1 2 2 .5-1.5 1.5.3 2-1.8-1-1.8 1 .3-2L9 5.5l2-.5 1-2z" />
</svg>



    {{ __('Production') }}

    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
</button>


                                <div id="production-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('production.*') ? '' : 'hidden' }}">
                                    <!-- Production Dashboard -->
<a href="{{ route('production.dashboard') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('production.dashboard') ? 'bg-gray-600' : '' }}">

    <!-- 🏭 Factory / Production Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 21v-8l6-3 6 3v8H3zm18 0v-6l-3-1.5V21h3zM9 10V3l6 3v4"/>
    </svg>

    {{ __('Dashboard') }}
</a>


<!-- Batch Flow -->
<a href="{{ route('batch.flow.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('production.batch') ? 'bg-gray-600' : '' }}">

    <!-- 🔁 Workflow / Process Flow Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 4h7v7H4V4zm9 0h7v7h-7V4zM4 13h7v7H4v-7zm9 0h7v7h-7v-7z"/>
    </svg>

    {{ __('Batch Flow') }}
</a>

                                    <!-- <a href="{{ route('processes.index') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('processes.*') ? 'bg-gray-600' : '' }}">
                                        {{ __('Process Management') }}
                                    </a> -->
                                </div>
                            </div>
                        @endhasanyrole

                        @canany(['manage sales', 'view sales'])
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left 
    {{ request()->routeIs('sales.*') || request()->routeIs('clients.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
    onclick="toggleDropdown(this)" 
    aria-expanded="{{ request()->routeIs('sales.*') || request()->routeIs('clients.*') ? 'true' : 'false' }}"
    aria-controls="sales-dropdown"
    title="{{ __('Sales Management') }}">

    <img src="{{ asset('storage/icons/sales.jpeg') }}" 
         alt="Sales Icon" 
         class="w-5 h-5 mr-2 object-contain">

    {{ __('Sales') }}

    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
</button>

                                <div id="sales-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('sales.*') || request()->routeIs('clients.*') ? '' : 'hidden' }}">
                                    @can('manage sales')
                                         <!-- Sales Dashboard -->
    <a href="{{ route('sales.dashboard') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('sales.dashboard') ? 'bg-gray-600' : '' }}">

        <!-- 📊 Chart Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M11 3v18M6 12v9m10-6v6m5-9v9M3 21h18"/>
        </svg>

        {{ __('Sales Dashboard') }}
    </a>
                                        <!-- Quotations -->
    <a href="{{ route('quotations.index') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('quotations.*') ? 'bg-gray-600' : '' }}">

        <!-- 📝 Document Text Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 8h10M7 12h6M7 16h8M5 4h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1z"/>
        </svg>

        {{ __('Quotations') }}
    </a>

    <!-- Invoices -->
    <a href="{{ route('invoices.index') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('invoices.*') ? 'bg-gray-600' : '' }}">

        <!-- 💰 Invoice / Receipt Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 14h6m-6-4h6M7 3h10a2 2 0 012 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 012-2z"/>
        </svg>

        {{ __('Invoices') }}
    </a>

    <!-- Party / Clients -->
    <a href="{{ route('clients.index') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('clients.*') ? 'bg-gray-600' : '' }}">

        <!-- 👥 Users Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m3-6a4 4 0 110 8 4 4 0 010-8z"/>
        </svg>

        {{ __('Party') }}
    </a>

                                    @endcan
                                    @can('view production')
                                        <!-- Production Orders -->
    <a href="{{ route('production-orders.index') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('production-orders.*') ? 'bg-gray-600' : '' }}">

        <!-- 🏭 Factory / Production Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 21v-8l6-3 6 3v8H3zm18 0v-6l-3-1.5V21h3zM9 10V3l6 3v4"/>
        </svg>

        {{ __('Orders') }}
    </a>
                                    @endcan
                                </div>
                            </div>
                        @endcan

                        @hasanyrole('Sales Manager|Sales Employee')
                            <div>
                                <a href="{{ route('sales.products.index') }}" 
                                   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('sales.products.*') ? 'bg-gray-700 border-l-4 border-green-500' : '' }}"
                                   title="{{ __('Products') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V5a2 2 0 00-2-2H6a2 2 0 00-2 2v8m16 0l-8 8-8-8"></path>
                                    </svg>
                                    {{ __('Products') }}
                                </a>
                            </div>
                            <div>
                                <a href="{{ route('sales.cart.index') }}" 
                                   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('sales.cart.*') ? 'bg-gray-700 border-l-4 border-green-500' : '' }}"
                                   title="{{ __('Sales Cart') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 7M7 13l-2 5m5-5v5m4-5v5m1-10h2a2 2 0 012 2v2a2 2 0 01-2 2h-2"></path>
                                    </svg>
                                    {{ __('Sales Cart') }}
                                </a>
                            </div>
                        @endhasanyrole

                       @if(auth()->user()->hasRole(['super_admin', 'super-admin', 'Admin', 'admin']))
    <div>
       <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left 
    {{ request()->routeIs('supply-chain.*') || request()->routeIs('suppliers.*') || request()->routeIs('supplier-orders.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
    onclick="toggleDropdown(this)" 
    aria-expanded="{{ request()->routeIs('supply-chain.*') || request()->routeIs('suppliers.*') || request()->routeIs('supplier-orders.*') ? 'true' : 'false' }}"
    aria-controls="supply-chain-dropdown"
    title="{{ __('Supply Chain Management') }}">

    <img src="{{ asset('storage/icons/supplychain.jpeg') }}" 
         alt="Supply Chain Icon" 
         class="w-5 h-5 mr-2 object-contain">

    {{ __('Supply Chain') }}

    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
    </svg>
</button>


       <div id="supply-chain-dropdown" 
     class="pl-8 space-y-1 {{ request()->routeIs('supplier-orders*') || request()->routeIs('supply-chain.*') || request()->routeIs('suppliers.*') ? '' : 'hidden' }}">


   <!-- Dashboard -->
<a href="{{ route('supply-chain.dashboard') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('supply-chain.dashboard') ? 'bg-gray-600' : '' }}">
    
    <!-- Home Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0h6"/>
    </svg>

    {{ __('Dashboard') }}
</a>

    <!-- Active Orders -->
<a href="{{ route('supply-chain.active') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('supply-chain.active') ? 'bg-gray-600' : '' }}">

    <!-- Lightning Bolt Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 10V3L4 14h7v7l9-11h-7z"/>
    </svg>

    {{ __('Active Orders') }}
</a>

<!-- On-Time Orders -->
<a href="{{ route('supply-chain.ontime') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('supply-chain.ontime') ? 'bg-gray-600' : '' }}">

    <!-- Clock Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>

    {{ __('On-Time Orders') }}
</a>

<!-- Suppliers -->
<a href="{{ route('suppliers.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('suppliers.*') ? 'bg-gray-600' : '' }}">

    <!-- Users Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m3-6a4 4 0 110 8 4 4 0 010-8z"/>
    </svg>

    {{ __('Suppliers') }}
</a>

<!-- Supplier Orders -->
<a href="{{ route('supplier-orders.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('supplier-orders.*') ? 'bg-gray-600' : '' }}">

    <!-- Document Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M7 7h10M7 11h7M7 15h4M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
    </svg>

    {{ __('Supplier Orders') }}
</a>

<!-- Return Orders -->
<a href="{{ route('supplier-orders.returns') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('supplier-orders.returns') ? 'bg-gray-600' : '' }}">

    <!-- Refresh Arrow Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0114-4M20 14a8 8 0 01-14 4"/>
    </svg>

    {{ __('Return Orders') }}
</a>

</div>

    </div>
@endif


                        @if(auth()->user()->hasRole(['super_admin', 'super-admin', 'Admin', 'admin']))
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('finance.*') || request()->routeIs('transactions.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
                                        onclick="toggleDropdown(this)" 
                                        aria-expanded="{{ request()->routeIs('finance.*') || request()->routeIs('transactions.*') ? 'true' : 'false' }}"
                                        aria-controls="finance-dropdown"
                                        title="{{ __('Finance Management') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3v1H7v2h2v2h2v-2h2v-2h-2v-1c0-.552.448-1 1-1s1 .448 1 1v1h2v-2c0-1.657-1.343-3-3-3z"></path></svg>
                                    {{ __('Finance') }}
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div id="finance-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('finance.*') || request()->routeIs('transactions.*') ? '' : 'hidden' }}">
                                    <!-- Finance Dashboard -->
<a href="{{ route('finance.dashboard') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('finance.dashboard') ? 'bg-gray-600' : '' }}">

    <!-- 📈 Finance / Analytics Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 17l6-6 4 4 8-8M3 21h18"/>
    </svg>

    {{ __('Finance Dashboard') }}
</a>
                                    <!-- Transactions -->
<a href="{{ route('transactions.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('transactions.index') ? 'bg-gray-600' : '' }}">

    <!-- 🔄 Transactions Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 4v6h6M20 20v-6h-6M4 10a8 8 0 0114-4M20 14a8 8 0 01-14 4"/>
    </svg>

    {{ __('Transactions') }}
</a>
                                </div>
                            </div>
                        @endif

                        @role('client')
                            <a href="{{ route('client.products') }}" 
                               class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('products.*') ? 'bg-gray-700 border-l-4 border-green-500' : '' }}">
                                🛍️ {{ __('Products / Articles') }}
                            </a>
                            <a href="{{ route('client.orders') }}" 
                               class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('orders.*') ? 'bg-gray-700 border-l-4 border-green-500' : '' }}">
                                📦 {{ __('Orders') }}
                            </a>
                            <a href="{{ route('client.cart') }}" 
                               class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('client.cart*') ? 'bg-gray-700 border-l-4 border-green-500' : '' }}">
                                🛒 {{ __('Cart') }}
                            </a>
                        @endrole

                        @if(auth()->user()->hasRole(['super_admin', 'super-admin', 'Admin', 'admin']))
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('employees.*') || request()->routeIs('manager-portal.*') || request()->routeIs('attendance.*') || request()->routeIs('warning-letters.*') || request()->routeIs('leave-management.*') || request()->routeIs('performance-reviews.*') || request()->routeIs('payrolls.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
                                        onclick="toggleDropdown(this)" 
                                        aria-expanded="{{ request()->routeIs('employees.*') || request()->routeIs('manager-portal.*') || request()->routeIs('attendance.*') || request()->routeIs('warning-letters.*') || request()->routeIs('leave-management.*') || request()->routeIs('performance-reviews.*') || request()->routeIs('payrolls.*') ? 'true' : 'false' }}"
                                        aria-controls="hr-dropdown"
                                        title="{{ __('HR and Payroll Management') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                     {{ __('HR & Payroll') }}
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div id="hr-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('employees.*') || request()->routeIs('manager-portal.*') || request()->routeIs('attendance.*') || request()->routeIs('warning-letters.*') || request()->routeIs('leave-management.*') || request()->routeIs('performance-reviews.*') || request()->routeIs('payrolls.*') ? '' : 'hidden' }}">
                                    @can('view hr')
                                         <!-- Employees -->
    <a href="{{ route('employees.index') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('employees.*') ? 'bg-gray-600' : '' }}">

        <!-- 👤 Employee Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 12a4 4 0 100-8 4 4 0 000 8zm6 8v-2a4 4 0 00-4-4H10a4 4 0 00-4 4v2"/>
        </svg>

        {{ __('Employees') }}
    </a>
                                    @endcan
                                    @can('manage hr')
                                         <!-- Payroll Management -->
    <a href="{{ route('payrolls.worker_payroll_index') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('payrolls.*') ? 'bg-gray-600' : '' }}">

        <!-- 💵 Payroll Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 10h18M3 14h18M7 6h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2zm5 4v4m0 0l2-2m-2 2l-2-2"/>
        </svg>

        {{ __('Payroll Management') }}
    </a>
                                        <!-- <a href="{{ route('attendance.print') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('attendance.print') ? 'bg-gray-600' : '' }}">{{ __('Attendance Report') }}</a> -->
                                        <!-- <a href="{{ route('warning-letters.index') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('warning-letters.*') ? 'bg-gray-600' : '' }}">{{ __('Warning Letters') }}</a> -->
                                        <!-- <a href="{{ route('leave-management.index') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('leave-management.*') ? 'bg-gray-600' : '' }}">{{ __('Leave Management') }}</a> -->
                                        <!-- <a href="{{ route('performance-reviews.index') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('performance-reviews.*') ? 'bg-gray-600' : '' }}">{{ __('Performance Reviews') }}</a> -->
                                    @endcan
                                    <!-- @can('access manager portal')
                                        <a href="{{ route('manager-portal.index') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('manager-portal.*') ? 'bg-gray-600' : '' }}">{{ __('Manager Portal') }}</a>
                                    @endcan -->
                                    <!-- Advance Salary -->
<a href="{{ route('salary-advance.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('salary-advance.*') ? 'bg-gray-600' : '' }}">

    <!-- 💰 Money / Cash Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4m8-4a8 8 0 11-16 0 8 8 0 0116 0zm-8 4v2m0-10V6"/>
    </svg>

    {{ __('Advance Salary') }}
</a>
                                </div>
                            </div>
                        @endhasanyrole

                        @if(auth()->user()->hasRole(['super_admin', 'super-admin', 'Admin', 'admin']))
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('reports.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
                                        onclick="toggleDropdown(this)" 
                                        aria-expanded="{{ request()->routeIs('reports.*') ? 'true' : 'false' }}"
                                        aria-controls="reports-dropdown"
                                        title="{{ __('View Reports') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    {{ __('Reports') }}
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div id="reports-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('reports.*') ? '' : 'hidden' }}">
                                    <!-- Sales Report -->
<a href="{{ route('reports.sales') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('reports.sales') ? 'bg-gray-600' : '' }}">

    <!-- 📊 Bar Chart Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 19h4V9H4v10zm6 0h4V5h-4v14zm6 0h4V12h-4v7z"/>
    </svg>

    {{ __('Sales Report') }}
</a>
                                    <!-- <a href="{{ route('reports.inventory') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('reports.inventory') ? 'bg-gray-600' : '' }}">{{ __('Inventory Report') }}</a> -->
                                    <!-- Finance Report -->
<a href="{{ route('reports.finance') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('reports.finance') ? 'bg-gray-600' : '' }}">

    <!-- 💵 Money / Finance Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M3 10h18M3 14h18M7 6h10a2 2 0 012 2v8a2 2 0 01-2 2H7a2 2 0 01-2-2V8a2 2 0 012-2z"/>
    </svg>

    {{ __('Finance Report') }}
</a>
                                    <!-- Employee Performance -->
<a href="{{ route('reports.employee-performance') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('reports.employee-performance') ? 'bg-gray-600' : '' }}">

    <!-- ⭐ Performance / Rating Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 17l-5.2 2.7 1-5.7L3 9.8l5.8-.8L12 4l3.2 5 5.8.8-4.8 3.9 1 5.7z"/>
    </svg>

    {{ __('Employee Performance') }}
</a>
                                    <!-- <a href="{{ route('reports.payroll') }}" class="block px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('reports.payroll') ? 'bg-gray-600' : '' }}">{{ __('Payroll Report') }}</a> -->
                                </div>
                            </div>
                        @endhasanyrole

                        @can('manage users')
                            <div>
                                <button class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('users.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
                                        onclick="toggleDropdown(this)" 
                                        aria-expanded="{{ request()->routeIs('users.*') ? 'true' : 'false' }}"
                                        aria-controls="users-dropdown"
                                        title="{{ __('User Management') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    {{ __('Users') }}
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </button>
                                <div id="users-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('users.*') ? '' : 'hidden' }}">
                                   <!-- Manage Users -->
<a href="{{ route('users.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('users.index') ? 'bg-gray-600' : '' }}">

    <!-- 👥 Users Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4m3-6a4 4 0 110 8 4 4 0 010-8z"/>
    </svg>

    {{ __('Manage Users') }}
</a>


<!-- Edit Profile -->
<a href="{{ route('users.edit', auth()->user()) }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('users.edit') ? 'bg-gray-600' : '' }}">

    <!-- ✏️ Edit / Profile Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15.232 5.232a2.5 2.5 0 113.536 3.536l-9.192 9.192L6 18l.04-3.576 9.192-9.192z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7l1 1"/>
    </svg>

    {{ __('Edit Profile') }}
</a>

                                </div>
                            </div>
                        @endcan

                        @can('view notifications')
                            <a href="{{ route('notifications.index') }}" 
                               class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded {{ request()->routeIs('notifications.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}"
                               title="{{ __('View Notifications') }}">
                                <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                {{ __('Notifications') }}
                                @php
                                    $unreadNotificationsCount = auth()->check() && auth()->user()->hasNotifications() ? auth()->user()->unreadNotifications()->count() : 0;
                                @endphp
                                @if ($unreadNotificationsCount > 0)
                                    <span id="notification-badge" class="ml-2 bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">{{ $unreadNotificationsCount }}</span>
                                @else
                                    <span id="notification-badge" class="ml-2 bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1 hidden">{{ $unreadNotificationsCount }}</span>
                                @endif
                            </a>
                        @endcan

                        @can('manage settings')
                            <div>
                                <button id="settings-toggle" 
                                        class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left {{ request()->routeIs('settings.*') ? 'bg-gray-700 border-l-4 border-blue-500' : '' }}" 
                                        onclick="toggleDropdown(this)" 
                                        aria-expanded="{{ request()->routeIs('settings.*') ? 'true' : 'false' }}"
                                        aria-controls="settings-dropdown"
                                        title="{{ __('Settings') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ __('Settings') }}
                                    <svg class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="settings-dropdown" class="pl-8 space-y-1 {{ request()->routeIs('settings.*') ? '' : 'hidden' }}">
                                    <!-- General Settings -->
<a href="{{ route('settings.index') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('settings.index') ? 'bg-gray-600' : '' }}">

    <!-- ⚙️ Settings Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10.325 4.317l.675-1.962 2 0 .675 1.962a7.97 7.97 0 012.578 1.49l1.86-.68 1 1.732-1.86.68a7.97 7.97 0 010 2.98l1.86.68-1 1.732-1.86-.68a7.97 7.97 0 01-2.578 1.49L13 21.645h-2l-.675-1.962a7.97 7.97 0 01-2.578-1.49l-1.86.68-1-1.732 1.86-.68a7.97 7.97 0 010-2.98l-1.86-.68 1-1.732 1.86.68a7.97 7.97 0 012.578-1.49z"/>
        <circle cx="12" cy="12" r="3" stroke-width="2"/>
    </svg>

    {{ __('General') }}
</a>


@role('super_admin')

    <!-- Roles -->
    <a href="{{ route('settings.roles') }}" 
       class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
       {{ request()->routeIs('settings.roles') ? 'bg-gray-600' : '' }}">

        <!-- 🛡️ Shield / Roles Icon -->
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 3l8 4v6c0 5-3.5 9-8 9s-8-4-8-9V7l8-4z"/>
        </svg>

        {{ __('Roles') }}
    </a>

@endrole


<!-- Activity Log -->
<a href="{{ route('settings.activity') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('settings.activity') ? 'bg-gray-600' : '' }}">

    <!-- 📜 Document / History Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-4-2-4 2-4-2-4 2V6a2 2 0 012-2z"/>
    </svg>

    {{ __('Activity Log') }}
</a>


<!-- Backup & Restore -->
<a href="{{ route('settings.backup') }}" 
   class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded 
   {{ request()->routeIs('settings.backup') ? 'bg-gray-600' : '' }}">

    <!-- 💾 Backup Icon -->
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4v16m0-16l-3 3m3-3l3 3M5 10h14M5 14h14"/>
    </svg>

    {{ __('Backup & Restore') }}
</a>

                                </div>
                            </div>
                        @endcan

                        <!-- Language Toggle -->
                        <div class="px-4 py-2">
                            <select id="language-toggle" class="w-full bg-white text-slate-700 border border-slate-300 rounded-lg p-2 focus:ring-2 focus:ring-purple-400 focus:border-purple-400 font-medium cursor-pointer shadow-sm hover:border-purple-300 transition-all" onchange="window.location.href='/lang/' + this.value" aria-label="{{ __('Select Language') }}">
                                <option value="en" {{ app()->getLocale() === 'en' ? 'selected' : '' }}>{{ __('English') }}</option>
                                <option value="ar" {{ app()->getLocale() === 'ar' ? 'selected' : '' }}>{{ __('Arabic') }}</option>
                                <option value="hi" {{ app()->getLocale() === 'hi' ? 'selected' : '' }}>{{ __('Hindi') }}</option>
                                <option value="te" {{ app()->getLocale() === 'te' ? 'selected' : '' }}>{{ __('Telugu') }}</option>
                            </select>
                        </div>

                        @auth
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center px-4 py-2 text-gray-100 hover:bg-gray-700 rounded w-full text-left"
                                        title="{{ __('Logout') }}">
                                    <svg class="w-5 h-5 sidebar-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    {{ __('Logout') }}
                                </button>
                            </form>
                        @endauth
                    </nav>
                </div>
            @endif

            <!-- Main Content -->
            <div class="flex-1 flex flex-col md:ml-4 md:mr-4 md:my-4 h-[calc(100vh-2rem)] overflow-y-auto rounded-4xl glass-card transition-all duration-300" id="main-content">
                <!-- Navbar -->
                <header class="bg-white/50 backdrop-blur-sm border-b border-slate-200 p-4 flex flex-col md:flex-row justify-between items-center relative space-y-4 md:space-y-0 z-40">
                    <div class="flex items-center justify-between w-full md:w-auto">
                        <h1 class="text-xl md:text-2xl font-semibold text-gray-800 tracking-normal uppercase">
                            @if(!($user && $user->hasRole(['Admin', 'admin']) && $user->is_remote == 0))
                                <img src="{{ asset('storage/login/LOGO MARIX.png') }}" 
                                     alt="Company Logo" 
                                     class="h-12 md:h-16 object-contain">
                            @endif
                        </h1>
                        <div class="md:hidden" x-data="{ open: false }">
                            <button @click="open = !open" class="text-gray-700 focus:outline-none">
                                <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                                <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute top-full right-0 mt-2 w-64 bg-white shadow-lg rounded-lg z-50">
                                @if($user && $user->hasRole(['Admin', 'admin']) && $user->is_remote == 1)
                                    <a href="{{ route('admin.online') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Dashboard</a>
                                    <a href="{{ route('products.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Articles</a>
                                    <a href="{{ route('invoices.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Invoices</a>
                                    <a href="{{ route('clients.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Party</a>
                                    <a href="{{ route('production-orders.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Orders</a>
                                    <a href="{{ route('notifications.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
                                        Notifications
                                        @php $unreadNotificationsCount = auth()->user()->unreadNotifications()->count() ?? 0; @endphp
                                        @if ($unreadNotificationsCount > 0)
                                            <span class="ml-2 bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadNotificationsCount }}</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Profile</a>
                                @endif
                               @role('client', 'web') 

    <a href="{{ route('client.dashboard') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Dashboard</a>
    <a href="{{ route('client.orders') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Orders</a>
    <a href="{{ route('client.cart') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Cart</a>
    <a href="{{ route('client.notifications') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
        Notifications
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="ml-2 bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
        @endif
    </a>
    {{-- 🔹 New Quotations Link --}}
    <a href="{{ route('client.quotations') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">
        Quotations
    </a>
    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Profile</a>
    <a href="{{ route('support.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Support</a>
@endrole

                                @if($user->hasAnyRole(['Sales Manager', 'Sales Employee']))
                                    <a href="{{ route('sales.dashboard') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Dashboard</a>
                                    <a href="{{ route('quotations.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Quotations</a>
                                    <a href="{{ route('invoices.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Invoices</a>
                                    <a href="{{ route('clients.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Party</a>
                                    <a href="{{ route('production-orders.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Orders</a>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-100">Profile</a>
                                @endif
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="hidden md:flex items-center space-x-4 md:space-x-6 w-full justify-end">
                        @if($user && $user->hasRole(['Admin', 'admin']) && $user->is_remote == 1)
                            <a href="{{ route('admin.online') }}" 
                               class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('admin.online') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Dashboard
                            </a>
                            <a href="{{ route('products.index') }}" 
                               class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('products.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Articles
                            </a>
                            <a href="{{ route('invoices.index') }}" 
                               class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('invoices.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Invoices
                            </a>
                            <a href="{{ route('clients.index') }}" 
                               class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('clients.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Party
                            </a>
                            <a href="{{ route('production-orders.index') }}" 
                               class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('production-orders.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Orders
                            </a>
                            <a href="{{ route('notifications.index') }}" 
                               class="flex items-center px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('notifications.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Notifications
                               @if ($unreadNotificationsCount > 0)
                                   <span class="ml-2 bg-red-500 text-white text-xs font-semibold rounded-full px-2 py-1">
                                       {{ $unreadNotificationsCount }}
                                   </span>
                               @endif
                            </a>
                            <a href="{{ route('profile.edit') }}" 
                               class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all {{ request()->routeIs('profile.*') ? 'bg-primary-50 text-primary-700' : '' }}">
                               Profile
                            </a>
                        @endif
                       @role('client')
    <a href="{{ route('client.dashboard') }}" class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('client.dashboard*') ? 'bg-blue-50 text-blue-700' : '' }}">Dashboard</a>
    <a href="{{ route('client.orders') }}" class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('orders.*') ? 'bg-blue-50 text-blue-700' : '' }}">Orders</a>
    <a href="{{ route('client.cart') }}" class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('client.cart*') ? 'bg-blue-50 text-blue-700' : '' }}">Cart</a>
    <a href="{{ route('client.notifications') }}" class="relative px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('client.notifications*') ? 'bg-blue-50 text-blue-700' : '' }}">
        Notifications
        @if(isset($unreadCount) && $unreadCount > 0)
            <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">{{ $unreadCount }}</span>
        @endif
    </a>
    <a href="{{ route('client.quotations') }}" class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('client.quotations*') ? 'bg-blue-50 text-blue-700' : '' }}">
        Quotations
    </a>
    <a href="{{ route('profile.edit') }}" class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('profile.edit*') ? 'bg-blue-50 text-blue-700' : '' }}">Profile</a>
    <a href="{{ route('support.index') }}" class="px-4 py-2 text-gray-700 font-medium rounded-lg hover:bg-blue-100 {{ request()->routeIs('support.index*') ? 'bg-blue-50 text-blue-700' : '' }}">General Support</a>
@endrole

                        @if($user->hasAnyRole(['Sales Manager', 'Sales Employee']))
                            <a href="{{ route('sales.dashboard') }}" class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all">Dashboard</a>
                            <a href="{{ route('quotations.index') }}" class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all">Quotations</a>
                            <a href="{{ route('invoices.index') }}" class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all">Invoices</a>
                            <a href="{{ route('clients.index') }}" class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all">Clients</a>
                            <a href="{{ route('production-orders.index') }}" class="px-4 py-2 text-slate-700 font-medium hover:text-primary-600 hover:bg-slate-100 rounded-lg transition-all">Orders</a>
                        @endif
                        @if($user)
                            <div class="flex items-center space-x-4">
                                <span class="text-slate-700 font-medium">{{ $user->name }}</span>
                                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 text-white bg-red-500 hover:bg-red-600 rounded-lg shadow-sm transition-all font-medium">Logout</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </header>

                <!-- Page Content -->
                <main id="main-content" class="flex-1 p-6 transition-all duration-300">
                    @if (session('success'))
                        <script>
                            Toastify({
                                text: "{{ session('success') }}",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#10b981",
                                className: "rounded-lg",
                            }).showToast();
                        </script>
                    @endif
                    @if (session('warning'))
                        <script>
                            Toastify({
                                text: "{{ session('warning') }}",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "#f59e0b",
                                className: "rounded-lg",
                            }).showToast();
                        </script>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <div class="flex min-h-screen">
            <div class="flex-1 p-6">
                <h1 class="text-2xl font-bold">Access Denied</h1>
                <p>Please log in or contact support.</p>
            </div>
        </div>
    @endif

    <div id="loading-spinner" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden">
        <div class="w-12 h-12 border-4 border-t-4 border-blue-500 rounded-full animate-spin"></div>
    </div>



<script>
    // Dynamic tooltips that follow the hovered item perfectly
    document.addEventListener('DOMContentLoaded', () => {
        const tooltip = document.createElement('div');
        tooltip.className = 'sidebar-tooltip';
        document.body.appendChild(tooltip);

        const showTooltip = (e) => {
            const el = e.target.closest('a, button');
            if (!el || !document.getElementById('sidebar')?.classList.contains('collapsed')) return;

            const label = el.getAttribute('data-label') || el.innerText.trim();
            if (!label) return;

            tooltip.textContent = label;

            // Position exactly next to the hovered item
            const rect = el.getBoundingClientRect();
            tooltip.style.top = (rect.top + rect.height / 2) + 'px';

            tooltip.classList.add('visible');
        };

        const hideTooltip = () => {
            tooltip.classList.remove('visible');
        };

        // Attach to all sidebar links/buttons
        document.querySelectorAll('#sidebar nav a, #sidebar nav button').forEach(el => {
            el.addEventListener('mouseenter', showTooltip);
            el.addEventListener('mouseleave', hideTooltip);
            el.addEventListener('focus', showTooltip);
            el.addEventListener('blur', hideTooltip);
        });

        // Re-attach after any Livewire/Alpine updates
        document.addEventListener('livewire:load', () => {
            setTimeout(() => {
                document.querySelectorAll('#sidebar nav a, #sidebar nav button').forEach(el => {
                    el.removeEventListener('mouseenter', showTooltip);
                    el.removeEventListener('mouseleave', hideTooltip);
                    el.addEventListener('mouseenter', showTooltip);
                    el.addEventListener('mouseleave', hideTooltip);
                });
            }, 100);
        });
    });

    // Keep data-label populated (your existing function is perfect)
    function populateSidebarLabels() {
        document.querySelectorAll('#sidebar nav a, #sidebar nav button').forEach(el => {
            let label = el.querySelector('.sidebar-text')?.textContent.trim();
            if (!label) {
                label = Array.from(el.childNodes)
                    .filter(n => n.nodeType === Node.TEXT_NODE)
                    .map(n => n.textContent.trim())
                    .join('').trim();
            }
            if (label) el.setAttribute('data-label', label);
        });
    }

    // Run on load + after any dynamic changes
    document.addEventListener('DOMContentLoaded', () => {
        populateSidebarLabels();
        setTimeout(populateSidebarLabels, 500);
        setTimeout(populateSidebarLabels, 1500);
    });
    document.body.addEventListener('click', () => setTimeout(populateSidebarLabels, 100));
</script>

    <script>
    // Populate data-label for tooltips (runs on load + after any dynamic changes)
    function populateSidebarLabels() {
        document.querySelectorAll('#sidebar nav a, #sidebar nav button').forEach(el => {
            let label = '';

            // 1. Try .sidebar-text
            const textSpan = el.querySelector('.sidebar-text');
            if (textSpan) {
                label = textSpan.textContent.trim();
            }

            // 2. Try direct text nodes (e.g. emoji + text)
            if (!label) {
                label = Array.from(el.childNodes)
                    .filter(n => n.nodeType === Node.TEXT_NODE)
                    .map(n => n.textContent.trim())
                    .join('').trim();
            }

            // 3. Fallback: clean innerText (removes icons/emojis if needed)
            if (!label && el.innerText) {
                label = el.innerText.replace(/[\u{1F300}-\u{1F6FF}\u{2600}-\u{26FF}\u{2700}-\u{27BF}]/gu, '').trim();
            }

            if (label) {
                el.setAttribute('data-label', label);
            }
        });
    }

    // Run multiple times to catch Alpine.js/Livewire delays
    document.addEventListener('DOMContentLoaded', () => {
        populateSidebarLabels();
        setTimeout(populateSidebarLabels, 300);
        setTimeout(populateSidebarLabels, 800);
    });

    // Re-run on any click (safe for dropdowns)
    document.body.addEventListener('click', () => setTimeout(populateSidebarLabels, 100));
</script>

    <script>
        // Sidebar and Dropdown Toggle
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        }

        function toggleDropdown(button) {
            const dropdownId = button.getAttribute('aria-controls');
            const dropdown = document.getElementById(dropdownId);
            const isHidden = dropdown.classList.contains('hidden');
            dropdown.classList.toggle('hidden', !isHidden);
            button.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            const arrow = button.querySelector('svg.ml-auto');
            if (arrow) {
                arrow.classList.toggle('rotate-180', isHidden);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('sidebar-toggle');
            const closeButton = document.getElementById('sidebar-close');
            const overlay = document.getElementById('sidebar-overlay');

            if (sidebar && toggleButton && closeButton && overlay) {
                // Initialize sidebar state
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                } else {
                    sidebar.classList.add('active');
                    overlay.classList.remove('active');
                }

                // Toggle sidebar
                toggleButton.addEventListener('click', toggleSidebar);
                closeButton.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);

                // Close sidebar when clicking outside on mobile
                document.addEventListener('click', (e) => {
                    if (window.innerWidth < 768 && sidebar.classList.contains('active') && !sidebar.contains(e.target) && !toggleButton.contains(e.target)) {
                        toggleSidebar();
                    }
                });

                // Handle window resize
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.add('active');
                        overlay.classList.remove('active');
                    } else {
                        sidebar.classList.remove('active');
                        overlay.classList.remove('active');
                    }
                });
            }

            // Sidebar Collapse/Expand functionality
            const collapseToggle = document.getElementById('sidebar-collapse-toggle');
            if (collapseToggle && sidebar) {
                // Check localStorage for saved state
                const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (isCollapsed) {
                    sidebar.classList.add('collapsed');
                }

                collapseToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');
                    const collapsed = sidebar.classList.contains('collapsed');
                    localStorage.setItem('sidebarCollapsed', collapsed);
                    
                    // Update toggle button icon
                    const icon = collapseToggle.querySelector('svg path');
                    if (collapsed) {
                        icon.setAttribute('d', 'M13 5l7 7-7 7M6 5l7 7-7 7');
                    } else {
                        icon.setAttribute('d', 'M11 19l-7-7 7-7m8 14l-7-7 7-7');
                    }
                });
            }

            // Initialize dropdowns
            document.querySelectorAll('[aria-controls]').forEach(button => {
                const dropdownId = button.getAttribute('aria-controls');
                const dropdown = document.getElementById(dropdownId);
                if (!dropdown.classList.contains('hidden')) {
                    const arrow = button.querySelector('svg.ml-auto');
                    if (arrow) arrow.classList.add('rotate-180');
                }
                button.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleDropdown(button);
                    }
                });
            });

            // Populate data-label attributes for all sidebar links/buttons
            function populateSidebarDataLabels() {
                const items = document.querySelectorAll('#sidebar nav a, #sidebar nav button');
                items.forEach(el => {
                    // Prefer explicit .sidebar-text span when present
                    const labelSpan = el.querySelector('.sidebar-text');
                    let label = '';
                    if (labelSpan) {
                        label = labelSpan.textContent.trim();
                    } else {
                        // Fallback: use visible text (exclude svg content)
                        label = el.childNodes && Array.from(el.childNodes)
                            .filter(n => n.nodeType === Node.TEXT_NODE)
                            .map(n => n.textContent.trim()).join(' ').trim();
                        if (!label) {
                            // last-resort: innerText
                            label = el.innerText.trim();
                        }
                    }
                    if (label) el.setAttribute('data-label', label);
                });
            }
            populateSidebarDataLabels();

            // Update notification count
            const updateNotificationCount = () => {
                fetch('/api/notifications/unread-count', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if (badge) {
                        badge.textContent = data.unread_count;
                        badge.classList.toggle('hidden', data.unread_count === 0);
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
            };
            updateNotificationCount();
            setInterval(updateNotificationCount, 30000);

            // Global Search
            const searchInput = document.getElementById('global-search');
            const searchResults = document.getElementById('search-results');
            if (searchInput && searchResults) {
                searchInput.addEventListener('input', debounce(async (e) => {
                    showLoadingSpinner();
                    const query = e.target.value.trim();
                    if (query.length < 3) {
                        searchResults.classList.add('hidden');
                        searchResults.innerHTML = '';
                        hideLoadingSpinner();
                        return;
                    }
                    try {
                        const response = await fetch(`/api/search?query=${encodeURIComponent(query)}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        const results = await response.json();
                        searchResults.innerHTML = '';
                        if (results.length === 0) {
                            searchResults.innerHTML = '<div class="p-4 text-gray-500">{{ __("No results found.") }}</div>';
                            searchResults.classList.remove('hidden');
                            hideLoadingSpinner();
                            return;
                        }
                        results.forEach(result => {
                            const item = document.createElement('a');
                            item.href = result.url;
                            item.className = 'block p-4 hover:bg-gray-100 border-b';
                            item.innerHTML = `
                                <div class="flex justify-between">
                                    <div>
                                        <div class="font-semibold">${result.title}</div>
                                        <div class="text-sm text-gray-500">${result.description}</div>
                                    </div>
                                    <div class="text-xs text-gray-400">${result.type}</div>
                                </div>
                            `;
                            searchResults.appendChild(item);
                        });
                        searchResults.classList.remove('hidden');
                    } catch (error) {
                        console.error('Error fetching search results:', error);
                    } finally {
                        hideLoadingSpinner();
                    }
                }, 300));
                document.addEventListener('click', (e) => {
                    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                        searchResults.classList.add('hidden');
                    }
                });
            }

            // Dark Mode Toggle
            const darkModeToggle = document.getElementById('dark-mode-toggle');
            const darkModeIcon = document.getElementById('dark-mode-icon');
            const body = document.getElementById('app-body');
            if (darkModeToggle && darkModeIcon && body) {
                if (localStorage.getItem('darkMode') === 'enabled' || (localStorage.getItem('darkMode') !== 'disabled' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    body.classList.add('dark');
                    darkModeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>';
                }
                darkModeToggle.addEventListener('click', () => {
                    body.classList.toggle('dark');
                    if (body.classList.contains('dark')) {
                        localStorage.setItem('darkMode', 'enabled');
                        darkModeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>';
                    } else {
                        localStorage.setItem('darkMode', 'disabled');
                        darkModeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>';
                    }
                });
            }

            // Loading Spinner
            function showLoadingSpinner() {
                document.getElementById('loading-spinner').classList.remove('hidden');
            }
            function hideLoadingSpinner() {
                document.getElementById('loading-spinner').classList.add('hidden');
            }

            // Debounce for search
            function debounce(func, wait) {
                let timeout;
                return function (...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
        });

        // Set current year dynamically
        document.getElementById('footer-year').textContent = new Date().getFullYear();
    </script>

    <footer class="bg-gray-900 text-gray-300 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center md:items-start space-y-6 md:space-y-0">
                <div class="flex flex-col items-center md:items-start space-y-2">
                    <p class="text-gray-400 text-sm">
                        Powered by 
                        <a href="mailto:contact@rmzintl.com" class="underline hover:text-white">
                            RMZ International
                        </a>
                    </p>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-6"></div>
            <div class="mt-4 text-center text-gray-500 text-xs">
                &copy; <span id="footer-year"></span> Marix. All rights reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @yield('scripts')

    <!-- @include('partials.chatbot') -->

</body>
</html>