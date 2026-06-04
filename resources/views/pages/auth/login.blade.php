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

        <div class="relative flex py-2 items-center">
            <div class="flex-grow border-t border-zinc-200 dark:border-zinc-700"></div>
            <span class="flex-shrink mx-4 text-zinc-400 text-xs uppercase">{{ __('Or continue with') }}</span>
            <div class="flex-grow border-t border-zinc-200 dark:border-zinc-700"></div>
        </div>

        <flux:button href="{{ route('google.redirect') }}" class="w-full flex items-center justify-center gap-2 bg-white hover:bg-zinc-50 text-zinc-700 border border-zinc-200 hover:border-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 dark:text-zinc-200 dark:border-zinc-700 py-3.5 rounded-xl transition-colors duration-200">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"/>
            </svg>
            {{ __('Sign in with Google') }}
        </flux:button>

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
