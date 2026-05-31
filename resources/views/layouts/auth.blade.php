@props([
    'title' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-[#0D1512] antialiased relative overflow-x-hidden">
        <div class="relative grid h-screen w-screen grid-cols-1 lg:grid-cols-12 p-4 gap-4 bg-white dark:bg-[#0D1512]">
            <!-- Left Panel (Premium Rounded Marketing Card) -->
            <div class="lg:col-span-5 hidden lg:flex flex-col justify-between bg-[#EAF5E9]/60 dark:bg-[#12241F]/40 border border-[#E1F0DF] dark:border-[#1E352E]/30 rounded-[32px] p-12 overflow-hidden relative">
                <!-- Glowing background accents -->
                <div class="absolute top-[-10%] right-[-10%] w-[300px] h-[300px] rounded-full bg-[#A3D9A5]/30 dark:bg-[#A3D9A5]/10 blur-[80px] pointer-events-none z-0"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[250px] h-[250px] rounded-full bg-[#1E3F35]/20 dark:bg-[#1E3F35]/15 blur-[70px] pointer-events-none z-0"></div>

                <!-- Top Logo & Brand -->
                <a href="{{ route('home') }}" class="relative z-20 flex items-center justify-center gap-2 group self-center" wire:navigate>
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-[#1E3F35] dark:bg-[#A3D9A5] shadow-md transition-all duration-300 group-hover:scale-105">
                        <x-app-logo-icon class="size-6 text-[#A3D9A5] dark:text-[#1E3F35]" />
                    </span>
                    <span class="text-2xl font-bold tracking-tight text-[#1E3F35] dark:text-white">EcoFlow</span>
                </a>

                <!-- Center Dashboard Mockup Graphic -->
                <div class="flex-grow flex items-center justify-center py-6 relative z-10">
                    <!-- Circular element behind mockup -->
                    <div class="absolute w-[280px] h-[280px] rounded-full bg-[#D4ECD2]/50 dark:bg-[#1E3F35]/35 blur-[35px] z-0"></div>
                    <img src="{{ asset('images/login_mockup.png') }}" alt="EcoFlow Dashboard Mockup" class="w-full max-w-[340px] h-auto object-contain relative z-10 drop-shadow-[0_15px_30px_rgba(0,0,0,0.08)] transform hover:scale-[1.03] transition-transform duration-500">
                </div>

                <!-- Bottom Captions -->
                <div class="text-center relative z-20 mt-auto">
                    <h3 class="text-xl font-bold text-[#1E3F35] dark:text-white mb-2 tracking-tight">Satu akun untuk semua fitur EcoFlow</h3>
                    <p class="text-sm text-[#466952] dark:text-[#8BB39A] leading-relaxed max-w-sm mx-auto font-medium">EcoFlow Account adalah akun Anda yang terintegrasi untuk melacak, mengelola, dan mengurangi emisi karbon secara efisien.</p>
                </div>
            </div>

            <!-- Right Panel (Form Card Container) -->
            <div class="lg:col-span-7 flex items-center justify-center p-4 md:p-8 overflow-y-auto">
                <!-- Additional subtle glow behind form area -->
                <div class="absolute top-[20%] right-[10%] w-[200px] h-[200px] rounded-full bg-[#A3D9A5]/10 dark:bg-[#A3D9A5]/5 blur-[60px] pointer-events-none"></div>

                <div class="w-full max-w-md bg-white dark:bg-[#111A16] border border-zinc-200/80 dark:border-white/[0.06] shadow-[0_8px_30px_rgba(0,0,0,0.02)] dark:shadow-[0_16px_40px_rgba(0,0,0,0.3)] rounded-[28px] p-8 md:p-10 relative z-10">
                    <!-- EcoFlow Identity Logo inside the Card -->
                    <div class="flex items-center justify-center gap-2 mb-8">
                        <x-app-logo-icon class="w-6 h-6 text-[#12A150]" />
                        <span class="text-lg font-black tracking-tight text-zinc-900 dark:text-white">EcoFlow<span class="text-[#12A150]">Identity</span></span>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
