<x-layouts::auth :title="__('Register')">
    <div class="flex flex-col gap-6">
        <div class="text-center">
            <flux:heading size="xl" class="font-extrabold">{{ __('Create an account') }}</flux:heading>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <input type="hidden" name="firebase_uid" id="firebase_uid" />
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

<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const firebaseConfig = {
            apiKey: "{{ config('services.firebase.api_key') }}",
            authDomain: "{{ config('services.firebase.auth_domain') }}",
            projectId: "{{ config('services.firebase.project_id') }}",
            storageBucket: "{{ config('services.firebase.storage_bucket') }}",
            messagingSenderId: "{{ config('services.firebase.messaging_sender_id') }}",
            appId: "{{ config('services.firebase.app_id') }}"
        };

        const hasConfig = firebaseConfig.apiKey && firebaseConfig.apiKey !== '';
        let auth;
        if (hasConfig) {
            firebase.initializeApp(firebaseConfig);
            auth = firebase.auth();
        } else {
            console.warn("Firebase credentials not configured in .env. Running in Mock/Simulator mode.");
        }

        const registerForm = document.querySelector('form[action="{{ route('register.store') }}"]');
        if (registerForm) {
            registerForm.addEventListener('submit', async function(e) {
                const firebaseUidInput = document.getElementById('firebase_uid');
                if (firebaseUidInput && firebaseUidInput.value) {
                    return; // Allow native submission
                }

                e.preventDefault();

                const email = registerForm.querySelector('input[name="email"]').value;
                const password = registerForm.querySelector('input[name="password"]').value;

                if (hasConfig) {
                    try {
                        const userCredential = await auth.createUserWithEmailAndPassword(email, password);
                        firebaseUidInput.value = userCredential.user.uid;
                        registerForm.submit();
                    } catch (error) {
                        alert("Firebase Registration Error: " + error.message);
                    }
                } else {
                    console.log("Firebase Simulated registration successful.");
                    firebaseUidInput.value = 'mock-firebase-uid-' + Math.random().toString(36).substring(2, 15);
                    registerForm.submit();
                }
            });
        }
    });
</script>
