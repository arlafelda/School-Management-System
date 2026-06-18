<section>
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden">
        
        <!-- Gradient Accent -->
        <div class="h-2 bg-gradient-to-r from-indigo-500 via-blue-500 to-purple-500"></div>
        
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">👤</div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Profile Information') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __("Update your account's profile information and email address.") }}
                </p>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="max-w-2xl space-y-6">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input 
                        id="name" 
                        name="name" 
                        type="text" 
                        class="mt-1 block w-full" 
                        :value="old('name', $user->name)" 
                        required 
                        autofocus 
                        autocomplete="name" 
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input 
                        id="email" 
                        name="email" 
                        type="email" 
                        class="mt-1 block w-full" 
                        :value="old('email', $user->email)" 
                        required 
                        autocomplete="username" 
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-4 p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                            <p class="text-sm text-amber-700">
                                {{ __('Your email address is unverified.') }}
                                
                                <button form="send-verification" 
                                        class="underline font-medium text-amber-600 hover:text-amber-700 focus:outline-none">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </p>

                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-3 text-sm font-medium text-emerald-600 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7" />
                                    </svg>
                                    {{ __('A new verification link has been sent to your email address.') }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-4">
                    <x-primary-button class="px-8 py-3">
                        {{ __('Save Changes') }}
                    </x-primary-button>

                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2500)"
                            class="text-emerald-600 text-sm font-medium flex items-center gap-2"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7" />
                            </svg>
                            {{ __('Saved.') }}
                        </p>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>