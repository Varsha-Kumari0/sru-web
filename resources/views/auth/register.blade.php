<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" class="text-gray-900 font-semibold" />
            <x-text-input id="email" class="block mt-1 w-full border-[#1860b7] focus:border-gray-900 focus:ring-gray-900" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-900 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4 bg-gray-800 hover:bg-gray-900 active:bg-gray-800 focus:ring-gray-800">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>