<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <flux:heading size="xl" class="font-extrabold">{{ __('Create an account') }}</flux:heading>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter your name"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autocomplete="email"
                placeholder="Enter your email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Enter your password"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm Password')"
                type="password"
                required
                autocomplete="new-password"
                placeholder="Confirm your password"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" class="w-full bg-[#12A150] hover:bg-[#0e803f] text-white font-bold py-3.5 rounded-xl border-none transition-colors duration-200" data-test="register-user-button">
                    {{ __('Continue') }}
                </flux:button>
            </div>
        </form>

        <div class="flex items-center justify-center text-sm mt-4">
            <span class="text-zinc-600 dark:text-zinc-400 mr-1.5">{{ __('Already have an account?') }}</span>
            <flux:link :href="route('login')" class="text-[#12A150] hover:text-[#0e803f] font-semibold" wire:navigate>
                {{ __('Log in') }}
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
