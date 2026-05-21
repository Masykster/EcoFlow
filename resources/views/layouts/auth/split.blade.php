<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-[#F8F9FA] dark:bg-[#0D1512] antialiased relative overflow-x-hidden">
        <div class="relative grid h-dvh flex-col items-center justify-center px-4 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <!-- Left Panel (Premium Mesh Gradient + Quote) -->
            <div class="relative hidden h-full flex-col p-12 text-white lg:flex border-e border-white/10 overflow-hidden">
                <!-- Gorgeous ecological gradient mesh background -->
                <div class="absolute inset-0 bg-gradient-to-tr from-[#122821] via-[#1E3F35] to-[#2D5F50] z-0"></div>
                <!-- Glowing ambient light inside gradient -->
                <div class="absolute top-[-20%] right-[-20%] w-[500px] h-[500px] rounded-full bg-[#A3D9A5]/20 blur-[100px] pointer-events-none"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[400px] h-[400px] rounded-full bg-emerald-500/10 blur-[80px] pointer-events-none"></div>
                
                <a href="{{ route('home') }}" class="relative z-20 flex items-center gap-3 text-xl font-bold group" wire:navigate>
                    <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white/10 backdrop-blur-md border border-white/20 transition-all duration-300 group-hover:scale-105 shadow-md">
                        <x-app-logo-icon class="size-6 text-[#A3D9A5]" />
                    </span>
                    <span class="tracking-tight">EcoFlow</span>
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto bg-white/5 backdrop-blur-md border border-white/10 rounded-[24px] p-8 shadow-xl">
                    <blockquote class="space-y-3">
                        <flux:heading size="lg" class="text-white font-medium leading-relaxed">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                        <footer class="mt-2"><flux:heading class="text-[#A3D9A5] font-bold text-sm uppercase tracking-wider">— {{ trim($author) }}</flux:heading></footer>
                    </blockquote>
                </div>
            </div>

            <!-- Right Panel (Form Area with Glassmorphism) -->
            <div class="relative w-full h-full lg:p-8 flex items-center justify-center overflow-y-auto">
                <!-- Glowing spheres for right panel background -->
                <div class="absolute top-[-10%] right-[-10%] w-[300px] h-[300px] rounded-full bg-[#A3D9A5]/10 dark:bg-[#A3D9A5]/5 blur-[70px] pointer-events-none"></div>
                <div class="absolute bottom-[-10%] left-[-10%] w-[250px] h-[250px] rounded-full bg-[#1E3F35]/10 dark:bg-[#1E3F35]/20 blur-[60px] pointer-events-none"></div>

                <div class="relative z-10 mx-auto flex w-full max-w-md flex-col justify-center space-y-6 bg-white/40 dark:bg-[#1E2623]/30 backdrop-blur-xl border border-white/50 dark:border-white/[0.06] shadow-[0_20px_50px_rgba(30,63,53,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.25)] rounded-[32px] p-8 md:p-10">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden group mb-4" wire:navigate>
                        <span class="flex h-12 w-12 items-center justify-center rounded-full bg-[#1E3F35] dark:bg-[#A3D9A5] shadow-md transition-all duration-300 group-hover:scale-105">
                            <x-app-logo-icon class="size-7 text-[#A3D9A5] dark:text-[#1E3F35]" />
                        </span>
                        <span class="text-xl font-bold tracking-tight text-[#1E3F35] dark:text-white mt-1">EcoFlow</span>
                    </a>
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
