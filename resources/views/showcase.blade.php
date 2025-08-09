<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('showcase.title') }} - TaRL Project</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hanuman:wght@100;300;400;700;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold text-gray-800">TaRL Project</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ url('/language/en') }}" class="px-3 py-1 rounded {{ app()->getLocale() == 'en' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">English</a>
                    <a href="{{ url('/language/km') }}" class="px-3 py-1 rounded {{ app()->getLocale() == 'km' ? 'bg-blue-100 text-blue-700' : 'text-gray-600' }}">ខ្មែរ</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-4">{{ __('showcase.header') }}</h1>
            <p class="text-xl text-blue-100 max-w-3xl mx-auto">{{ __('showcase.description') }}</p>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">{{ __('showcase.main_features') }}</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $category => $data)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4">
                        <div class="flex items-center">
                            <i class="fas fa-{{ $data['icon'] }} text-3xl text-white mr-3"></i>
                            <h3 class="text-xl font-semibold text-white">{{ __('showcase.features.' . $category) }}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-3">
                            @foreach($data['features'] as $feature)
                            <li class="flex items-start">
                                <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-gray-700">{{ __('showcase.features.' . $category . '.' . $feature) }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">{{ __('showcase.statistics.title') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-blue-600 mb-2">1000+</div>
                    <div class="text-gray-600">{{ __('showcase.statistics.schools') }}</div>
                </div>
                <div class="bg-white rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-green-600 mb-2">50,000+</div>
                    <div class="text-gray-600">{{ __('showcase.statistics.students') }}</div>
                </div>
                <div class="bg-white rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-purple-600 mb-2">2000+</div>
                    <div class="text-gray-600">{{ __('showcase.statistics.teachers') }}</div>
                </div>
                <div class="bg-white rounded-lg p-6 text-center">
                    <div class="text-4xl font-bold text-orange-600 mb-2">100+</div>
                    <div class="text-gray-600">{{ __('showcase.statistics.mentors') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Features -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">{{ __('showcase.additional_features.title') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-language text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('showcase.additional_features.multilingual') }}</h3>
                    <p class="text-gray-600">{{ __('showcase.additional_features.multilingual_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-3xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('showcase.additional_features.responsive') }}</h3>
                    <p class="text-gray-600">{{ __('showcase.additional_features.responsive_desc') }}</p>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-3xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">{{ __('showcase.additional_features.secure') }}</h3>
                    <p class="text-gray-600">{{ __('showcase.additional_features.secure_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-4">{{ __('showcase.cta.title') }}</h2>
            <p class="text-xl text-blue-100 mb-8">{{ __('showcase.cta.description') }}</p>
            <div class="space-x-4">
                <a href="/login" class="inline-block bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-colors">
                    {{ __('showcase.cta.login') }}
                </a>
                <a href="/register" class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                    {{ __('showcase.cta.register') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p>&copy; 2024 TaRL Project. {{ __('showcase.footer.rights') }}</p>
            <p class="mt-2 text-gray-400">{{ __('showcase.footer.powered_by') }}</p>
        </div>
    </footer>
</body>
</html>