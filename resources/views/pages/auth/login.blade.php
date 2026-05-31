<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <flux:heading size="xl" class="font-extrabold">{{ __('Log in to Continue') }}</flux:heading>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="Enter your email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
                viewable
            />

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button type="submit" class="w-full bg-[#12A150] hover:bg-[#0e803f] text-white font-bold py-3.5 rounded-xl border-none transition-colors duration-200" data-test="login-button">
                    {{ __('Continue') }}
                </flux:button>
            </div>
        </form>

        <div class="flex items-center justify-between text-sm mt-4">
            @if (Route::has('password.request'))
                <flux:link :href="route('password.request')" class="text-[#12A150] hover:text-[#0e803f] font-semibold" wire:navigate>
                    {{ __('Can\'t Log in?') }}
                </flux:link>
            @endif

            @if (Route::has('register'))
                <flux:link :href="route('register')" class="text-[#12A150] hover:text-[#0e803f] font-semibold" wire:navigate>
                    {{ __('Create an account') }}
                </flux:link>
            @endif
        </div>
    </div>
</x-layouts::auth>
