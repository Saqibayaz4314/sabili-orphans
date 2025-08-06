<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600" style="color:white">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" style="color:white;text-align:end"/>

            <x-text-input id="password" class="block mt-1 w-full" style="color:white;text-align:end"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" style="color:rgba(1, 143, 145, 1);background-color:white" class="inline-flex ms-3 items-center px-4 py-2  border border-transparent rounded-md font-semibold text-sm  uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Confirm') }}
            </button>
           
        </div>
    </form>
</x-guest-layout>
