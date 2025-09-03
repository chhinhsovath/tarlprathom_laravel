<x-app-layout>
    <div class="py-6">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">ទម្រង់សង្កេតការណ៍ការចុះណែនាំ</h3>
                    
                    <x-mentoring-form 
                        :schools="$schools" 
                        :teachers="$teachers"
                        :action="route('mentoring.store')"
                    />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>