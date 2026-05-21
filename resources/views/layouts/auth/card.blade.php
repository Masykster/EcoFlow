<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#F8F9FA] dark:bg-[#0D1512] antialiased relative overflow-x-hidden flex items-center justify-center">
        <!-- Floating organic blobs for premium glassmorphism background -->
        <div class="absolute top-[-10%] right-[-10%] w-[350px] md:w-[600px] h-[350px] md:h-[600px] rounded-full bg-[#A3D9A5]/25 dark:bg-[#A3D9A5]/10 blur-[80px] md:blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[300px] md:w-[500px] h-[300px] md:h-[500px] rounded-full bg-[#1E3F35]/20 dark:bg-[#1E3F35]/35 blur-[60px] md:blur-[100px] pointer-events-none"></div>
        <div class="absolute top-[40%] left-[50%] -translate-x-1/2 -translate-y-1/2 w-[200px] h-[200px] rounded-full bg-[#E67E5D]/10 dark:bg-[#E67E5D]/5 blur-[70px] pointer-events-none"></div>

        <div class="relative z-10 flex min-h-svh w-full flex-col items-center justify-center gap-6 p-4 md:p-10">
            <!-- Glassmorphic panel -->
            <div class="flex w-full max-w-md flex-col gap-4 bg-white/40 dark:bg-[#1E2623]/30 backdrop-blur-xl border border-white/50 dark:border-white/[0.06] shadow-[0_20px_50px_rgba(30,63,53,0.12)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-[32px] p-8 md:p-10">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium mb-2 group" wire:navigate>
                    <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#1E3F35] dark:bg-[#A3D9A5] shadow-md transition-all duration-300 group-hover:scale-105">
                        <x-app-logo-icon class="size-7 text-[#A3D9A5] dark:text-[#1E3F35]" />
                    </span>
                    <span class="text-xl font-bold tracking-tight text-[#1E3F35] dark:text-white mt-1">EcoFlow</span>
                </a>
                <div class="flex flex-col gap-6">
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
