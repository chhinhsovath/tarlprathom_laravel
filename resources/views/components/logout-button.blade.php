{{-- Enhanced Logout Button Component --}}
<form method="POST" action="{{ route('logout') }}" class="inline" id="logout-form">
    @csrf
    <button type="button" 
            onclick="handleLogout()" 
            class="flex items-center w-full px-3 py-2 text-sm text-gray-700 rounded-md hover:bg-red-50 hover:text-red-600 transition-colors duration-150">
        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
        </svg>
        {{ trans_db('sign_out') }}
    </button>
</form>

<script>
function handleLogout() {
    // Get fresh CSRF token
    fetch('/csrf-token')
        .then(response => response.json())
        .then(data => {
            // Update CSRF token in form
            const csrfInput = document.querySelector('#logout-form input[name="_token"]');
            if (csrfInput) {
                csrfInput.value = data.csrf_token;
            }
            
            // Submit the form
            document.getElementById('logout-form').submit();
        })
        .catch(error => {
            console.log('CSRF refresh failed, submitting with existing token:', error);
            // Fallback: submit with existing token
            document.getElementById('logout-form').submit();
        });
}
</script>