<x-guest-layout>

    {{-- @include('loginpage') --}}
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <br>

    <h4 style="text-align: center">Tenant</h4>

    <form method="POST" action="{{ route('tenant_login_submit') }}">
        @csrf

        @if($errors->any())
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        @endif
        @if(Session::get('error'))
        <li>{{ Session::get('error') }}</li>
        @endif
        @if(Session::get('success'))
        <li>{{ Session::get('success') }}</li>
        @endif

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('tenant_register') }}">
                {{ __('Don\'t have an account? Register') }}
            </a>

            <x-primary-button class="ms-3 btn-btn-primary">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>



<style>
    .btn-btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>

