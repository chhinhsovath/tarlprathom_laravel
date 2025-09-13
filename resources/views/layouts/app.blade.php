<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#4f46e5">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="default">
        <meta name="apple-mobile-web-app-title" content="TaRL System">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="msapplication-TileColor" content="#4f46e5">
        <meta name="msapplication-tap-highlight" content="no">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="/manifest.json">
        
        <!-- App Icons -->
        <link rel="apple-touch-icon" sizes="180x180" href="/images/icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/images/icon-192x192.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/images/icon-192x192.png">

        <title>{{ config('app.name', 'TaRL Project') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <!-- Scripts -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            .loading-spinner {
                display: inline-block;
                width: 20px;
                height: 20px;
                border: 3px solid rgba(0, 0, 0, .1);
                border-radius: 50%;
                border-top-color: #3B82F6;
                animation: spin 1s ease-in-out infinite;
            }
            
            @keyframes spin {
                to { transform: rotate(360deg); }
            }
            
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9999;
            }
            
            .loading-overlay .loading-content {
                background: white;
                padding: 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 15px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
            
            .loading-overlay .loading-spinner {
                width: 30px;
                height: 30px;
            }
            
            .btn-loading {
                position: relative;
                color: transparent !important;
            }
            
            .btn-loading .loading-spinner {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                border-color: rgba(255, 255, 255, .3);
                border-top-color: white;
            }
            
            .loading {
                position: relative;
                pointer-events: none;
                opacity: 0.7;
            }
            
            .loading::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 20px;
                height: 20px;
                border: 3px solid rgba(0, 0, 0, .1);
                border-radius: 50%;
                border-top-color: #3B82F6;
                animation: spin 1s ease-in-out infinite;
            }
            
            /* Page transition overlay */
            .page-transition-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(255, 255, 255, 0.95);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 9998;
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
            }
            
            .page-transition-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            .page-transition-content {
                text-align: center;
            }
            
            .page-transition-spinner {
                width: 50px;
                height: 50px;
                border: 4px solid rgba(59, 130, 246, 0.2);
                border-top-color: #3B82F6;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 0 auto 20px;
            }
            
            .page-transition-text {
                font-size: 16px;
                color: #374151;
                font-weight: 500;
            }
            
            /* Fade animation for page content */
            body.page-transitioning {
                overflow: hidden;
            }
            
            body.page-transitioning main {
                opacity: 0.3;
                transition: opacity 0.3s ease-in-out;
            }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <x-toast-notifications />
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="w-full py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                @yield('content', $slot ?? '')
            </main>
        </div>
        
        <!-- Global Loading Overlay -->
        <div id="globalLoadingOverlay" class="loading-overlay" style="display: none;">
            <div class="loading-content">
                <div class="loading-spinner"></div>
                <span id="loadingText">{{ __('Loading...') }}</span>
            </div>
        </div>
        
        <!-- Page Transition Overlay -->
        <div id="pageTransitionOverlay" class="page-transition-overlay">
            <div class="page-transition-content">
                <div class="page-transition-spinner"></div>
                <div class="page-transition-text">{{ __('Loading page...') }}</div>
            </div>
        </div>
        
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            // COMPLETELY DISABLE ALL ALERTS
            window.alert = function() { console.log('Alert blocked:', arguments); };
            window.confirm = function() { console.log('Confirm blocked:', arguments); return true; };
            window.prompt = function() { console.log('Prompt blocked:', arguments); return null; };
        </script>
        <script>
            // Global loading functions
            window.showLoading = function(text) {
                const overlay = document.getElementById('globalLoadingOverlay');
                const loadingText = document.getElementById('loadingText');
                if (overlay && loadingText) {
                    if (text) {
                        loadingText.textContent = text;
                    } else {
                        loadingText.textContent = '{{ __("Loading...") }}';
                    }
                    overlay.style.display = 'flex';
                } else {
                    console.log('Loading:', text || 'Loading...');
                }
            };
            
            window.hideLoading = function() {
                const overlay = document.getElementById('globalLoadingOverlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            };
            
            // Button loading helper
            window.setButtonLoading = function(button, loading = true) {
                const $button = $(button);
                if (loading) {
                    $button.prop('disabled', true).addClass('btn-loading');
                    if (!$button.find('.loading-spinner').length) {
                        $button.append('<div class="loading-spinner"></div>');
                    }
                } else {
                    $button.prop('disabled', false).removeClass('btn-loading');
                    $button.find('.loading-spinner').remove();
                }
            };
            
            // Global AJAX setup
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                
                // Global error handler
                $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
                    hideLoading();
                    if (jqXHR.status === 419) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __("Session Expired") }}',
                            text: '{{ __("Please refresh the page and try again.") }}',
                            confirmButtonText: '{{ __("Refresh") }}'
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                });
                
                // Handle export button clicks (high priority to prevent conflicts)
                $(document).on('click.export', '.export-btn', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Prevent page transition from triggering
                    
                    const $btn = $(this);
                    const url = $btn.attr('href') || $btn.data('url');
                    const buttonText = $btn.html();
                    
                    // Show loading state
                    setButtonLoading($btn, true);
                    showLoading('{{ __("Preparing export...") }}');
                    
                    // Create a temporary form for file download
                    const form = $('<form>', {
                        method: 'GET',
                        action: url,
                        class: 'no-transition' // Prevent page transition on this form
                    });
                    
                    // Trigger download
                    $('body').append(form);
                    form.submit();
                    form.remove();
                    
                    // Hide loading after a delay
                    setTimeout(() => {
                        hideLoading();
                        setButtonLoading($btn, false);
                        
                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __("Export Started") }}',
                            text: '{{ __("Your file is being downloaded.") }}',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    }, 2000);
                    
                    return false; // Extra safety to prevent any other handlers
                });
                
                // Page transition handler
                let pageTransitionTimeout;
                
                function showPageTransition() {
                    $('body').addClass('page-transitioning');
                    $('#pageTransitionOverlay').addClass('active');
                    
                    // Clear any existing timeout
                    if (pageTransitionTimeout) {
                        clearTimeout(pageTransitionTimeout);
                    }
                    
                    // Set a timeout to hide the overlay after 10 seconds
                    pageTransitionTimeout = setTimeout(() => {
                        console.warn('Page transition timeout - hiding overlay');
                        hidePageTransition();
                    }, 10000);
                }
                
                function hidePageTransition() {
                    $('body').removeClass('page-transitioning');
                    $('#pageTransitionOverlay').removeClass('active');
                    
                    // Clear the timeout if it exists
                    if (pageTransitionTimeout) {
                        clearTimeout(pageTransitionTimeout);
                        pageTransitionTimeout = null;
                    }
                }
                
                // Handle all link clicks for page transitions
                $(document).on('click', 'a[href]:not([href^="#"]):not([href^="javascript:"]):not([target="_blank"]):not(.export-btn):not([download])', function(e) {
                    // Double-check it's not an export button
                    if ($(this).hasClass('export-btn')) {
                        return;
                    }
                    
                    const href = $(this).attr('href');
                    
                    // Skip if it's an external link
                    if (href.startsWith('http') && !href.includes(window.location.host)) {
                        return;
                    }
                    
                    // Skip if it's a logout link (handled by form submission)
                    if (href.includes('logout')) {
                        return;
                    }
                    
                    // Skip if preventDefault was already called
                    if (e.isDefaultPrevented()) {
                        return;
                    }
                    
                    // Show page transition
                    e.preventDefault();
                    showPageTransition();
                    
                    // Navigate after a short delay for animation
                    setTimeout(() => {
                        window.location.href = href;
                    }, 300);
                });
                
                // Handle form submissions
                $(document).on('submit', 'form:not(.no-transition)', function(e) {
                    // Skip AJAX forms
                    if ($(this).data('ajax') || $(this).hasClass('ajax-form')) {
                        return;
                    }
                    
                    // Show transition for regular form submissions
                    showPageTransition();
                });
                
                // Handle browser back/forward buttons
                window.addEventListener('pageshow', function(event) {
                    if (event.persisted) {
                        hidePageTransition();
                    }
                });
                
                // Hide transition when page is fully loaded
                $(window).on('load', function() {
                    hidePageTransition();
                });
                
                // Also hide on document ready as fallback
                $(document).ready(function() {
                    setTimeout(() => {
                        hidePageTransition();
                    }, 100);
                });
                
                // Handle page visibility changes (e.g., when user switches tabs and comes back)
                document.addEventListener('visibilitychange', function() {
                    if (!document.hidden) {
                        // Page is visible again, hide any stuck loading overlays
                        hidePageTransition();
                        hideLoading();
                    }
                });
                
                // Additional fallback: hide loading on focus
                $(window).on('focus', function() {
                    hidePageTransition();
                    hideLoading();
                });
                
                // Handle navigation errors (when user uses back button after error)
                window.addEventListener('pageshow', function(event) {
                    hidePageTransition();
                    hideLoading();
                });
            });
            
            // PWA Installation and Service Worker with Navigation Fix
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    // Check and mark if app is already installed
                    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
                        localStorage.setItem('pwaInstalled', 'true');
                        console.log('App is running in standalone mode - marking as installed');
                    }
                    
                    navigator.serviceWorker.register('/service-worker-dynamic.js')
                        .then(registration => {
                            console.log('✅ ServiceWorker registered: ', registration.scope);
                            
                            // Check for updates
                            registration.addEventListener('updatefound', () => {
                                const newWorker = registration.installing;
                                newWorker.addEventListener('statechange', () => {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        // New content available
                                        Swal.fire({
                                            title: 'Update Available',
                                            text: 'A new version is available. Refresh to get the latest version.',
                                            icon: 'info',
                                            showCancelButton: true,
                                            confirmButtonText: 'Refresh',
                                            cancelButtonText: 'Later'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                newWorker.postMessage({ action: 'skipWaiting' });
                                                window.location.reload();
                                            }
                                        });
                                    }
                                });
                            });
                        })
                        .catch(error => {
                            console.error('❌ ServiceWorker registration failed: ', error);
                            // Disable service worker on errors to prevent navigation issues
                            if ('serviceWorker' in navigator) {
                                navigator.serviceWorker.getRegistrations().then(registrations => {
                                    registrations.forEach(registration => registration.unregister());
                                });
                            }
                        });
                        
                    // Handle service worker updates
                    navigator.serviceWorker.addEventListener('controllerchange', () => {
                        window.location.reload();
                    });
                });
            }
            
            // PWA Install Prompt
            let deferredPrompt;
            
            // Check if PWA is installed
            function isPWAInstalled() {
                // Check multiple conditions to determine if installed
                const isStandalone = window.matchMedia('(display-mode: standalone)').matches;
                const isInWebAppiOS = (window.navigator.standalone === true);
                const isInstalled = localStorage.getItem('pwaInstalled') === 'true';
                
                return isStandalone || isInWebAppiOS || isInstalled;
            }
            
            // Check if install prompt was recently dismissed
            function wasRecentlyDismissed() {
                const dismissedTime = localStorage.getItem('installPromptDismissed');
                if (!dismissedTime) return false;
                
                // Don't show again for 7 days after dismissal
                const daysSinceDismissed = (Date.now() - parseInt(dismissedTime)) / (1000 * 60 * 60 * 24);
                return daysSinceDismissed < 7;
            }
            
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Only show install prompt if not installed and not recently dismissed
                if (!isPWAInstalled() && !wasRecentlyDismissed()) {
                    showInstallPrompt();
                }
            });
            
            // Listen for successful app installation
            window.addEventListener('appinstalled', (e) => {
                console.log('PWA installed successfully');
                localStorage.setItem('pwaInstalled', 'true');
                $('#installBanner').remove();
                deferredPrompt = null;
            });
            
            function showInstallPrompt() {
                // Don't show if already showing
                if ($('#installBanner').length > 0) {
                    return;
                }
                
                const installBanner = $(`
                    <div id="installBanner" class="fixed top-0 left-0 right-0 bg-indigo-600 text-white p-3 z-50 transform -translate-y-full transition-transform duration-300">
                        <div class="flex items-center justify-between max-w-7xl mx-auto">
                            <div class="flex items-center space-x-3">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                <span class="text-sm font-medium">Install TaRL App for better experience</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="installBtn" class="bg-white text-indigo-600 px-4 py-1 rounded text-sm font-medium hover:bg-gray-50 transition-colors">
                                    Install
                                </button>
                                <button id="dismissInstall" class="text-white/80 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                `);
                
                $('body').prepend(installBanner);
                
                setTimeout(() => {
                    installBanner.removeClass('-translate-y-full');
                }, 500);
                
                $('#installBtn').on('click', () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        deferredPrompt.userChoice.then((choiceResult) => {
                            if (choiceResult.outcome === 'accepted') {
                                console.log('User accepted PWA install');
                                // Mark as installed immediately after acceptance
                                localStorage.setItem('pwaInstalled', 'true');
                            } else {
                                console.log('User dismissed PWA install');
                                // Mark as dismissed to prevent showing again soon
                                localStorage.setItem('installPromptDismissed', Date.now());
                            }
                            deferredPrompt = null;
                            installBanner.remove();
                        });
                    }
                });
                
                $('#dismissInstall').on('click', () => {
                    installBanner.addClass('-translate-y-full');
                    setTimeout(() => installBanner.remove(), 300);
                    localStorage.setItem('installPromptDismissed', Date.now());
                });
                
                // Auto-dismiss after 10 seconds
                setTimeout(() => {
                    if ($('#installBanner').length) {
                        $('#dismissInstall').click();
                    }
                }, 10000);
            }
            
            // Connection status monitoring
            window.addEventListener('online', () => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Back Online',
                    text: 'Internet connection restored',
                    showConfirmButton: false,
                    timer: 2000,
                    toast: true
                });
                
                // Trigger background sync for offline data
                if ('serviceWorker' in navigator && 'sync' in window.ServiceWorkerRegistration.prototype) {
                    navigator.serviceWorker.ready.then(registration => {
                        return registration.sync.register('sync-assessments');
                    });
                }
            });
            
            window.addEventListener('offline', () => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Offline Mode',
                    text: 'You are now offline. Data will sync when connection is restored.',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true
                });
            });
            
            // Fix navigation issues that might be caused by service worker or other conflicts
            $(document).ready(function() {
                // Ensure all navigation links work properly
                $('a[href]').not('.no-transition, .export-btn').on('click', function(e) {
                    const href = $(this).attr('href');
                    
                    // Skip external links and anchors
                    if (href.startsWith('http') || href.startsWith('#') || href.startsWith('mailto:') || href.startsWith('tel:')) {
                        return true;
                    }
                    
                    // Skip if link has target="_blank"
                    if ($(this).attr('target') === '_blank') {
                        return true;
                    }
                    
                    // For internal navigation, ensure it works
                    if (href && !href.includes('javascript:')) {
                        console.log('🔗 Navigating to:', href);
                        
                        // Show loading for navigation
                        if (href !== window.location.pathname) {
                            showPageTransition();
                        }
                        
                        // Let the default navigation happen
                        return true;
                    }
                });
                
                // Debug service worker issues
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.addEventListener('message', event => {
                        console.log('📨 Service Worker message:', event.data);
                    });
                    
                    // Monitor service worker state
                    navigator.serviceWorker.ready.then(registration => {
                        console.log('🟢 Service Worker ready:', registration.active?.state);
                    });
                }
                
                // Clear stuck navigation states
                $(window).on('pageshow', function(event) {
                    hidePageTransition();
                    hideLoading();
                    console.log('📄 Page show event:', window.location.pathname);
                });
                
                // Handle browser back/forward navigation
                window.addEventListener('popstate', function(event) {
                    console.log('⬅️ Browser navigation:', window.location.pathname);
                    hidePageTransition();
                    hideLoading();
                });
            });
        </script>
        
        <!-- Mobile-specific styles -->
        <style>
            @media (max-width: 768px) {
                /* Mobile navigation improvements */
                .mobile-nav-bottom {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background: white;
                    border-top: 1px solid #e5e7eb;
                    padding: 8px 0;
                    z-index: 50;
                }
                
                .mobile-nav-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 8px 12px;
                    text-decoration: none;
                    color: #6b7280;
                    font-size: 12px;
                    min-height: 60px;
                    transition: all 0.2s;
                }
                
                .mobile-nav-item.active {
                    color: #4f46e5;
                    background: #f0f4ff;
                }
                
                .mobile-nav-item svg {
                    width: 24px;
                    height: 24px;
                    margin-bottom: 4px;
                }
                
                /* Add bottom padding to content to avoid navigation overlap */
                .page-content {
                    padding-bottom: 80px;
                }
                
                /* Touch-friendly improvements */
                button, .btn, input[type="submit"] {
                    min-height: 44px;
                    min-width: 44px;
                }
                
                /* Table improvements for mobile */
                .mobile-table-card {
                    display: block;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    margin-bottom: 12px;
                    padding: 16px;
                    background: white;
                }
                
                .mobile-table-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;
                }
                
                .mobile-table-row:last-child {
                    margin-bottom: 0;
                }
                
                .mobile-table-label {
                    font-weight: 600;
                    color: #374151;
                    min-width: 80px;
                }
                
                .mobile-table-value {
                    color: #6b7280;
                    text-align: right;
                }
                
                /* Form improvements */
                .mobile-form-group {
                    margin-bottom: 20px;
                }
                
                .mobile-form-group label {
                    display: block;
                    font-weight: 600;
                    margin-bottom: 8px;
                    color: #374151;
                }
                
                .mobile-form-group input,
                .mobile-form-group select,
                .mobile-form-group textarea {
                    width: 100%;
                    padding: 12px;
                    border: 2px solid #e5e7eb;
                    border-radius: 8px;
                    font-size: 16px; /* Prevents zoom on iOS */
                }
                
                .mobile-form-group input:focus,
                .mobile-form-group select:focus,
                .mobile-form-group textarea:focus {
                    outline: none;
                    border-color: #4f46e5;
                    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
                }
                
                /* Reduce font sizes for better mobile experience */
                .mobile-text-sm {
                    font-size: 14px;
                }
                
                .mobile-text-xs {
                    font-size: 12px;
                }
            }
            
            /* PWA display mode specific styles */
            @media all and (display-mode: standalone) {
                /* Remove top padding when in PWA mode (no browser address bar) */
                body {
                    padding-top: env(safe-area-inset-top);
                    padding-bottom: env(safe-area-inset-bottom);
                }
                
                /* Hide install banner when already installed */
                #installBanner {
                    display: none !important;
                }
            }
            
            /* Network status indicator */
            .network-status {
                position: fixed;
                top: 10px;
                right: 10px;
                z-index: 1000;
                padding: 8px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 600;
                transition: all 0.3s;
            }
            
            .network-status.online {
                background: #10b981;
                color: white;
                opacity: 0;
                transform: translateY(-100%);
            }
            
            .network-status.offline {
                background: #ef4444;
                color: white;
                opacity: 1;
                transform: translateY(0);
            }
        </style>
        
        @stack('scripts')
    </body>
</html>
