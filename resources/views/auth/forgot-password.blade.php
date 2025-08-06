<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600" style="color: white">
        {{ __('نسيت كلمة المرور؟ لا توجد مشكلة. ما عليك سوى إخبارنا بعنوان بريدك الإلكتروني وسنرسل لك عبره رابط إعادة تعيين كلمة المرور الذي سيسمح لك باختيار كلمة مرور جديدة.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" style="color: white"/>

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" style="color:white;text-align:end"/>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus style="text-align:end"/>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" style="color:rgba(1, 143, 145, 1);background-color:white" class="inline-flex ms-3 items-center px-4 py-2  border border-transparent rounded-md font-semibold text-sm  uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                 رابط إعادة تعيين كلمة مرور عبر البريد الإلكتروني
            </button>
        </div>
    </form>
</x-guest-layout>
