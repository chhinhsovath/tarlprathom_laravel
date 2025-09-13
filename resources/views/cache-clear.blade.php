<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cache Clear - TaRL System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-indigo-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Cache Clear Utility</h2>
                <p class="text-sm text-gray-600 mb-6">Click the button below to clear all application caches</p>
            </div>
            
            <div id="results" class="hidden mb-6 p-4 rounded-lg"></div>
            
            <button id="clearCacheBtn" onclick="clearAllCaches()" 
                class="w-full bg-indigo-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center justify-center">
                <span id="btnText">Clear All Caches</span>
                <div id="loader" class="loader hidden ml-3"></div>
            </button>
            
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                    ← Back to Application
                </a>
            </div>
            
            <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold">Security Notice</p>
                        <p>This page is protected. Only use when needed for deployment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function clearAllCaches() {
            const btn = document.getElementById('clearCacheBtn');
            const btnText = document.getElementById('btnText');
            const loader = document.getElementById('loader');
            const results = document.getElementById('results');
            
            // Disable button and show loader
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.textContent = 'Clearing caches...';
            loader.classList.remove('hidden');
            results.classList.add('hidden');
            
            // Get token from URL
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            
            // Send request to clear caches
            fetch('{{ route("cache.clear") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token: token })
            })
            .then(response => response.json())
            .then(data => {
                // Hide loader
                loader.classList.add('hidden');
                
                if (data.status === 'success') {
                    // Show success
                    btnText.textContent = '✅ Caches Cleared Successfully!';
                    btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    btn.classList.add('bg-green-600');
                    
                    // Show results
                    results.classList.remove('hidden');
                    results.classList.add('bg-green-50', 'border', 'border-green-200');
                    
                    let resultHtml = '<h3 class="font-semibold text-green-800 mb-2">Results:</h3><ul class="text-sm text-green-700 space-y-1">';
                    for (const [key, value] of Object.entries(data)) {
                        if (key !== 'status' && key !== 'message') {
                            resultHtml += `<li>${value}</li>`;
                        }
                    }
                    resultHtml += '</ul>';
                    results.innerHTML = resultHtml;
                    
                    // Reset button after 5 seconds
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-green-600');
                        btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                        btnText.textContent = 'Clear All Caches';
                    }, 5000);
                } else {
                    // Show error
                    btnText.textContent = '❌ Error Clearing Caches';
                    btn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                    btn.classList.add('bg-red-600');
                    
                    results.classList.remove('hidden');
                    results.classList.add('bg-red-50', 'border', 'border-red-200');
                    results.innerHTML = `<p class="text-red-700">${data.message || 'An error occurred'}</p>`;
                    
                    // Reset button after 3 seconds
                    setTimeout(() => {
                        btn.disabled = false;
                        btn.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-red-600');
                        btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                        btnText.textContent = 'Clear All Caches';
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                loader.classList.add('hidden');
                btnText.textContent = '❌ Network Error';
                btn.classList.remove('bg-indigo-600');
                btn.classList.add('bg-red-600');
                
                setTimeout(() => {
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-not-allowed', 'bg-red-600');
                    btn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                    btnText.textContent = 'Clear All Caches';
                }, 3000);
            });
        }
    </script>
</body>
</html>