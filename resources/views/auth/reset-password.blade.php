<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الالكتروني')" style="color:white;text-align:end"/>
            <x-text-input id="email" class="block mt-1 w-full" style="text-align:end" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" style="color:white;text-align:end"/>
            <x-text-input id="password" class="block mt-1 w-full" style="text-align:end" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('تأكيد كلمة المرور')" style="color:white;text-align:end"/>

            <x-text-input id="password_confirmation" class="block mt-1 w-full" style="text-align:end"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" style="color:rgba(1, 143, 145, 1);background-color:white" class="inline-flex ms-3 items-center px-4 py-2  border border-transparent rounded-md font-semibold text-sm  uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                استعادة كلمة المرور
            </button>
            
        </div>
    </form>
</x-guest-layout>
