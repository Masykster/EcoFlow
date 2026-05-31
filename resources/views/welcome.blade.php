@php
    $totalUsers = max(2840, \App\Models\User::count());
    $totalSavedKg = max(14850.2, round(\App\Models\UserPoint::sum('points') * 0.5 + 14850.2, 1));
    $formattedUsers = number_format($totalUsers, 0, ',', '.');
    $formattedSaved = number_format($totalSavedKg, 1, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EcoFlow - Langkah Kecil untuk Jejak yang Lebih Hijau</title>
    
    <!-- Fonts: Plus Jakarta Sans (Jejakin style) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; color: #171717; }
        
        /* Jejakin-like dot grid background */
        .bg-dots {
            background-image: radial-gradient(circle, #CBD5E1 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* Custom Animations */
        .reveal-up { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .reveal-up.active { opacity: 1; transform: translateY(0); }
        
        .reveal-left { opacity: 0; transform: translateX(-40px); transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .reveal-left.active { opacity: 1; transform: translateX(0); }

        .reveal-right { opacity: 0; transform: translateX(40px); transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .reveal-right.active { opacity: 1; transform: translateX(0); }
        
        .reveal-zoom { opacity: 0; transform: scale(0.95); transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94); }
        .reveal-zoom.active { opacity: 1; transform: scale(1); }

        .delay-100 { transition-delay: 100ms; }
        .delay-200 { transition-delay: 200ms; }
        .delay-300 { transition-delay: 300ms; }
        
        /* Soft lift effect for cards */
        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        }

        /* Custom Cursor styling */
        @media (min-width: 768px) {
            body, a, button, p, h1, h2, h3, h4, span, div {
                cursor: none !important;
            }
        }
        
        @keyframes droplet {
            0% { width: 0px; height: 0px; opacity: 0.8; }
            100% { width: 60px; height: 60px; opacity: 0; }
        }
        .droplet-effect {
            position: fixed;
            border-radius: 50%;
            background-color: rgba(18, 161, 80, 0.2);
            border: 2px solid rgba(18, 161, 80, 0.6);
            pointer-events: none;
            transform: translate(-50%, -50%);
            animation: droplet 0.4s ease-out forwards;
            z-index: 9998;
        }
    </style>
</head>
<body class="antialiased bg-[#FAFAFA] selection:bg-[#62F369] selection:text-black">

    <!-- Floating Navbar (Jejakin Style) -->
    <div id="floating-navbar-container" class="fixed top-6 left-0 right-0 z-50 px-4 md:px-0 flex justify-center pointer-events-none transition-all duration-500 transform -translate-y-12 opacity-0 scale-95">
        <nav id="floating-navbar" class="bg-white/70 backdrop-blur-xl border border-white/20 shadow-[0_8px_32px_rgba(0,0,0,0.03)] shadow-[inset_0_1px_0_rgba(255,255,255,0.5)] rounded-full px-6 py-3 flex items-center justify-between pointer-events-auto transition-all duration-700 ease-out w-[60px] opacity-0 overflow-hidden">
            <div id="navbar-content" class="w-[1100px] max-w-[85vw] flex justify-between items-center opacity-0 transition-opacity duration-300 pointer-events-none flex-shrink-0">
                <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity">
                    <!-- Logo Icon -->
                    <img src="{{ asset('favicon.svg') }}" alt="EcoFlow Logo" class="w-8 h-8 object-contain">
                    <span class="text-xl font-bold tracking-tight text-[#111827]">EcoFlow</span>
                </div>
                
                <div id="nav-links" class="hidden md:flex items-center gap-8 text-sm font-semibold text-[#4B5563]">
                    <a href="#beranda" class="nav-link text-[#1E3F35] relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-[#A3D9A5] after:rounded-full">Beranda</a>
                    <a href="#tentang-kami" class="nav-link hover:text-[#1E3F35] transition-colors">Tentang Kami</a>
                    <a href="#kalkulator" class="nav-link hover:text-[#1E3F35] transition-colors">Kalkulator</a>
                    <a href="#cta-offset" class="nav-link hover:text-[#1E3F35] transition-colors">Aksi Hijau</a>
                    <a href="#faq" class="nav-link hover:text-[#1E3F35] transition-colors">FAQ</a>
                </div>
                
                <div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-[#1E3F35] hover:bg-[#A3D9A5] hover:text-[#1E3F35] text-white px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 active:scale-[0.98]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="bg-[#1E3F35] hover:bg-[#A3D9A5] hover:text-[#1E3F35] text-white px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 active:scale-[0.98]">Sign In</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>

    <!-- Hero Section: Scroll Expansion Paradigm -->
    <section id="beranda" class="relative h-[200vh] w-full bg-[#0F211C] overflow-visible">
        <!-- Sticky viewport container to hold full-screen layout while scroll animation plays -->
        <div class="w-full h-[100vh] sticky top-0 overflow-hidden flex flex-col items-center justify-start z-10">
            
            <!-- Background Image Layer: Fades out as scroll progress increases -->
            <div id="hero-bg-image" class="absolute inset-0 z-0 h-full w-full pointer-events-none transition-opacity duration-75">
                <img src="/images/hero_forest.png" alt="EcoFlow Background" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-[#0F211C]/35"></div>
            </div>

            <!-- Dot Grid Background for Jejakin theme -->
            <div class="absolute inset-0 bg-dots opacity-20 pointer-events-none z-10"></div>

            <!-- Content Wrapper -->
            <div class="container mx-auto flex flex-col items-center justify-start relative z-20 h-full">
                <div class="flex flex-col items-center justify-center w-full h-full relative">
                    
                    <!-- Expanding Media Container (video) -->
                    <div id="hero-media-container" class="absolute z-10 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 rounded-[24px] overflow-hidden bg-black shadow-[0px_0px_50px_rgba(0,0,0,0.3)] transition-none" style="width: 350px; height: 450px; max-width: 95vw; max-height: 85vh;">
                        <div class="relative w-full h-full pointer-events-none">
                            <video id="hero-media-video" src="https://me7aitdbxq.ufs.sh/f/2wsMIGDMQRdYuZ5R8ahEEZ4aQK56LizRdfBSqeDMsmUIrJN1" autoPlay muted loop playsInline preload="auto" class="w-full h-full object-cover rounded-xl"></video>
                            <div class="absolute inset-0 bg-black/35 z-10"></div>
                        </div>
                    </div>

                    <!-- Slide-out Title Texts: Translate away horizontally -->
                    <div id="hero-slide-titles" class="flex flex-col items-center justify-center text-center gap-4 w-full relative z-30 pointer-events-none mix-blend-difference">
                        <h2 id="hero-title-first" class="text-5xl md:text-7xl font-extrabold text-[#A3D9A5] transition-none leading-none tracking-tight">EcoFlow</h2>
                        <h2 id="hero-title-rest" class="text-4xl md:text-6xl font-extrabold text-white text-center transition-none leading-none tracking-tight">Carbon Analytics</h2>
                    </div>

                    <!-- Text markers / scrolling instructions -->
                    <div id="hero-scroll-marker" class="absolute bottom-10 left-0 right-0 z-30 flex flex-col items-center justify-center pointer-events-none text-white/70 font-medium text-sm transition-all duration-300">
                        <span class="animate-bounce text-lg">↓</span>
                        <span>Scroll untuk Memulai</span>
                    </div>

                    <!-- Expanded Hero Content: Fades in after expansion is complete -->
                    <div id="hero-expanded-content" class="absolute inset-0 z-40 flex flex-col items-center justify-center px-6 text-center opacity-0 pointer-events-none transition-opacity duration-500 max-w-4xl mx-auto gap-6">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#1E3F35]/25 border border-white/10 text-xs font-bold text-[#A3D9A5] uppercase tracking-widest">
                            <span class="w-2 h-2 rounded-full bg-[#A3D9A5] animate-pulse"></span>
                            Standar Emisi IPCC
                        </div>
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white leading-none tracking-tight">
                            Langkah Kecil untuk<br>
                            <span class="relative text-[#A3D9A5]">
                                Jejak yang Lebih Hijau.
                            </span>
                        </h1>
                        <p class="text-base md:text-xl text-gray-300 leading-relaxed max-w-2xl font-medium">
                            Pantau dan kurangi emisi karbon harianmu dengan kalkulator cerdas berbasis standar IPCC. Aksi nyata untuk mitigasi perubahan iklim dimulai dari genggamanmu.
                        </p>
                        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-2">
                            <a href="#kalkulator" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#E67E5D] text-white hover:bg-[#d96d4b] active:scale-[0.98] px-8 py-4 rounded-full font-bold transition-all text-lg shadow-lg pointer-events-auto">
                                Hitung Jejak Karbonmu
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section id="tentang-kami" class="py-32 px-6 lg:px-16 bg-white overflow-hidden relative">
        <!-- White Sphere Grid & Yellow-Green Glow Background -->
        <div class="absolute inset-0 z-0 pointer-events-none" style="
            background: white;
            background-image: 
                linear-gradient(to right, rgba(30,63,53,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(30,63,53,0.06) 1px, transparent 1px),
                radial-gradient(circle at 50% 50%, rgba(163,217,165,0.2) 0%, rgba(163,217,165,0.05) 40%, transparent 80%),
                radial-gradient(circle at center, #FFF991 0%, transparent 70%);
            background-size: 32px 32px, 32px 32px, 100% 100%, 100% 100%;
            opacity: 0.85;
        "></div>
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16 lg:gap-24 relative z-10">
            
            <!-- Left Text -->
            <div class="w-full md:w-1/2 reveal-left">
                <div class="inline-flex px-3 py-1 rounded-full bg-[#1E3F35]/15 text-[#1E3F35] text-xs font-bold uppercase tracking-wider mb-6">
                    Tentang Platform
                </div>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-[#171717] mb-6 tracking-tight leading-tight">
                    Mengapa <span class="text-[#1E3F35]">EcoFlow?</span>
                </h2>
                <p class="text-xl text-[#555555] leading-relaxed font-medium mb-8">
                    Kami menghadirkan solusi digital untuk membantu kamu memahami dampak aktivitas harian terhadap lingkungan dan mengambil aksi nyata bagi iklim.
                </p>
                <div class="flex gap-2 mb-8">
                    <div class="w-12 h-1.5 bg-[#A3D9A5] rounded-full"></div>
                    <div class="w-4 h-1.5 bg-gray-200 rounded-full"></div>
                </div>
            </div>

            <!-- Right Image Carousel visual -->
            <div class="w-full md:w-1/2 relative rounded-[32px] overflow-hidden shadow-2xl group reveal-right" id="slider" style="aspect-ratio: 4/3;">
                <div id="slides" class="flex transition-transform duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] w-[300%] h-full">
                    <div class="w-1/3 h-full relative">
                        <img src="/images/plant_glass.png" alt="Plant in glass" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white"><h3 class="font-bold text-2xl">Penanaman Pohon</h3></div>
                    </div>
                    <div class="w-1/3 h-full relative">
                        <img src="/images/slider_analytics.png" alt="Eco Analytics" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white"><h3 class="font-bold text-2xl">Analitik Emisi</h3></div>
                    </div>
                    <div class="w-1/3 h-full relative">
                        <img src="/images/slider_community.png" alt="Eco Community" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-8 left-8 text-white"><h3 class="font-bold text-2xl">Aksi Komunitas</h3></div>
                    </div>
                </div>
                
                <!-- Navigation arrows overlay -->
                <button id="slider-prev" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur hover:bg-[#A3D9A5] rounded-full flex items-center justify-center text-[#171717] shadow-xl transition-all transform hover:scale-110 opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button id="slider-next" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur hover:bg-[#A3D9A5] rounded-full flex items-center justify-center text-[#171717] shadow-xl transition-all transform hover:scale-110 opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
                
                <!-- Dots overlay -->
                <div class="absolute bottom-8 right-8 flex gap-2" id="slider-dots">
                    <button class="h-2 rounded-full bg-[#1E3F35] w-6 transition-all slider-dot" data-idx="0"></button>
                    <button class="w-2 h-2 rounded-full bg-white/50 transition-all slider-dot" data-idx="1"></button>
                    <button class="w-2 h-2 rounded-full bg-white/50 transition-all slider-dot" data-idx="2"></button>
                </div>
            </div>
            
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-32 px-6 lg:px-16 bg-[#F9FAFB] text-[#171717] text-center relative overflow-hidden">
        <!-- Abstract Shapes -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#A3D9A5]/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#1E3F35]/5 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <!-- Pill -->
            <div class="inline-flex px-4 py-1.5 rounded-full bg-[#1E3F35]/10 text-[#1E3F35] text-xs font-black uppercase tracking-wider mb-6 reveal-up border border-[#1E3F35]/10">
                Aksi Hijau
            </div>
            <h2 class="text-4xl md:text-5xl font-black text-[#171717] mb-6 reveal-up tracking-tight">
                Mulai Aksi Hijau Kamu Dalam 3 Langkah Mudah
            </h2>
            <p class="text-gray-500 mb-20 max-w-2xl mx-auto text-lg reveal-up delay-100 font-medium">
                Platform komprehensif yang didesain secara khusus untuk memudahkan kamu dalam mengelola, memantau, dan menurunkan jejak karbon.
            </p>
            
            <!-- Asymmetric Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                
                <!-- Step 1: Purple Card -->
                <div class="bg-[#8778f5] text-white p-8 md:p-10 rounded-[2.5rem] text-left flex flex-col justify-between overflow-hidden relative group reveal-left" style="min-height: 480px;">
                    <div>
                        <div class="inline-block px-3 py-1 rounded-full bg-white/20 text-white text-[10px] font-bold uppercase tracking-wider mb-6">
                            Langkah 1
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black mb-4 tracking-tight leading-tight">
                            Kalkulasi Presisi Emisi Harian
                        </h3>
                        <p class="text-white/80 text-sm md:text-base leading-relaxed mb-8 max-w-[45ch]">
                            Hitung emisi CO2e dari transportasi hingga konsumsi makanan secara akurat menggunakan metodologi IPCC & DEFRA yang diakui dunia.
                        </p>
                    </div>

                    <!-- CSS Mock UI: Floating Calculator Card -->
                    <div class="relative w-full h-48 mt-auto flex items-end justify-center">
                        <div class="bg-white text-[#171717] rounded-3xl p-5 shadow-2xl border border-white/10 w-full max-w-[340px] transform translate-y-4 group-hover:-translate-y-2 transition-transform duration-500 relative z-10 text-left">
                            <div class="flex justify-between items-center mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-[#EAF3EB] text-[#2D5F50]">Kalkulator Aktif</span>
                                <div class="flex gap-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                                </div>
                            </div>
                            <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-widest leading-none">Bahan Bakar</span>
                            <span class="block text-sm font-black text-gray-800 mt-1 leading-none">Pertalite - 10 Liter</span>
                            
                            <!-- Result Box -->
                            <div class="mt-4 p-3 bg-gray-50 rounded-2xl border border-gray-100 flex justify-between items-center">
                                <div>
                                    <span class="text-[7px] font-bold text-gray-400 uppercase tracking-wider block">Estimasi emisi</span>
                                    <span class="text-lg font-black text-[#2D5F50] leading-none block mt-1">22.50 kg CO₂e</span>
                                </div>
                                <div class="flex -space-x-1.5">
                                    <div class="w-5 h-5 rounded-full bg-[#1E3F35] text-[6px] font-bold text-white flex items-center justify-center border border-white">AM</div>
                                    <div class="w-5 h-5 rounded-full bg-[#A3D9A5] text-[6px] font-bold text-[#1E3F35] flex items-center justify-center border border-white">CF</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Yellow/Gold Card -->
                <div class="bg-[#f5c35b] text-[#1E3F35] p-8 md:p-10 rounded-[2.5rem] text-left flex flex-col justify-between overflow-hidden relative group reveal-right" style="min-height: 480px;">
                    <div>
                        <div class="inline-block px-3 py-1 rounded-full bg-[#1E3F35]/15 text-[#1E3F35] text-[10px] font-bold uppercase tracking-wider mb-6">
                            Langkah 2
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black mb-4 tracking-tight leading-tight">
                            Analitik Personal & Pemantauan
                        </h3>
                        <p class="text-[#1E3F35]/80 text-sm md:text-base leading-relaxed mb-8 max-w-[45ch]">
                            Pantau riwayat emisi mingguan dan bulanan kamu melalui dashboard bento-grid yang dirancang untuk memudahkan pemantauan progres.
                        </p>
                    </div>

                    <!-- CSS Mock UI: Floating Dashboard Card -->
                    <div class="relative w-full h-48 mt-auto flex items-end justify-center">
                        <div class="bg-white text-[#171717] rounded-3xl p-5 shadow-2xl border border-white/10 w-full max-w-[340px] transform translate-y-4 group-hover:-translate-y-2 transition-transform duration-500 relative z-10 text-left">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-xs font-black text-gray-800 leading-none">Batas Emisi Bulanan</span>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-100 rounded-full h-2 mb-2">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: 65%;"></div>
                            </div>
                            <div class="flex justify-between text-[8px] font-bold text-gray-400">
                                <span>650 kg Terpakai</span>
                                <span>1000 kg Limit</span>
                            </div>

                            <!-- Color tags / categories -->
                            <div class="mt-4 flex gap-1.5">
                                <div class="w-4 h-4 rounded-full bg-emerald-500/20 border border-emerald-500 flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                                </div>
                                <div class="w-4 h-4 rounded-full bg-amber-500/20 border border-amber-500 flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                                </div>
                                <div class="w-4 h-4 rounded-full bg-blue-500/20 border border-blue-500 flex items-center justify-center">
                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                </div>
                                <span class="text-[8px] font-bold text-gray-400 self-center ml-1">Saran AI: Matikan lampu siang hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Lime Green Wide Card -->
            <div class="bg-[#a8f387] text-[#1E3F35] p-8 md:p-12 rounded-[2.5rem] text-left reveal-up overflow-hidden relative group">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                    
                    <!-- Left Content Column -->
                    <div class="lg:col-span-7 flex flex-col justify-between h-full">
                        <div>
                            <div class="inline-block px-3 py-1 rounded-full bg-[#1E3F35]/15 text-[#1E3F35] text-[10px] font-bold uppercase tracking-wider mb-6">
                                Langkah 3
                            </div>
                            <h3 class="text-3xl md:text-4xl font-black mb-4 tracking-tight leading-tight">
                                Aksi Nyata & Raih Penghargaan
                            </h3>
                            <p class="text-[#1E3F35]/80 text-sm md:text-base leading-relaxed mb-8 max-w-[50ch]">
                                Dapatkan tips ramah lingkungan yang dipersonalisasi dan raih badge eksklusif sebagai apresiasi atas kontribusi hijaumu.
                            </p>
                        </div>
                        
                        <div>
                            <a href="{{ route('calculator') }}" class="inline-flex bg-[#171717] hover:bg-[#262626] active:scale-[0.98] active:translate-y-0.5 text-white text-xs font-bold py-3.5 px-7 rounded-full shadow-lg transition-all tracking-wider uppercase leading-none">
                                Mulai Sekarang
                            </a>
                        </div>
                    </div>
                    
                    <!-- Right CSS Mockup Visual Column -->
                    <div class="lg:col-span-5 flex justify-center items-center relative min-h-[220px]">
                        
                        <!-- Floating Badge Card 1 -->
                        <div class="absolute bg-white text-[#171717] rounded-3xl p-4 shadow-xl border border-white/10 w-44 transform -translate-x-12 -translate-y-4 -rotate-6 group-hover:-translate-y-8 transition-transform duration-500 z-10">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center mb-2 mx-auto">
                                <svg class="w-6 h-6 text-emerald-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                            </div>
                            <span class="block text-xs font-black text-center text-gray-800">Pejuang MRT</span>
                            <span class="block text-[8px] font-semibold text-center text-emerald-600 mt-1 leading-none bg-emerald-50 px-2 py-0.5 rounded-full w-fit mx-auto border border-emerald-100">Diraih</span>
                        </div>

                        <!-- Floating Badge Card 2 -->
                        <div class="absolute bg-white text-[#171717] rounded-3xl p-4 shadow-xl border border-white/10 w-44 transform translate-x-12 translate-y-6 rotate-6 group-hover:translate-y-2 transition-transform duration-500 z-20">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center mb-2 mx-auto">
                                <svg class="w-6 h-6 text-amber-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M12 3C8.5 7 5.5 11 5.5 15c0 3.5 2.5 6 6.5 6m0-18c3.5 4 6.5 8 6.5 12 0 3.5-2.5 6-6.5 6" />
                                </svg>
                            </div>
                            <span class="block text-xs font-black text-center text-gray-800">Carbon Fighter</span>
                            <span class="block text-[8px] font-semibold text-center text-amber-600 mt-1 leading-none bg-amber-50 px-2 py-0.5 rounded-full w-fit mx-auto border border-amber-100">Diraih</span>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </section>

    <section class="py-32 px-6 lg:px-16 bg-white overflow-hidden relative">
        <!-- White Grid with Dots Background -->
        <div class="absolute inset-0 z-0 pointer-events-none" style="
            background: white;
            background-image: 
                linear-gradient(to right, rgba(0,0,0,0.06) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(0,0,0,0.06) 1px, transparent 1px),
                radial-gradient(circle, rgba(51,65,85,0.4) 1px, transparent 1px);
            background-size: 20px 20px, 20px 20px, 20px 20px;
            background-position: 0 0, 0 0, 0 0;
        "></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-20 reveal-up">
                <div class="inline-flex px-3 py-1 rounded-full bg-[#1E3F35]/15 text-[#1E3F35] text-xs font-bold uppercase tracking-wider mb-6">
                    Dampak Iklim
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#171717] tracking-tight">
                    Mengapa <span class="text-[#1E3F35]">EcoFlow</span> Begitu Penting?
                </h2>
            </div>
            
            <div class="flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12 relative">
                
                <!-- Left Cards -->
                <div class="w-full lg:w-1/3 flex flex-col gap-6 relative z-10">
                    <!-- Card 1 -->
                    <div class="bg-white border border-gray-100 p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover-lift reveal-left relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-[#1E3F35]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 bg-[#F3F4F6] rounded-full flex items-center justify-center mb-6">
                            <span class="text-xl">1</span>
                        </div>
                        <h3 class="font-bold text-[#171717] text-xl mb-3">Menahan Laju Pemanasan</h3>
                        <p class="text-[#555555] leading-relaxed">Setiap gram emisi yang dikurangi membantu bumi bernapas lega. Kita kolektif menjaga suhu permukaan bumi stabil.</p>
                    </div>
                    <!-- Card 2 -->
                    <div class="bg-white border border-gray-100 p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover-lift reveal-left delay-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-[#1E3F35]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751A11.99 11.99 0 0112 2.715z" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 bg-[#F3F4F6] rounded-full flex items-center justify-center mb-6">
                            <span class="text-xl">2</span>
                        </div>
                        <h3 class="font-bold text-[#171717] text-xl mb-3">Mitigasi Risiko Cuaca</h3>
                        <p class="text-[#555555] leading-relaxed">Aksi kecil harianmu membantu mengurangi efek cuaca ekstrem seperti banjir bandang dan badai besar.</p>
                    </div>
                </div>
                
                <!-- Center Image (Globe) -->
                <div class="w-full lg:w-1/3 flex justify-center items-center py-10 lg:py-0 relative z-0 reveal-zoom">
                    <div class="absolute inset-0 bg-[#A3D9A5]/20 rounded-full blur-[80px] animate-pulse"></div>
                    <img src="/images/earth_cartoon.png" class="w-64 h-64 md:w-80 md:h-80 object-contain drop-shadow-2xl relative z-10 animate-[spin_60s_linear_infinite]" alt="Earth Cartoon">
                </div>
 
                <!-- Right Cards -->
                <div class="w-full lg:w-1/3 flex flex-col gap-6 relative z-10">
                    <!-- Card 3 -->
                    <div class="bg-white border border-gray-100 p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover-lift reveal-right relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-[#1E3F35]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.105-7.5 11.25-7.5 11.25S4.5 17.605 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 bg-[#F3F4F6] rounded-full flex items-center justify-center mb-6">
                            <span class="text-xl">3</span>
                        </div>
                        <h3 class="font-bold text-[#171717] text-xl mb-3">Menjamin Sumber Air</h3>
                        <p class="text-[#555555] leading-relaxed">Perubahan iklim merusak siklus hidrologi. Menjaga emisi berarti turut serta melindungi cadangan air bersih.</p>
                    </div>
                    <!-- Card 4 -->
                    <div class="bg-white border border-gray-100 p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover-lift reveal-right delay-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-[#1E3F35]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M12 3C8.5 7 5.5 11 5.5 15c0 3.5 2.5 6 6.5 6m0-18c3.5 4 6.5 8 6.5 12 0 3.5-2.5 6-6.5 6" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 bg-[#F3F4F6] rounded-full flex items-center justify-center mb-6">
                            <span class="text-xl">4</span>
                        </div>
                        <h3 class="font-bold text-[#171717] text-xl mb-3">Meningkatkan Kualitas Udara</h3>
                        <p class="text-[#555555] leading-relaxed">Udara bersih adalah hak. Gaya hidup rendah karbon langsung menurunkan tingkat polusi udara berbahaya.</p>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <!-- Kalkulator Info Section -->
    <section id="kalkulator" class="pt-32 pb-48 bg-[#F4F7F6] relative overflow-hidden">
        <div class="relative z-10 reveal-up max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#171717] tracking-tight mb-4">Mulai Hitung Jejakmu</h2>
                <p class="text-[#555555] max-w-2xl mx-auto text-lg font-medium">Gunakan kalkulator interaktif kami untuk mengetahui estimasi jejak karbon dari aktivitas harianmu.</p>
            </div>
            <!-- Livewire component handles its own layout, but we wrap it for spacing -->
            <livewire:carbon-calculator :isGuestMode="true" />
        </div>
        
        <!-- Bottom Illustrations -->
        <div class="absolute bottom-0 left-0 w-full pointer-events-none reveal-up delay-200 z-0">
            <img src="/images/kids_planting.png" alt="Kids Planting" class="w-full h-auto max-h-[400px] object-cover object-bottom opacity-100">
            <div class="absolute inset-0 bg-gradient-to-t from-[#FAFAFA]/50 to-transparent"></div>
        </div>
    </section>

    <!-- CTA Offset Section -->
    <section id="cta-offset" class="py-32 px-6 lg:px-16 bg-[#FCFAF7] relative overflow-hidden flex flex-col justify-center items-center min-h-[480px]">
        <!-- Gradient Glow -->
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-[600px] h-[250px] bg-gradient-to-t from-amber-100/30 via-amber-50/5 to-transparent rounded-full blur-3xl pointer-events-none z-0"></div>

        <!-- Left Illustration SVG -->
        <svg class="absolute bottom-0 left-0 w-[45%] md:w-[35%] h-auto pointer-events-none z-10" viewBox="0 0 350 250" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Background Hill -->
            <path d="M-50 250 C 50 200, 150 210, 220 250 Z" fill="#4d9b80"/>
            <!-- Foreground Hill -->
            <path d="M-50 250 C 80 160, 180 180, 260 250 Z" fill="#6bcfae"/>
            
            <!-- Purple Flower Stem 1 -->
            <path d="M80 190 Q 75 140 90 90" stroke="#7a349b" stroke-width="3" stroke-linecap="round" fill="none"/>
            <circle cx="90" cy="90" r="8" fill="#c382e7" stroke="#7a349b" stroke-width="2"/>
            <circle cx="85" cy="105" r="7" fill="#c382e7" stroke="#7a349b" stroke-width="2"/>
            <circle cx="80" cy="120" r="7" fill="#c382e7" stroke="#7a349b" stroke-width="2"/>
            <circle cx="78" cy="135" r="6" fill="#c382e7" stroke="#7a349b" stroke-width="2"/>
            <circle cx="78" cy="150" r="6" fill="#c382e7" stroke="#7a349b" stroke-width="2"/>
            
            <!-- Yellow shoots Left -->
            <path d="M40 185 C 40 140, 50 140, 48 185" fill="#f5b93d" stroke="#c48a1d" stroke-width="2"/>
            <path d="M48 185 C 50 130, 60 130, 58 185" fill="#f5b93d" stroke="#c48a1d" stroke-width="2"/>
            <path d="M58 185 C 60 145, 70 145, 68 185" fill="#f5b93d" stroke="#c48a1d" stroke-width="2"/>

            <!-- Large green leaf Left -->
            <path d="M-20 220 C 10 130, 80 180, 30 250 Z" fill="#58b292" stroke="#3b826a" stroke-width="2"/>
            <path d="M-40 230 C -10 120, 60 160, 0 250 Z" fill="#4fa586" stroke="#3b826a" stroke-width="2"/>
            
            <!-- Floating tiny stars -->
            <polygon points="35,110 37,115 42,115 38,118 40,123 35,120 30,123 32,118 28,115 33,115" fill="#f5b93d" />
            <polygon points="120,160 122,163 126,163 123,165 124,169 120,167 116,169 117,165 114,163 118,163" fill="#c382e7" />
        </svg>

        <!-- Right Illustration SVG -->
        <svg class="absolute bottom-0 right-0 w-[45%] md:w-[35%] h-auto pointer-events-none z-10" viewBox="0 0 350 250" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Background Hill -->
            <path d="M150 250 C 220 210, 300 200, 400 250 Z" fill="#4d9b80"/>
            <!-- Foreground Hill -->
            <path d="M100 250 C 180 180, 270 160, 400 250 Z" fill="#6bcfae"/>
            
            <!-- Yellow flower on stem -->
            <path d="M260 180 Q 275 130 250 85" stroke="#bda22a" stroke-width="3" stroke-linecap="round" fill="none"/>
            <circle cx="250" cy="85" r="12" fill="#ffd54f" stroke="#bda22a" stroke-width="2"/>
            <circle cx="250" cy="85" r="5" fill="#f57f17" />
            
            <!-- Blue flower stem -->
            <path d="M220 190 Q 230 140 210 90" stroke="#34759b" stroke-width="3" stroke-linecap="round" fill="none"/>
            <circle cx="210" cy="90" r="7" fill="#80cbc4" stroke="#34759b" stroke-width="2"/>
            <circle cx="213" cy="105" r="7" fill="#80cbc4" stroke="#34759b" stroke-width="2"/>
            <circle cx="218" cy="120" r="7" fill="#80cbc4" stroke="#34759b" stroke-width="2"/>
            <circle cx="220" cy="135" r="6" fill="#80cbc4" stroke="#34759b" stroke-width="2"/>
            
            <!-- Yellow shoots Right -->
            <path d="M290 185 C 290 140, 280 140, 282 185" fill="#f5b93d" stroke="#c48a1d" stroke-width="2"/>
            <path d="M300 185 C 300 130, 290 130, 292 185" fill="#f5b93d" stroke="#c48a1d" stroke-width="2"/>
            
            <!-- Large green leaf Right -->
            <path d="M320 220 C 290 130, 220 180, 270 250 Z" fill="#58b292" stroke="#3b826a" stroke-width="2"/>
            <path d="M340 230 C 310 120, 240 160, 300 250 Z" fill="#4fa586" stroke="#3b826a" stroke-width="2"/>
            
            <!-- Floating tiny stars -->
            <polygon points="310,110 312,113 316,113 313,115 314,119 310,117 306,119 307,115 304,113 308,113" fill="#ffd54f" />
            <polygon points="180,150 182,153 186,153 183,155 184,159 180,157 176,159 177,155 174,153 178,153" fill="#80cbc4" />
        </svg>

        <div class="max-w-4xl mx-auto relative z-20 text-center reveal-up">
            <h2 class="text-4xl md:text-6xl tracking-tight text-[#171717] mb-6 font-light leading-tight">
                Mulai pantau, <span class="font-black border-b-4 border-[#1E3F35] pb-1 block md:inline">kurangi emisimu.</span>
            </h2>
            <p class="text-gray-500 text-base md:text-lg mb-10 leading-relaxed max-w-2xl mx-auto font-medium">
                Lacak emisi karbon harianmu dalam hitungan menit, atau simulasikan dampak lingkunganmu sebelum mengambil aksi nyata bersama EcoFlow.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#kalkulator" class="w-full sm:w-auto inline-flex items-center justify-center bg-[#fbc02d] hover:bg-[#f9a825] active:scale-[0.98] active:translate-y-0.5 text-[#1E3F35] py-3.5 px-8 rounded-full font-bold shadow-md transition-all text-sm uppercase tracking-wider leading-none">
                    Mulai Gratis
                </a>
                <a href="#tentang-kami" class="w-full sm:w-auto inline-flex items-center justify-center bg-[#131722] hover:bg-[#1a1f2c] active:scale-[0.98] active:translate-y-0.5 text-white py-3.5 px-8 rounded-full font-bold shadow-md transition-all text-sm uppercase tracking-wider leading-none">
                    Lihat Program
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-24 px-6 lg:px-16 bg-[#F8F9FA] relative overflow-hidden border-t border-gray-100">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row gap-12 lg:gap-20">
            <!-- Left Side header -->
            <div class="w-full lg:w-1/3 text-left reveal-left">
                <div class="inline-flex px-3 py-1 rounded-full bg-[#1E3F35]/10 text-[#1E3F35] text-xs font-bold uppercase tracking-wider mb-6">
                    Pertanyaan Umum
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-[#111827] mb-6 tracking-tight leading-tight">
                    Memahami Metodologi Kami
                </h2>
                <p class="text-[#4B5563] text-base leading-relaxed font-medium">
                    Kalkulator EcoFlow dirancang agar transparan dan berbasis sains. Hubungi kami jika Anda memiliki pertanyaan teknis lebih lanjut.
                </p>
            </div>

            <!-- Right Side Accordion -->
            <div class="w-full lg:w-2/3 reveal-right flex flex-col divide-y divide-gray-100">
                <!-- FAQ Item 1 -->
                <div class="py-5">
                    <button class="faq-trigger w-full flex justify-between items-center text-left font-bold text-lg text-[#111827] py-2 focus:outline-none transition-colors hover:text-[#1E3F35]">
                        <span>Bagaimana EcoFlow menghitung jejak karbon saya?</span>
                        <svg class="faq-icon w-5 h-5 text-[#4B5563] transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-panel max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out">
                        <p class="text-[#4B5563] text-sm md:text-base leading-relaxed pt-2 pb-4 font-medium">
                            Kalkulator kami menggunakan parameter ilmiah dari <strong>IPCC 2006/2019 Guidelines for National Greenhouse Gas Inventories</strong>. Kami mengalikan jumlah konsumsi harian Anda dengan faktor emisi lokal seperti data Kementerian ESDM RI untuk emisi listrik PLN (~0.87 kg CO₂/kWh) serta riset Poore & Nemecek (2018) untuk emisi siklus hidup bahan makanan.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 2 -->
                <div class="py-5">
                    <button class="faq-trigger w-full flex justify-between items-center text-left font-bold text-lg text-[#111827] py-2 focus:outline-none transition-colors hover:text-[#1E3F35]">
                        <span>Apakah data aktivitas yang saya masukkan aman?</span>
                        <svg class="faq-icon w-5 h-5 text-[#4B5563] transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-panel max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out">
                        <p class="text-[#4B5563] text-sm md:text-base leading-relaxed pt-2 pb-4 font-medium">
                            Tentu saja. Perhitungan kalkulator karbon berjalan secara lokal di peramban Anda. Kami hanya menyimpan ringkasan emisi di database kami jika Anda terdaftar dan masuk ke akun Anda, semata-mata untuk menampilkan bagan tren perkembangan emisi Anda dari waktu ke waktu.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 3 -->
                <div class="py-5">
                    <button class="faq-trigger w-full flex justify-between items-center text-left font-bold text-lg text-[#111827] py-2 focus:outline-none transition-colors hover:text-[#1E3F35]">
                        <span>Mengapa emisi dari makanan hewani cenderung sangat tinggi?</span>
                        <svg class="faq-icon w-5 h-5 text-[#4B5563] transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-panel max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out">
                        <p class="text-[#4B5563] text-sm md:text-base leading-relaxed pt-2 pb-4 font-medium">
                            Daging sapi dan produk ternak ruminansia menghasilkan gas metana (CH₄) melalui proses fermentasi enterik selama pencernaan mereka. Metana memiliki dampak pemanasan global 28 kali lebih kuat dibanding karbon dioksida (CO₂) dalam jangka 100 tahun. Selain itu, budidaya ternak juga membutuhkan konversi lahan hutan yang signifikan, yang melipatgandakan emisi karbonnya.
                        </p>
                    </div>
                </div>

                <!-- FAQ Item 4 -->
                <div class="py-5">
                    <button class="faq-trigger w-full flex justify-between items-center text-left font-bold text-lg text-[#111827] py-2 focus:outline-none transition-colors hover:text-[#1E3F35]">
                        <span>Bagaimana cara kerja Carbon Offsetting di platform ini?</span>
                        <svg class="faq-icon w-5 h-5 text-[#4B5563] transition-transform duration-300 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div class="faq-panel max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out">
                        <p class="text-[#4B5563] text-sm md:text-base leading-relaxed pt-2 pb-4 font-medium">
                            Setiap pohon mangrove yang Anda kompensasikan ditanam oleh mitra konservasi lokal bersertifikat kami di Indonesia. Kami merekam titik koordinat pohon, foto pemantauan pertumbuhan berkala, dan mengonversikan potensi serapan karbonnya untuk dikreditkan langsung ke profil pelacakan karbon Anda di dashboard EcoFlow.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Animated Footer Section -->
    <section class="relative w-full mt-0 overflow-hidden">
        <footer class="border-t bg-[#FAFAFA] mt-20 relative">
            <div class="max-w-7xl flex flex-col justify-between mx-auto min-h-[30rem] sm:min-h-[35rem] md:min-h-[40rem] relative p-4 py-10">
                
                <!-- Main Content -->
                <div class="flex flex-col mb-12 sm:mb-20 md:mb-0 w-full relative z-20">
                    <div class="w-full flex flex-col items-center">
                        <div class="space-y-2 flex flex-col items-center flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-[#1E3F35] text-3xl sm:text-4xl font-extrabold tracking-tight">
                                    EcoFlow
                                </span>
                            </div>
                            <p class="text-[#555555] font-semibold text-center w-full max-w-sm sm:w-96 px-4 sm:px-0 mt-2">
                                Platform analisis dan mitigasi perubahan iklim terpadu. Membantu menghitung, mengurangi, dan mengimbangi jejak karbon harian Anda.
                            </p>
                        </div>

                        <!-- Social Links -->
                        <div class="flex mb-8 mt-6 gap-5">
                            <!-- Twitter -->
                            <a href="https://twitter.com" class="text-gray-400 hover:text-[#1E3F35] transition-colors" target="_blank" rel="noopener noreferrer">
                                <div class="w-6 h-6 hover:scale-110 duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                </div>
                                <span class="sr-only">Twitter</span>
                            </a>
                            <!-- LinkedIn -->
                            <a href="https://linkedin.com" class="text-gray-400 hover:text-[#1E3F35] transition-colors" target="_blank" rel="noopener noreferrer">
                                <div class="w-6 h-6 hover:scale-110 duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </div>
                                <span class="sr-only">LinkedIn</span>
                            </a>
                            <!-- GitHub -->
                            <a href="https://github.com" class="text-gray-400 hover:text-[#1E3F35] transition-colors" target="_blank" rel="noopener noreferrer">
                                <div class="w-6 h-6 hover:scale-110 duration-300">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482C19.138 20.193 22 16.44 22 12.017 22 6.484 17.522 2 12 2z"/></svg>
                                </div>
                                <span class="sr-only">GitHub</span>
                            </a>
                            <!-- Email -->
                            <a href="mailto:hello@ecoflow.id" class="text-gray-400 hover:text-[#1E3F35] transition-colors">
                                <div class="w-6 h-6 hover:scale-110 duration-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><path d="M22 6l-10 7L2 6"/></svg>
                                </div>
                                <span class="sr-only">Email</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="flex flex-wrap justify-center gap-6 text-sm font-semibold text-gray-500 max-w-full px-4">
                            <a href="#beranda" class="hover:text-[#1E3F35] duration-300 hover:font-bold">Beranda</a>
                            <a href="#tentang-kami" class="hover:text-[#1E3F35] duration-300 hover:font-bold">Tentang Kami</a>
                            <a href="#kalkulator" class="hover:text-[#1E3F35] duration-300 hover:font-bold">Kalkulator</a>
                            <a href="#cta-offset" class="hover:text-[#1E3F35] duration-300 hover:font-bold">Aksi Hijau</a>
                            <a href="#faq" class="hover:text-[#1E3F35] duration-300 hover:font-bold">FAQ</a>
                        </div>
                    </div>
                </div>

                <!-- Copyright Area -->
                <div class="mt-20 md:mt-24 flex flex-col gap-4 items-center justify-center md:flex-row md:items-center md:justify-between px-4 md:px-0 relative z-20 border-t border-gray-200/50 pt-8 text-sm">
                    <p class="text-gray-400 text-center md:text-left">
                        ©{{ date('Y') }} EcoFlow. All rights reserved.
                    </p>
                    <div class="flex flex-col md:flex-row gap-4 md:gap-8 items-center">
                        <nav class="flex gap-6">
                            <a href="#" class="text-sm text-gray-400 hover:text-[#1E3F35] transition-colors duration-300 font-medium">
                                Kebijakan Privasi
                            </a>
                            <a href="#" class="text-sm text-gray-400 hover:text-[#1E3F35] transition-colors duration-300 font-medium">
                                Syarat & Ketentuan
                            </a>
                        </nav>
                       
                    </div>
                </div>
            </div>

            <!-- Large Background Text (ECOFLOW) -->
            <div 
                class="bg-gradient-to-b from-[#1E3F35]/15 via-[#1E3F35]/5 to-transparent bg-clip-text text-transparent leading-none absolute left-1/2 -translate-x-1/2 bottom-40 md:bottom-32 font-extrabold tracking-tighter pointer-events-none select-none text-center px-4"
                style="font-size: clamp(3rem, 12vw, 10rem); max-width: 95vw;"
            >
                ECOFLOW
            </div>



            <!-- Bottom Line divider -->
            <div class="absolute bottom-32 sm:bottom-34 backdrop-blur-sm h-1 bg-gradient-to-r from-transparent via-gray-200/60 to-transparent w-full left-1/2 -translate-x-1/2"></div>

            <!-- Bottom Shadow / fade out -->
            <div class="bg-gradient-to-t from-[#FAFAFA] via-[#FAFAFA]/80 blur-[1em] to-[#FAFAFA]/40 absolute bottom-28 w-full h-24 pointer-events-none"></div>
        </footer>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Scroll Animation Observer (Reveal elements)
            const revealOptions = { threshold: 0.1, rootMargin: "0px 0px -50px 0px" };
            const revealObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, revealOptions);

            const revealElements = document.querySelectorAll('.reveal-up, .reveal-left, .reveal-right, .reveal-zoom');
            revealElements.forEach(el => revealObserver.observe(el));

            // Scroll spy for navbar active state
            const sections = document.querySelectorAll('#beranda, #tentang-kami, #kalkulator, #cta-offset, #faq');
            const navLinks = document.querySelectorAll('#nav-links .nav-link');
            
            const activeClasses = ['text-[#1E3F35]', 'relative', 'after:absolute', 'after:bottom-[-4px]', 'after:left-0', 'after:w-full', 'after:h-[2px]', 'after:bg-[#A3D9A5]', 'after:rounded-full'];
            
            function changeActiveLink(id) {
                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href === `#${id}`) {
                        link.classList.add(...activeClasses);
                        link.classList.remove('hover:text-[#1E3F35]', 'transition-colors');
                    } else {
                        link.classList.remove(...activeClasses);
                        link.classList.add('hover:text-[#1E3F35]', 'transition-colors');
                    }
                });
            }
            
            const observerOptions = {
                root: null,
                rootMargin: '-20% 0px -40% 0px',
                threshold: 0.1
            };
            
            const sectionObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        changeActiveLink(entry.target.id);
                    }
                });
            }, observerOptions);
            
            sections.forEach(section => {
                if (section) sectionObserver.observe(section);
            });

            // Scroll Expansion Hero & Navbar Reveal Logic
            const heroMediaContainer = document.getElementById('hero-media-container');
            const heroBgImage = document.getElementById('hero-bg-image');
            const titleFirst = document.getElementById('hero-title-first');
            const titleRest = document.getElementById('hero-title-rest');
            const scrollMarker = document.getElementById('hero-scroll-marker');
            const expandedContent = document.getElementById('hero-expanded-content');
            
            const navbarContainer = document.getElementById('floating-navbar-container');
            const navbar = document.getElementById('floating-navbar');
            const navbarContent = document.getElementById('navbar-content');

            function handleScrollExpand() {
                if (!heroMediaContainer) return;
                
                const progress = Math.min(window.scrollY / window.innerHeight, 1);
                const isMobile = window.innerWidth < 768;
                const startWidth = isMobile ? 240 : 350;
                const startHeight = isMobile ? 320 : 450;
                const endWidth = window.innerWidth;
                const endHeight = window.innerHeight;

                const currentWidth = startWidth + progress * (endWidth - startWidth);
                const currentHeight = startHeight + progress * (endHeight - startHeight);
                const currentRadius = 24 - progress * 24;

                // 1. Update media container size & border radius
                heroMediaContainer.style.width = currentWidth + 'px';
                heroMediaContainer.style.height = currentHeight + 'px';
                heroMediaContainer.style.borderRadius = currentRadius + 'px';

                // 2. Background image opacity fade out
                if (heroBgImage) {
                    heroBgImage.style.opacity = 1 - progress;
                }

                // 3. Titles slide out horizontally
                const textTranslateX = progress * 150;
                if (titleFirst) {
                    titleFirst.style.transform = 'translateX(-' + textTranslateX + 'vw)';
                }
                if (titleRest) {
                    titleRest.style.transform = 'translateX(' + textTranslateX + 'vw)';
                }

                // 4. Scroll marker fade out
                if (scrollMarker) {
                    scrollMarker.style.opacity = Math.max(0, 1 - progress * 2.5);
                }

                // 5. Fade-in expanded content
                if (expandedContent) {
                    if (progress >= 0.95) {
                        expandedContent.classList.remove('opacity-0', 'pointer-events-none');
                        expandedContent.classList.add('opacity-100');
                    } else {
                        expandedContent.classList.remove('opacity-100');
                        expandedContent.classList.add('opacity-0', 'pointer-events-none');
                    }
                }

                // 6. Navbar Transition (Center-out expansion)
                if (navbarContainer && navbar && navbarContent) {
                    if (progress >= 0.98) {
                        // Reveal container
                        navbarContainer.classList.remove('opacity-0', '-translate-y-12', 'scale-95', 'pointer-events-none');
                        
                        // Expand navbar horizontally from center
                        navbar.style.width = '100%';
                        navbar.style.maxWidth = '72rem'; // max-w-6xl is 72rem (1152px)
                        navbar.classList.remove('w-[60px]', 'opacity-0');
                        
                        // Fade in inner content
                        setTimeout(() => {
                            const currentProgress = Math.min(window.scrollY / window.innerHeight, 1);
                            if (currentProgress >= 0.98) {
                                navbarContent.classList.remove('opacity-0', 'pointer-events-none');
                                navbarContent.classList.add('opacity-100');
                            }
                        }, 200);
                    } else {
                        // Immediately hide content to prevent overlap
                        navbarContent.classList.remove('opacity-100');
                        navbarContent.classList.add('opacity-0', 'pointer-events-none');
                        
                        // Shrink navbar back to narrow state
                        navbar.style.width = '60px';
                        navbar.style.maxWidth = '';
                        navbar.classList.add('opacity-0');
                        
                        // Hide container
                        navbarContainer.classList.add('opacity-0', '-translate-y-12', 'scale-95', 'pointer-events-none');
                    }
                }
            }

            window.addEventListener('scroll', () => {
                if (window.scrollY === 0) {
                    changeActiveLink('beranda');
                }
                handleScrollExpand();
            });

            window.addEventListener('resize', handleScrollExpand);
            
            // Initial call to set positions on page load
            handleScrollExpand();

            // Slider Logic
            const slides = document.getElementById('slides');
            const dots = document.querySelectorAll('.slider-dot');
            let currentSlide = 0;
            const totalSlides = 3;

            function updateSlider(index) {
                if(!slides) return;
                currentSlide = (index + totalSlides) % totalSlides;
                slides.style.transform = `translateX(-${currentSlide * (100 / totalSlides)}%)`;
                
                dots.forEach((dot, i) => {
                    if(i === currentSlide) {
                        dot.classList.add('bg-[#62F369]', 'w-6');
                        dot.classList.remove('bg-white/50', 'w-2');
                    } else {
                        dot.classList.add('bg-white/50', 'w-2');
                        dot.classList.remove('bg-[#62F369]', 'w-6');
                    }
                });
            }

            document.getElementById('slider-prev')?.addEventListener('click', () => updateSlider(currentSlide - 1));
            document.getElementById('slider-next')?.addEventListener('click', () => updateSlider(currentSlide + 1));
            
            dots.forEach(dot => {
                dot.addEventListener('click', (e) => {
                    updateSlider(parseInt(e.target.dataset.idx));
                });
            });

            // Auto slide
            if(slides) {
                setInterval(() => updateSlider(currentSlide + 1), 6000);
            }

            // Custom Cursor Logic
            if (window.matchMedia("(min-width: 768px)").matches) {
                const cursor = document.getElementById('custom-cursor');
                const cursorLabel = document.getElementById('cursor-label');
                const cursorArrow = document.getElementById('cursor-svg');
                const cursorHand = document.getElementById('cursor-hand');
                
                const labels = [
                    "Sustainability Hero",
                    "Eco Warrior",
                    "Climate Champion",
                    "Green Guardian",
                    "Earth Protector",
                    "Carbon Saver"
                ];
                
                // Random label on load
                cursorLabel.textContent = labels[Math.floor(Math.random() * labels.length)];
                
                let mouseX = 0;
                let mouseY = 0;
                
                document.addEventListener('mousemove', (e) => {
                    mouseX = e.clientX;
                    mouseY = e.clientY;
                    
                    // Immediately move the main cursor and label container
                    cursor.style.transform = `translate(${mouseX}px, ${mouseY}px)`;
                    
                    // Check if hovering over any interactive element (buttons, links, or elements with cursor pointer)
                    const hoverTarget = e.target.closest('a, button, [role="button"], .cursor-pointer, input[type="submit"], input[type="button"], select, .faq-trigger, [onclick]');
                    if (hoverTarget) {
                        cursorArrow.classList.add('hidden');
                        cursorHand.classList.remove('hidden');
                    } else {
                        cursorArrow.classList.remove('hidden');
                        cursorHand.classList.add('hidden');
                    }
                });

                document.addEventListener('mousedown', (e) => {
                    cursorLabel.classList.add('scale-95');
                    
                    // Droplet effect
                    const droplet = document.createElement('div');
                    droplet.className = 'droplet-effect';
                    droplet.style.left = `${e.clientX}px`;
                    droplet.style.top = `${e.clientY}px`;
                    document.body.appendChild(droplet);
                    
                    setTimeout(() => droplet.remove(), 400);
                });

                document.addEventListener('mouseup', () => {
                    cursorLabel.classList.remove('scale-95');
                });
            }

            // FAQ Accordion Logic
            const faqTriggers = document.querySelectorAll('.faq-trigger');
            faqTriggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const panel = trigger.nextElementSibling;
                    const icon = trigger.querySelector('.faq-icon');
                    
                    // Toggle current panel
                    if (panel.style.maxHeight) {
                        panel.style.maxHeight = null;
                        panel.style.opacity = '0';
                        icon?.classList.remove('rotate-180');
                        trigger.classList.remove('text-[#1E3F35]');
                        trigger.classList.add('text-[#111827]');
                    } else {
                        // Close other panels first
                        document.querySelectorAll('.faq-panel').forEach(p => {
                            p.style.maxHeight = null;
                            p.style.opacity = '0';
                            p.previousElementSibling.querySelector('.faq-icon')?.classList.remove('rotate-180');
                            p.previousElementSibling.classList.remove('text-[#1E3F35]');
                            p.previousElementSibling.classList.add('text-[#111827]');
                        });
                        
                        panel.style.maxHeight = panel.scrollHeight + "px";
                        panel.style.opacity = '1';
                        icon?.classList.add('rotate-180');
                        trigger.classList.remove('text-[#111827]');
                        trigger.classList.add('text-[#1E3F35]');
                    }
                });
            });
        });
    </script>

    <!-- Custom Cursor HTML -->
    <div id="custom-cursor" class="fixed top-0 left-0 pointer-events-none z-[9999] hidden md:block">
        <div class="relative">
            <!-- Arrow SVG -->
            <svg id="cursor-svg" class="absolute w-[28px] h-[29px] drop-shadow-md z-10 transition-transform duration-100 origin-top-left" style="top: 0; left: 0;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1 L16 7 L9 9 L6 17 Z" fill="#12A150" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
            </svg>
            
            <!-- Hand SVG -->
            <svg id="cursor-hand" class="absolute w-[30px] h-[30px] drop-shadow-md z-10 transition-transform duration-100 origin-top-left hidden" style="top: 0; left: -6.75px; transform: rotate(-20deg); transform-origin: 6.75px 0px;" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8.5 4.466V1.75a1.75 1.75 0 1 0-3.5 0v5.34l-1.2.24a1.5 1.5 0 0 0-1.196 1.636l.345 3.106a2.5 2.5 0 0 0 .405 1.11l1.433 2.15A1.5 1.5 0 0 0 6.035 16h6.385a1.5 1.5 0 0 0 1.302-.756l1.395-2.441a3.5 3.5 0 0 0 .444-1.389l.271-2.715a2 2 0 0 0-1.99-2.199h-.581a5 5 0 0 0-.195-.248c-.191-.229-.51-.568-.88-.716-.364-.146-.846-.132-1.158-.108l-.132.012a1.26 1.26 0 0 0-.56-.642 2.6 2.6 0 0 0-.738-.288c-.31-.062-.739-.058-1.05-.046zm2.094 2.025" fill="white" stroke="#12A150" stroke-width="1.2" stroke-linejoin="round"/>
                <!-- Three vertical stripes on the palm -->
                <line x1="6.8" y1="9.5" x2="6.8" y2="12.5" stroke="#12A150" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="8.3" y1="9.5" x2="8.3" y2="12.5" stroke="#12A150" stroke-width="1.2" stroke-linecap="round"/>
                <line x1="9.8" y1="9.5" x2="9.8" y2="12.5" stroke="#12A150" stroke-width="1.2" stroke-linecap="round"/>
            </svg>
            
            <!-- Label (Ribbon) -->
            <div id="cursor-label" class="absolute top-6 left-5 h-[25px] flex items-center justify-center px-3 bg-[#12A150] text-white text-xs font-bold rounded-lg whitespace-nowrap shadow-md transition-transform duration-100 origin-top-left">
                Sustainability Hero
            </div>
        </div>
    </div>
</body>
</html>
