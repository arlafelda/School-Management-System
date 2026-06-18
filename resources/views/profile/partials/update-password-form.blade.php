<section>
    <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/60 border border-gray-100 overflow-hidden">
        
        <!-- Gradient Accent -->
        <div class="h-2 bg-gradient-to-r from-amber-500 to-orange-500"></div>
        
        <!-- Card Header -->
        <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">🔒</div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Update Password') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
            <form method="post" action="{{ route('password.update') }}" class="max-w-2xl space-y-6">
                @csrf
                @method('put')

                <div>
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                    <x-text-input 
                        id="update_password_current_password" 
                        name="current_password" 
                        type="password" 
                        class="mt-1 block w-full" 
                        autocomplete="current-password" 
                        required
                    />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password" :value="__('New Password')" />
                    <x-text-input 
                        id="update_password_password" 
                        name="password" 
                        type="password" 
                        class="mt-1 block w-full" 
                        autocomplete="new-password" 
                        required
                    />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input 
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        type="password" 
                        class="mt-1 block w-full" 
                        autocomplete="new-password" 
                        required
                    />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between pt-4">
                    <x-primary-button class="px-8 py-3">
                        {{ __('Save Password') }}
                    </x-primary-button>

                    @if (session('status') === 'password-updated')
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