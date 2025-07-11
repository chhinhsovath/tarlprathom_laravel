<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Profile Photo -->
        <div>
            <x-input-label for="profile_photo" :value="__('Profile Photo')" />
            
            @if($user->profile_photo)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">{{ __('Current Photo:') }}</p>
                    <div class="h-32 w-32 rounded-full overflow-hidden">
                        <img src="{{ Storage::url($user->profile_photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover" style="height: 128px; width: 128px; object-fit: cover;">
                    </div>
                </div>
            @else
                <div class="mb-4">
                    <div class="h-32 w-32 rounded-full bg-gray-200 flex items-center justify-center">
                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
            @endif
            
            <input type="file" 
                   id="profile_photo" 
                   name="profile_photo" 
                   accept="image/*"
                   class="mt-1 block w-full text-sm text-gray-500
                   file:mr-4 file:py-2 file:px-4
                   file:rounded-md file:border-0
                   file:text-sm file:font-semibold
                   file:bg-indigo-50 file:text-indigo-700
                   hover:file:bg-indigo-100"
                   onchange="previewPhoto(this)">
            <x-input-error class="mt-2" :messages="$errors->get('profile_photo')" />
            <p class="mt-1 text-sm text-gray-500">{{ __('Upload a profile photo (max 5MB)') }}</p>
            
            <!-- Photo Preview -->
            <div id="photoPreview" class="mt-4 hidden">
                <p class="text-sm text-gray-600 mb-2">{{ __('New Photo:') }}</p>
                <div class="h-32 w-32 rounded-full overflow-hidden">
                    <img id="previewImage" class="h-full w-full object-cover" alt="Photo preview" style="height: 128px; width: 128px; object-fit: cover;">
                </div>
            </div>
        </div>

        <!-- Gender -->
        <div>
            <x-input-label for="sex" :value="__('Gender')" />
            <select id="sex" name="sex" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">{{ __('Select Gender') }}</option>
                <option value="male" {{ old('sex', $user->sex) == 'male' ? 'selected' : '' }}>{{ __('Male') }}</option>
                <option value="female" {{ old('sex', $user->sex) == 'female' ? 'selected' : '' }}>{{ __('Female') }}</option>
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('sex')" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- Telephone -->
        <div>
            <x-input-label for="telephone" :value="__('Telephone')" />
            <x-text-input id="telephone" name="telephone" type="text" class="mt-1 block w-full" :value="old('telephone', $user->telephone)" />
            <x-input-error class="mt-2" :messages="$errors->get('telephone')" />
        </div>

        <!-- School (Read-only for non-admins) -->
        <div>
            <x-input-label for="school" :value="__('School')" />
            @if(auth()->user()->isAdmin())
                <select id="school_id" name="school_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">{{ __('Select School') }}</option>
                    @foreach(\App\Models\School::orderBy('school_name')->get() as $school)
                        <option value="{{ $school->id }}" {{ old('school_id', $user->school_id) == $school->id ? 'selected' : '' }}>
                            {{ $school->school_name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('school_id')" />
            @else
                <p class="mt-1 text-sm text-gray-600">{{ $user->school ? $user->school->school_name : __('Not assigned') }}</p>
            @endif
        </div>

        <!-- Holding Classes -->
        <div>
            <x-input-label for="holding_classes" :value="__('Holding Classes')" />
            <x-text-input id="holding_classes" name="holding_classes" type="text" class="mt-1 block w-full" :value="old('holding_classes', $user->holding_classes)" placeholder="{{ __('e.g., Grade 1, Grade 2') }}" />
            <x-input-error class="mt-2" :messages="$errors->get('holding_classes')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script>
    function previewPhoto(input) {
        const file = input.files[0];
        const preview = document.getElementById('photoPreview');
        const previewImage = document.getElementById('previewImage');
        
        if (file) {
            // Check file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('{{ __("File size must be less than 5MB") }}');
                input.value = '';
                preview.classList.add('hidden');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endpush
