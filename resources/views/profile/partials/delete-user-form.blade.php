<section class="space-y-6">
    <div class="bg-white rounded-3xl shadow-xl shadow-red-100/50 border border-red-100 overflow-hidden">
        
        <!-- Header dengan Accent -->
        <div class="h-2 bg-gradient-to-r from-red-500 to-rose-600"></div>
        
        <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-3xl flex-shrink-0">🗑️</div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ __('Delete Account') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    Tindakan ini bersifat permanen dan tidak dapat dibatalkan
                </p>
            </div>
        </div>

        <div class="p-8">
            <div class="max-w-lg">
                <p class="text-gray-600 leading-relaxed">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                </p>

                <div class="mt-8">
                    <x-danger-button
                        x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                        class="px-6 py-3 text-sm font-medium flex items-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.595 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.595-1.858L5 7m5-4v6m4-6v6m1-10V9a1 1 0 00-1 1v1M12 4v6m2-3v3" />
                        </svg>
                        {{ __('Delete Account') }}
                    </x-danger-button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <div class="p-8">
            <div class="flex items-center gap-4 mb-6">
                <div class="w-10 h-10 bg-red-100 rounded-2xl flex items-center justify-center text-2xl">⚠️</div>
                <h2 class="text-xl font-semibold text-gray-900">
                    {{ __('Are you sure you want to delete your account?') }}
                </h2>
            </div>

            <p class="text-gray-600 leading-relaxed mb-6">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div>
                    <x-input-label for="password" value="{{ __('Password') }}" />
                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-full"
                        placeholder="Masukkan password Anda"
                        required
                    />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <x-secondary-button 
                        x-on:click="$dispatch('close')"
                        type="button">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button>
                        {{ __('Yes, Delete My Account') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </x-modal>
</section>