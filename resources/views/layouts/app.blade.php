<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen h-[100dvh] bg-[#edf2ef] dark:bg-[#090f0d] text-slate-800 dark:text-white overflow-hidden"
      x-data="{ theme: localStorage.getItem('theme') || 'dark' }"
      x-init="$watch('theme', val => { localStorage.setItem('theme', val); window.dispatchEvent(new CustomEvent('theme-changed', { detail: val })); })"
      :class="{ 'dark': theme === 'dark' }">
    <div class="h-[100dvh] w-full bg-[#edf2ef] dark:bg-[#090f0d] text-slate-800 dark:text-white p-4 md:p-6 flex flex-col items-center justify-center relative overflow-hidden">
        {{-- Ambient Background Leaves --}}
        <div class="absolute inset-0 z-0 bg-cover bg-center filter blur-xl opacity-5 dark:opacity-20 scale-105 pointer-events-none" 
             style="background-image: url('/images/hero_forest.png');"></div>
        <div class="absolute inset-0 z-0 bg-gradient-to-b from-[#e1e8e4]/80 via-[#edf2ef]/90 to-[#e5ebe7]/95 dark:from-[#090f0c]/90 dark:via-[#0c1410]/95 dark:to-[#050a08]/98 pointer-events-none"></div>

        {{-- Main Glassmorphic Panel --}}
        <div class="relative z-10 w-full max-w-[1280px] h-[92vh] bg-white/60 dark:bg-[#121c17]/60 dark:bg-zinc-950/45 backdrop-blur-xl border border-black/5 dark:border-white/10 rounded-[3rem] p-6 md:p-8 shadow-2xl flex flex-col justify-between gap-4 overflow-hidden">
            
            {{-- Header inside the panel --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 pb-2 border-b border-white/5 shrink-0">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 hover:opacity-95 transition-opacity" wire:navigate>
                    <img src="{{ asset('favicon.svg') }}" alt="EcoFlow Logo" class="w-8 h-8 object-contain">
                    <div class="text-left">
                        <span class="text-xl font-bold tracking-tight text-slate-800 dark:text-white leading-none block">EcoFlow</span>
                        <span class="block text-[9px] font-bold text-[#1E3F35] dark:text-[#A3D9A5] uppercase tracking-widest mt-0.5">Carbon Analytics</span>
                    </div>
                </a>

                <!-- Navigation Switcher for the 4 Sections -->
                <div class="flex bg-gray-200/60 dark:bg-[#1d2722]/80 border border-black/5 dark:border-white/5 p-1 rounded-full shadow-inner">
                    <a href="{{ route('dashboard') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-white dark:bg-[#2b3731] text-[#1E3F35] dark:text-[#A3D9A5] shadow-sm border border-black/5 dark:border-white/5' : 'text-gray-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white' }}"
                       wire:navigate>Dashboard</a>
                    
                    <a href="{{ route('calculator') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ request()->routeIs('calculator') ? 'bg-white dark:bg-[#2b3731] text-[#1E3F35] dark:text-[#A3D9A5] shadow-sm border border-black/5 dark:border-white/5' : 'text-gray-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white' }}"
                       wire:navigate>Kalkulator</a>
                    
                    <a href="{{ route('history') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ request()->routeIs('history') ? 'bg-white dark:bg-[#2b3731] text-[#1E3F35] dark:text-[#A3D9A5] shadow-sm border border-black/5 dark:border-white/5' : 'text-gray-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white' }}"
                       wire:navigate>Riwayat</a>
                    
                    <a href="{{ route('achievements') }}" 
                       class="px-5 py-2 rounded-full text-xs font-bold transition-all duration-300 {{ request()->routeIs('achievements') ? 'bg-white dark:bg-[#2b3731] text-[#1E3F35] dark:text-[#A3D9A5] shadow-sm border border-black/5 dark:border-white/5' : 'text-gray-500 hover:text-slate-800 dark:text-gray-400 dark:hover:text-white' }}"
                       wire:navigate>Pencapaian</a>
                </div>

                <!-- User Profile & Action Dropdown -->
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 text-right">
                        <div>
                            <span class="block text-sm font-bold text-slate-800 dark:text-white leading-none">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] text-gray-500 dark:text-gray-400 font-semibold mt-1">Carbon Analyst</span>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-[#e1e8e4] dark:bg-zinc-800 border border-black/5 dark:border-white/10 overflow-hidden shadow-inner">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=1E3F35&color=A3D9A5" alt="User Avatar" class="w-full h-full object-cover">
                        </div>
                    </div>
                    
                    <!-- Settings Dropdown -->
                    <div x-data="{ open: false }" class="relative flex items-center gap-2">
                        
                        <!-- Theme Toggle Button -->
                        <button @click="theme = (theme === 'dark' ? 'light' : 'dark')" 
                                class="w-10 h-10 rounded-full bg-[#e1e8e4] dark:bg-[#1b2520] hover:bg-[#d2dcd6] dark:hover:bg-[#25322b] border border-black/5 dark:border-white/10 flex items-center justify-center text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer"
                                title="Toggle Theme">
                            <!-- Sun Icon (visible in dark mode) -->
                            <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l-.707-.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
                            </svg>
                            <!-- Moon Icon (visible in light mode) -->
                            <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                            </svg>
                        </button>

                        <button @click="open = !open" 
                                class="w-10 h-10 rounded-full bg-[#e1e8e4] dark:bg-[#1b2520] hover:bg-[#d2dcd6] dark:hover:bg-[#25322b] border border-black/5 dark:border-white/10 flex items-center justify-center text-slate-600 dark:text-gray-400 hover:text-slate-800 dark:hover:text-white transition-colors cursor-pointer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" 
                             class="absolute right-0 mt-2 w-48 rounded-xl bg-white dark:bg-[#121c17] border border-black/5 dark:border-white/10 shadow-2xl p-2 z-50 text-left"
                             x-transition style="display: none;">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs font-bold text-slate-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#1b2520] rounded-lg transition-colors" wire:navigate>
                                Settings
                            </a>
                            <div class="h-px bg-black/5 dark:bg-white/5 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-xs font-bold text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-[#1b2520] rounded-lg transition-colors cursor-pointer">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Slot Container -->
            <div class="flex-1 min-h-0 w-full overflow-y-auto">
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
