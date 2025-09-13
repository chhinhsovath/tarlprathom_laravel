@props(['data' => [], 'headers' => [], 'actions' => null])

<div class="mobile-responsive-table">
    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach($headers as $header)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                    @if($actions)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ trans_db('Actions') }}
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $slot }}
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="block md:hidden space-y-4">
        @foreach($data as $index => $item)
            <div class="mobile-table-card bg-white rounded-lg border border-gray-200 p-4 shadow-sm">
                @foreach($headers as $key => $header)
                    @if(isset($item[$key]) && $item[$key] !== null && $item[$key] !== '')
                        <div class="mobile-table-row flex justify-between items-start py-2 border-b border-gray-100 last:border-b-0">
                            <span class="mobile-table-label text-sm font-semibold text-gray-700 flex-shrink-0 w-1/3">
                                {{ $header }}:
                            </span>
                            <span class="mobile-table-value text-sm text-gray-900 flex-1 text-right">
                                {!! is_array($item[$key]) ? implode(', ', $item[$key]) : $item[$key] !!}
                            </span>
                        </div>
                    @endif
                @endforeach
                
                @if($actions)
                    <div class="mobile-table-actions mt-4 pt-3 border-t border-gray-200 flex justify-end space-x-2">
                        {!! str_replace('{{$item}}', json_encode($item), $actions) !!}
                    </div>
                @endif
            </div>
        @endforeach

        @if(empty($data))
            <div class="mobile-table-card bg-white rounded-lg border border-gray-200 p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="mt-2 text-sm text-gray-500">{{ trans_db('No data available') }}</p>
            </div>
        @endif
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .mobile-table-card {
            transition: all 0.2s ease;
        }
        
        .mobile-table-card:active {
            transform: scale(0.98);
            background-color: #f9fafb;
        }
        
        .mobile-table-row {
            min-height: 32px;
            align-items: flex-start;
        }
        
        .mobile-table-label {
            line-height: 1.4;
            padding-right: 12px;
        }
        
        .mobile-table-value {
            line-height: 1.4;
            word-break: break-word;
        }
        
        .mobile-table-actions {
            flex-wrap: wrap;
            gap: 8px;
        }
        
        .mobile-table-actions > * {
            flex: 0 0 auto;
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 6px;
        }
    }
</style>