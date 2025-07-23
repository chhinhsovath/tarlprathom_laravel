<x-guest-layout>
    <div class="min-h-screen bg-gray-50 flex flex-col">
        <!-- Header with navigation -->
        <div class="bg-white shadow-sm flex-shrink-0">
            <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <a href="{{ route('resources.public') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Resources
                    </a>
                    <h1 class="text-xl font-semibold text-gray-900">TaRL Project</h1>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <div class="flex-1 bg-white shadow-sm">
                <!-- Resource Header -->
                <div class="p-4 sm:p-6 lg:px-8 border-b border-gray-200 flex-shrink-0">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex-1">
                            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $resource->title }}</h2>
                            @if($resource->description)
                                <p class="mt-1 text-sm sm:text-base text-gray-600">{{ $resource->description }}</p>
                            @endif
                        </div>
                        @if($resource->is_youtube)
                            <a href="{{ $resource->youtube_url }}" target="_blank"
                                class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-150 shadow-sm text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                </svg>
                                Watch on YouTube
                            </a>
                        @else
                            <a href="{{ route('resources.download', $resource) }}" 
                                class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-150 shadow-sm text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                </svg>
                                Download
                            </a>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row h-full lg:h-[calc(100vh-10rem)]">
                    <!-- File Preview Section -->
                    <div class="flex-1 p-6 lg:p-8 lg:border-r border-gray-200 overflow-y-auto">
                        <div class="bg-gray-50 rounded-lg p-4 lg:p-8 h-full flex flex-col">
                            <div class="text-center mb-4">
                                <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-full shadow-sm mb-3">
                                    <i class="fas {{ $resource->file_type_icon }} text-2xl text-gray-600"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $resource->file_name }}</h3>
                                <p class="text-sm text-gray-500">{{ strtoupper($resource->file_type) }} • {{ $resource->file_size_formatted }}</p>
                            </div>

                            <div class="flex-1 flex items-center justify-center">
                                @if($resource->is_youtube && $resource->youtube_embed_url)
                                    <div class="w-full max-w-4xl mx-auto">
                                        <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                                            <iframe id="youtube-player"
                                                src="{{ $resource->youtube_embed_url }}?enablejsapi=1"
                                                class="absolute top-0 left-0 w-full h-full rounded-lg shadow-sm"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                            </iframe>
                                        </div>
                                    </div>
                                @elseif($resource->file_type === 'video')
                                    <video controls class="w-full h-full max-h-[70vh] rounded-lg shadow-sm">
                                        <source src="{{ Storage::url($resource->file_path) }}" type="{{ $resource->mime_type }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @elseif($resource->file_type === 'pdf')
                                    <embed src="{{ Storage::url($resource->file_path) }}" type="application/pdf" class="w-full h-full min-h-[60vh] rounded-lg shadow-sm" />
                                @else
                                    <div class="text-sm text-gray-600">
                                        <p>Preview not available. Click download to access the file.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- File Information Sidebar -->
                    <div class="w-full lg:w-80 xl:w-96 p-6 lg:p-8 bg-gray-50 flex-shrink-0">
                        <h3 class="text-sm font-semibold text-gray-900 uppercase tracking-wider mb-4">File Information</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">File Type</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst($resource->file_type) }}
                                    </span>
                                </dd>
                            </div>
                            
                            <div>
                                <dt class="text-sm font-medium text-gray-500">File Size</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->file_size_formatted }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Uploaded By</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->uploader->name }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Upload Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $resource->created_at->format('M d, Y') }}</dd>
                            </div>

                            <div class="pt-4 border-t border-gray-200">
                                <dt class="text-sm font-medium text-gray-500 mb-2">Share this link</dt>
                                <dd>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" readonly 
                                            value="{{ url()->current() }}"
                                            class="flex-1 text-xs px-3 py-2 border border-gray-300 rounded-md bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                                            onclick="this.select()">
                                        <button onclick="copyToClipboard('{{ url()->current() }}')"
                                            class="p-2 text-gray-400 hover:text-gray-600 transition-colors duration-150">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </dd>
                            </div>

                            <div class="pt-4">
                                @if($resource->is_youtube)
                                    <a href="{{ $resource->youtube_url }}" target="_blank"
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium text-sm rounded-md transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                        </svg>
                                        Watch on YouTube
                                    </a>
                                @else
                                    <a href="{{ route('resources.download', $resource) }}" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white font-medium text-sm rounded-md transition-colors duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                        Download File
                                    </a>
                                @endif
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Create a temporary tooltip
                const button = event.target.closest('button');
                const tooltip = document.createElement('div');
                tooltip.className = 'absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs rounded px-2 py-1';
                tooltip.textContent = 'Copied!';
                button.parentElement.style.position = 'relative';
                button.parentElement.appendChild(tooltip);
                
                setTimeout(() => {
                    tooltip.remove();
                }, 2000);
            });
        }

        @if($resource->is_youtube && $resource->youtube_embed_url)
        // YouTube Video Tracking
        var player;
        var watchStartTime = null;
        var totalWatchTime = 0;
        var isPlaying = false;

        // Load YouTube IFrame API
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

        function onYouTubeIframeAPIReady() {
            player = new YT.Player('youtube-player', {
                events: {
                    'onReady': onPlayerReady,
                    'onStateChange': onPlayerStateChange
                }
            });
        }

        function onPlayerReady(event) {
            console.log('YouTube player ready');
        }

        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.PLAYING) {
                if (!isPlaying) {
                    watchStartTime = Date.now();
                    isPlaying = true;
                }
            } else if (event.data == YT.PlayerState.PAUSED || event.data == YT.PlayerState.ENDED) {
                if (isPlaying && watchStartTime) {
                    totalWatchTime += (Date.now() - watchStartTime) / 1000; // Convert to seconds
                    isPlaying = false;
                }
                
                if (event.data == YT.PlayerState.ENDED) {
                    trackVideoView();
                }
            }
        }

        function trackVideoView() {
            var duration = player.getDuration();
            var completionPercentage = (totalWatchTime / duration) * 100;
            
            fetch('{{ route("api.resources.track-view", $resource) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    watch_duration: Math.round(totalWatchTime),
                    total_duration: Math.round(duration),
                    completion_percentage: Math.min(completionPercentage, 100).toFixed(2)
                })
            });
        }

        // Track when user leaves the page
        window.addEventListener('beforeunload', function() {
            if (isPlaying && watchStartTime) {
                totalWatchTime += (Date.now() - watchStartTime) / 1000;
            }
            if (totalWatchTime > 0) {
                trackVideoView();
            }
        });
        @endif
    </script>
</x-guest-layout>