<div class="relative inline-block text-left">
    <div>
        <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="language-menu-button" aria-expanded="false" aria-haspopup="true" onclick="toggleLanguageMenu()">
            @if(app()->getLocale() == 'km')
                <img src="https://flagcdn.com/w20/kh.png" alt="Khmer" class="w-5 h-3 mr-2">
                ភាសាខ្មែរ
            @else
                <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-3 mr-2">
                English
            @endif
            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <div class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50" id="language-menu" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">
        <div class="py-1" role="none">
            <a href="{{ url('/language/en') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ app()->getLocale() == 'en' ? 'bg-gray-100' : '' }}" role="menuitem" tabindex="-1">
                <img src="https://flagcdn.com/w20/gb.png" alt="English" class="w-5 h-3 mr-3">
                English
                @if(app()->getLocale() == 'en')
                    <svg class="ml-auto h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
            <a href="{{ url('/language/km') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 {{ app()->getLocale() == 'km' ? 'bg-gray-100' : '' }}" role="menuitem" tabindex="-1">
                <img src="https://flagcdn.com/w20/kh.png" alt="Khmer" class="w-5 h-3 mr-3">
                ភាសាខ្មែរ
                @if(app()->getLocale() == 'km')
                    <svg class="ml-auto h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                @endif
            </a>
        </div>
    </div>
</div>

<script>
    function toggleLanguageMenu() {
        const menu = document.getElementById('language-menu');
        const button = document.getElementById('language-menu-button');
        const isHidden = menu.classList.contains('hidden');
        
        if (isHidden) {
            menu.classList.remove('hidden');
            button.setAttribute('aria-expanded', 'true');
        } else {
            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }
    }

    // Close the dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('language-menu');
        const button = document.getElementById('language-menu-button');
        
        if (!button.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
            button.setAttribute('aria-expanded', 'false');
        }
    });
</script>