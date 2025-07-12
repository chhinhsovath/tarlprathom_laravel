<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaRL Project') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

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
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
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
            // Global loading functions
            window.showLoading = function(text) {
                const overlay = document.getElementById('globalLoadingOverlay');
                const loadingText = document.getElementById('loadingText');
                if (text) {
                    loadingText.textContent = text;
                } else {
                    loadingText.textContent = '{{ __("Loading...") }}';
                }
                overlay.style.display = 'flex';
            };
            
            window.hideLoading = function() {
                const overlay = document.getElementById('globalLoadingOverlay');
                overlay.style.display = 'none';
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
                function showPageTransition() {
                    $('body').addClass('page-transitioning');
                    $('#pageTransitionOverlay').addClass('active');
                }
                
                function hidePageTransition() {
                    $('body').removeClass('page-transitioning');
                    $('#pageTransitionOverlay').removeClass('active');
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
            });
        </script>
        @stack('scripts')
    </body>
</html>
