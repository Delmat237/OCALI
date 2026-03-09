<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('theme', auth()->user()?->theme ?? 'light') }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'OCaLi') - {{ config('app.name', 'OCaLi') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <style>
        :root {
            --orange-fluo: #FFA500;
            --orange-fluo-hover: #FF8C00;
            --blue-roi: #4169E1;
            --blue-roi-hover: #3458C9;
            --gradient-primary: linear-gradient(135deg, #FFA500 0%, #FF6B00 100%);
            --gradient-secondary: linear-gradient(135deg, #4169E1 0%, #2E4BC9 100%);
            --gradient-dark: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            min-height: 100vh;
            overflow-x: hidden;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark body, html.dark body {
            background-color: #0f172a;
            color: #e2e8f0;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        /* ===== GEOMETRIC BACKGROUND SHAPES ===== */
        .geometric-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .geo-shape {
            position: absolute;
            opacity: 0.1;
        }

        .geo-circle {
            border-radius: 50%;
            border: 3px solid var(--orange-fluo);
        }

        .geo-circle-1 { width: 300px; height: 300px; top: -100px; right: -100px; animation: float 20s ease-in-out infinite; }
        .geo-circle-2 { width: 200px; height: 200px; bottom: 10%; left: -50px; animation: float 15s ease-in-out infinite reverse; }
        .geo-circle-3 { width: 150px; height: 150px; top: 50%; right: 10%; animation: pulse 8s ease-in-out infinite; }

        .geo-triangle {
            width: 0;
            height: 0;
            border-left: 80px solid transparent;
            border-right: 80px solid transparent;
            border-bottom: 140px solid var(--blue-roi);
        }

        .geo-triangle-1 { top: 20%; left: 5%; animation: rotate 30s linear infinite; }
        .geo-triangle-2 { bottom: 20%; right: 5%; animation: rotate 25s linear infinite reverse; }

        .geo-diamond {
            width: 100px;
            height: 100px;
            background: var(--gradient-primary);
            transform: rotate(45deg);
        }

        .geo-diamond-1 { top: 40%; left: 2%; animation: float 18s ease-in-out infinite; }
        .geo-diamond-2 { bottom: 30%; right: 15%; animation: float 22s ease-in-out infinite reverse; }

        .geo-hexagon {
            width: 120px;
            height: 70px;
            background: var(--blue-roi);
            position: relative;
        }

        .geo-hexagon::before,
        .geo-hexagon::after {
            content: '';
            position: absolute;
            width: 0;
            border-left: 60px solid transparent;
            border-right: 60px solid transparent;
        }

        .geo-hexagon::before {
            bottom: 100%;
            border-bottom: 35px solid var(--blue-roi);
        }

        .geo-hexagon::after {
            top: 100%;
            border-top: 35px solid var(--blue-roi);
        }

        .geo-hexagon-1 { top: 60%; left: 20%; animation: pulse 12s ease-in-out infinite; }

        .geo-cube {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--orange-fluo) 0%, transparent 50%);
            border: 2px solid var(--orange-fluo);
        }

        .geo-cube-1 { top: 15%; right: 20%; animation: rotate3d 20s linear infinite; }
        .geo-cube-2 { bottom: 5%; left: 30%; animation: rotate3d 15s linear infinite reverse; }

        /* ===== ANIMATIONS ===== */
        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); }
            25% { transform: translateY(-20px) translateX(10px); }
            50% { transform: translateY(0) translateX(20px); }
            75% { transform: translateY(20px) translateX(10px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.1; }
            50% { transform: scale(1.1); opacity: 0.2; }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotate3d {
            from { transform: rotate(0deg) rotateY(0deg); }
            to { transform: rotate(360deg) rotateY(360deg); }
        }

        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .animate-slide-up { animation: slideInUp 0.6s ease-out forwards; }
        .animate-slide-left { animation: slideInLeft 0.6s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 0.5s ease-out forwards; }

        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        .stagger-5 { animation-delay: 0.5s; }

        /* ===== NAVIGATION ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.9);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .dark .navbar {
            background: rgba(15, 23, 42, 0.95);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo img {
            height: 45px;
            width: auto;
        }

        .logo-text {
            font-family: 'Poppins', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        .nav-link {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            color: #475569;
            text-decoration: none;
            position: relative;
            padding: 0.5rem 0;
            transition: color 0.3s ease;
        }

        .dark .nav-link {
            color: #94a3b8;
        }

        .nav-link:hover {
            color: var(--orange-fluo);
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient-primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* ===== BUTTONS ===== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 165, 0, 0.4);
        }

        .btn-secondary {
            background: var(--gradient-secondary);
            color: white;
            box-shadow: 0 4px 15px rgba(65, 105, 225, 0.3);
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(65, 105, 225, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--orange-fluo);
            color: var(--orange-fluo);
        }

        .btn-outline:hover {
            background: var(--gradient-primary);
            color: white;
        }

        .btn-ghost {
            background: transparent;
            color: #475569;
        }

        .dark .btn-ghost {
            color: #94a3b8;
        }

        .btn-ghost:hover {
            background: rgba(255, 165, 0, 0.1);
            color: var(--orange-fluo);
        }

        /* Icon buttons */
        .btn-icon {
            width: 40px;
            height: 40px;
            padding: 0;
            border-radius: 12px;
        }

        /* ===== CARDS ===== */
        .card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }

        .dark .card {
            background: #1e293b;
        }

        .card-elevated {
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .card-elevated:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* Book Card - Diamond Shape Accent */
        .book-card {
            position: relative;
            overflow: visible;
        }

        .book-card::before {
            content: '';
            position: absolute;
            top: -10px;
            right: -10px;
            width: 30px;
            height: 30px;
            background: var(--gradient-primary);
            transform: rotate(45deg);
            z-index: 1;
            transition: all 0.3s ease;
        }

        .book-card:hover::before {
            transform: rotate(45deg) scale(1.2);
        }

        .book-cover {
            aspect-ratio: 2/3;
            overflow: hidden;
            position: relative;
            background-color: #f8fafc;
        }

        .dark .book-cover {
            background-color: #0f172a;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.5s ease;
        }

        .book-card:hover .book-cover img {
            transform: scale(1.05);
        }

        .book-cover-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: flex-end;
            padding: 1rem;
        }

        .book-card:hover .book-cover-overlay {
            opacity: 1;
        }

        .book-info {
            padding: 1rem;
        }

        .book-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.25rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .dark .book-title {
            color: #f1f5f9;
        }

        .book-author {
            font-size: 0.85rem;
            color: #64748b;
        }

        .book-rating {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            margin-top: 0.5rem;
        }

        .book-rating i {
            color: #FFA500;
            font-size: 0.8rem;
        }

        /* ===== CONTENT AREA ===== */
        .main-content {
            position: relative;
            z-index: 1;
            padding-top: 70px;
            min-height: 100vh;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }

        /* ===== SECTION STYLES ===== */
        .section {
            padding: 4rem 0;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 2rem;
            color: var(--blue-roi);
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        /* ===== GRID LAYOUTS ===== */
        .grid-books {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .grid-books {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 1rem;
            }
        }

        /* ===== FOOTER ===== */
        .footer {
            background: var(--gradient-dark);
            color: white;
            padding: 4rem 0 2rem;
            position: relative;
            overflow: hidden;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 200px;
            background: var(--gradient-primary);
            border-radius: 50%;
            opacity: 0.1;
            filter: blur(60px);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
        }

        .footer-brand {
            max-width: 300px;
        }

        .footer-brand .logo {
            margin-bottom: 1rem;
        }

        .footer-brand p {
            color: #94a3b8;
            line-height: 1.6;
        }

        .footer-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--orange-fluo);
        }

        .footer-bottom {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: #64748b;
        }

        /* ===== THEME & LOCALE TOGGLES ===== */
        .toggle-btn {
            background: rgba(255, 165, 0, 0.1);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--orange-fluo);
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: var(--gradient-primary);
            color: white;
        }

        /* ===== MOBILE MENU ===== */
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #475569;
            cursor: pointer;
        }

        .dark .mobile-menu-btn {
            color: #94a3b8;
        }

        @media (max-width: 1024px) {
            .nav-links {
                display: none;
            }

            .nav-actions > *:not(.mobile-menu-btn) {
                display: none !important;
            }

            .mobile-menu-btn {
                display: block;
            }
        }

        /* ===== UTILITIES ===== */
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .text-gradient-blue {
            background: var(--gradient-secondary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* ===== ALERTS ===== */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #dc2626;
        }

        .alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #d97706;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            border: 1px solid rgba(59, 130, 246, 0.3);
            color: #2563eb;
        }

        /* ===== LOADING SKELETON ===== */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
            border-radius: 8px;
        }

        .dark .skeleton {
            background: linear-gradient(90deg, #334155 25%, #475569 50%, #334155 75%);
            background-size: 200% 100%;
        }

        /* ===== MOBILE MENU CSS ===== */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -300px;
            width: 300px;
            height: 100%;
            background: white;
            z-index: 1002;
            transition: all 0.4s cubic-bezier(0.77, 0, 0.175, 1);
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .dark .mobile-menu {
            background: #1e293b;
            box-shadow: -5px 0 30px rgba(0, 0, 0, 0.5);
        }

        .mobile-menu.active {
            right: 0;
        }

        .mobile-menu-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .mobile-menu-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .close-menu-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #475569;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .dark .close-menu-btn {
            color: #94a3b8;
        }

        .close-menu-btn:hover {
            color: var(--orange-fluo);
        }

        .mobile-menu-content {
            flex: 1;
            padding: 2rem 1.5rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .mobile-nav-links {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .mobile-nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-size: 1.1rem;
            color: #475569;
            text-decoration: none;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .dark .mobile-nav-link {
            color: #94a3b8;
        }

        .mobile-nav-link:hover, .mobile-nav-link.active {
            background: rgba(255, 165, 0, 0.1);
            color: var(--orange-fluo);
        }

        .mobile-nav-link i {
            width: 24px;
            text-align: center;
        }

        .mobile-menu-actions {
            margin-top: auto;
            padding-top: 2rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .mobile-menu-actions {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mobile-menu-footer {
            padding: 1.5rem;
            display: flex;
            justify-content: center;
            background: rgba(0, 0, 0, 0.02);
        }

        .dark .mobile-menu-footer {
            background: rgba(255, 255, 255, 0.02);
        }
        /* ===== PAGE LOADER ===== */
        #page-loader {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .dark #page-loader {
            background: rgba(15, 23, 42, 0.85);
        }

        #page-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-spinner {
            width: 80px;
            height: 80px;
            position: relative;
            margin-bottom: 2rem;
        }

        .loader-logo {
            position: absolute;
            inset: 15px;
            z-index: 2;
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .loader-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .loader-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top-color: var(--orange-fluo);
            border-bottom-color: var(--blue-roi);
            animation: rotate 1.5s linear infinite;
        }

        .loader-progress-bar {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--gradient-primary);
            z-index: 10000;
            width: 0;
            transition: width 0.3s ease;
        }

        @keyframes pulse-glow {
            0%, 100% { transform: scale(1); filter: drop-shadow(0 0 5px var(--orange-fluo)); }
            50% { transform: scale(1.1); filter: drop-shadow(0 0 20px var(--orange-fluo)); }
        }
    </style>


    @stack('styles')
</head>
<body>
    <!-- Page Loader -->
    <div id="page-loader">
        <div class="loader-spinner">
            <div class="loader-ring"></div>
            <div class="loader-logo">
                <img src="{{ asset('images/logo.png') }}" alt="OCaLi">
            </div>
        </div>
        <div class="loader-progress-bar" id="loaderProgressBar"></div>
    </div>

    <!-- Geometric Background -->
    <div class="geometric-bg">
        <div class="geo-shape geo-circle geo-circle-1"></div>
        <div class="geo-shape geo-circle geo-circle-2"></div>
        <div class="geo-shape geo-circle geo-circle-3"></div>
        <div class="geo-shape geo-triangle geo-triangle-1"></div>
        <div class="geo-shape geo-triangle geo-triangle-2"></div>
        <div class="geo-shape geo-diamond geo-diamond-1"></div>
        <div class="geo-shape geo-diamond geo-diamond-2"></div>
        <div class="geo-shape geo-cube geo-cube-1"></div>
        <div class="geo-shape geo-cube geo-cube-2"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="OCaLi">
                <span class="logo-text">OCaLi</span>
            </a>

            <div class="nav-links">
                <a href="{{ route('home') }}" class="nav-link">{{ __('messages.home') }}</a>
                <a href="{{ route('explore') }}" class="nav-link">{{ __('messages.explore') }}</a>
                <a href="{{ route('chronicles.index') }}" class="nav-link">{{ __('messages.chronicles') }}</a>
                <a href="{{ route('pricing') }}" class="nav-link">{{ __('messages.pricing') }}</a>
            </div>

            <div class="nav-actions">
                <!-- Language Toggle -->
                <form action="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="toggle-btn" title="{{ __('messages.switch_language') }}">
                        {{ app()->getLocale() === 'fr' ? 'EN' : 'FR' }}
                    </button>
                </form>

                <!-- Theme Toggle -->
                <form action="{{ route('theme.toggle') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="toggle-btn" title="{{ __('messages.toggle_theme') }}">
                        <i class="fas {{ session('theme', 'light') === 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                    </button>
                </form>

                @auth
                    <a href="{{ route('library') }}" class="btn btn-ghost">
                        <i class="fas fa-book-open"></i>
                        <span class="hidden md:inline">{{ __('messages.my_library') }}</span>
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-user"></i>
                        {{ __('messages.dashboard') }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-ghost" title="{{ __('messages.logout') }}">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden md:inline">{{ __('messages.logout') }}</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">{{ __('messages.register') }}</a>
                @endauth

                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <a href="{{ route('home') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="OCaLi">
                <span class="logo-text">OCaLi</span>
            </a>
            <button class="close-menu-btn" id="closeMenuBtn">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="mobile-menu-content">
            <div class="mobile-nav-links">
                <a href="{{ route('home') }}" class="mobile-nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> {{ __('messages.home') }}
                </a>
                <a href="{{ route('explore') }}" class="mobile-nav-link {{ request()->routeIs('explore') ? 'active' : '' }}">
                    <i class="fas fa-compass"></i> {{ __('messages.explore') }}
                </a>
                <a href="{{ route('chronicles.index') }}" class="mobile-nav-link {{ request()->routeIs('chronicles.*') ? 'active' : '' }}">
                    <i class="fas fa-feather-alt"></i> {{ __('messages.chronicles') }}
                </a>
                <a href="{{ route('pricing') }}" class="mobile-nav-link {{ request()->routeIs('pricing') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> {{ __('messages.pricing') }}
                </a>
            </div>

            <div class="mobile-menu-actions">
                @auth
                    <a href="{{ route('library') }}" class="btn btn-ghost" style="width: 100%; justify-content: flex-start;">
                        <i class="fas fa-book-open"></i> {{ __('messages.my_library') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">
                        <i class="fas fa-user"></i> {{ __('messages.dashboard') }}
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin-top: 1rem;">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="width: 100%;">
                            <i class="fas fa-sign-out-alt"></i> {{ __('messages.logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost" style="width: 100%;">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">{{ __('messages.register') }}</a>
                @endauth
            </div>

            <div class="mobile-menu-footer">
                <!-- Mobile Language Toggle -->
                <form action="{{ route('locale.switch', app()->getLocale() === 'fr' ? 'en' : 'fr') }}" method="POST" style="display: inline-block;">
                    @csrf
                    <button type="submit" class="toggle-btn" title="{{ __('messages.switch_language') }}">
                        {{ app()->getLocale() === 'fr' ? 'EN' : 'FR' }}
                    </button>
                </form>

                <!-- Mobile Theme Toggle -->
                <form action="{{ route('theme.toggle') }}" method="POST" style="display: inline-block; margin-left: 1rem;">
                    @csrf
                    <button type="submit" class="toggle-btn" title="{{ __('messages.toggle_theme') }}">
                        <i class="fas {{ session('theme', 'light') === 'dark' ? 'fa-sun' : 'fa-moon' }}"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="container" style="padding-top: 1rem;">
                <div class="alert alert-success animate-slide-up">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container" style="padding-top: 1rem;">
                <div class="alert alert-error animate-slide-up">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <a href="{{ route('home') }}" class="logo">
                        <img src="{{ asset('images/logo.png') }}" alt="OCaLi" style="height: 50px;">
                        <span class="logo-text">OCaLi</span>
                    </a>
                    <p>{{ __('messages.footer_description') }}</p>
                </div>

                <div>
                    <h4 class="footer-title">{{ __('messages.quick_links') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('explore') }}">{{ __('messages.explore') }}</a></li>
                        <li><a href="{{ route('pricing') }}">{{ __('messages.pricing') }}</a></li>
                        <li><a href="{{ route('chronicles.index') }}">{{ __('messages.chronicles') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">{{ __('messages.for_authors') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('register') }}?role=author">{{ __('messages.become_author') }}</a></li>
                        <li><a href="{{ route('publishing-guide') }}">{{ __('messages.publishing_guide') }}</a></li>
                        <li><a href="{{ route('royalties') }}">{{ __('messages.royalties') }}</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">{{ __('messages.support') }}</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('help') }}">{{ __('messages.help_center') }}</a></li>
                        <li><a href="{{ route('contact') }}">{{ __('messages.contact_us') }}</a></li>
                        <li><a href="{{ route('terms') }}">{{ __('messages.terms') }}</a></li>
                        <li><a href="{{ route('privacy') }}">{{ __('messages.privacy') }}</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; 2023 OCaLi by BLAKTEC</p>
                <p style="margin-top: 0.25rem; font-size: 0.8rem; opacity: 0.7;">{{ __('messages.developed_by') }} Etienne Ndemaze — (+237) 659461197/677478829</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle handling
        document.addEventListener('DOMContentLoaded', function() {
            const html = document.documentElement;
            const theme = localStorage.getItem('theme') || '{{ session("theme", "light") }}';

            if (theme === 'dark') {
                html.classList.add('dark');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-slide-up');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMenuBtn = document.getElementById('closeMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');

            function toggleMenu() {
                mobileMenu.classList.toggle('active');
                mobileMenuOverlay.classList.toggle('active');
                document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleMenu);
            }

            if (closeMenuBtn) {
                closeMenuBtn.addEventListener('click', toggleMenu);
            }

            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', toggleMenu);
            }

            // Close menu when clicking a link
            const mobileLinks = document.querySelectorAll('.mobile-nav-link');
            mobileLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (mobileMenu.classList.contains('active')) {
                        toggleMenu();
                    }
                });
            });
        });

        // Page Loader Logic
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            const progressBar = document.getElementById('loaderProgressBar');
            
            if (progressBar) progressBar.style.width = '100%';
            
            setTimeout(() => {
                if (loader) loader.classList.add('hidden');
                setTimeout(() => {
                    if (progressBar) progressBar.style.width = '0';
                }, 500);
            }, 300);
        });

        // Show loader on page transition
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (!href || href.startsWith('#') || href.startsWith('javascript:') || this.target === '_blank' || e.ctrlKey || e.metaKey) {
                    return;
                }
                
                // Only for internal links (same origin)
                try {
                    const url = new URL(href, window.location.origin);
                    if (url.origin === window.location.origin) {
                        const loader = document.getElementById('page-loader');
                        const progressBar = document.getElementById('loaderProgressBar');
                        
                        if (loader) {
                            loader.classList.remove('hidden');
                            if (progressBar) {
                                progressBar.style.width = '0';
                                setTimeout(() => progressBar.style.width = '70%', 10);
                            }
                        }
                    }
                } catch (err) {
                    // Ignore invalid URLs
                }
            });
        });

        // Also show on form submissions (e.g., search, login)
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                const loader = document.getElementById('page-loader');
                if (loader) loader.classList.remove('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
