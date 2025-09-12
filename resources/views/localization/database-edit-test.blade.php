@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-6">Translation Manager (Test)</h2>

        <!-- Simple table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Key</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Khmer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">English</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Group</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($translations as $translation)
                        <tr>
                            <td class="px-6 py-4">
                                {!! htmlspecialchars((string) $translation->key, ENT_QUOTES, 'UTF-8') !!}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $kmValue = $translation->km;
                                    if (is_array($kmValue)) {
                                        $kmValue = implode(', ', $kmValue);
                                    } elseif (!is_string($kmValue)) {
                                        $kmValue = (string) $kmValue;
                                    }
                                @endphp
                                {!! htmlspecialchars($kmValue ?: '-', ENT_QUOTES, 'UTF-8') !!}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $enValue = $translation->en;
                                    if (is_array($enValue)) {
                                        $enValue = implode(', ', $enValue);
                                    } elseif (!is_string($enValue)) {
                                        $enValue = (string) $enValue;
                                    }
                                @endphp
                                {!! htmlspecialchars($enValue ?: '-', ENT_QUOTES, 'UTF-8') !!}
                            </td>
                            <td class="px-6 py-4">
                                {!! htmlspecialchars((string) $translation->group, ENT_QUOTES, 'UTF-8') !!}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                No translations found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            
            @if(method_exists($translations, 'hasPages') && $translations->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $translations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection