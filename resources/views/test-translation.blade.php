<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold mb-4">Translation Test</h1>
                    
                    <div class="space-y-2">
                        <p><strong>Locale:</strong> {{ app()->getLocale() }}</p>
                        <p><strong>Session Locale:</strong> {{ session('locale') }}</p>
                        <p><strong>Lang Path:</strong> {{ app()->langPath() }}</p>
                    </div>
                    
                    <h2 class="text-xl font-semibold mt-6 mb-4">Translation Tests:</h2>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left">Key</th>
                                <th class="px-4 py-2 text-left">Translation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2">Bulk Import Students</td>
                                <td class="px-4 py-2">{{ __('Bulk Import Students') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">Instructions</td>
                                <td class="px-4 py-2">{{ __('Instructions') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">Download Excel Template</td>
                                <td class="px-4 py-2">{{ __('Download Excel Template') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">{{ __("Name") }}</td>
                                <td class="px-4 py-2">{{ __('Name') }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2">Age</td>
                                <td class="px-4 py-2">{{ __('Age') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>