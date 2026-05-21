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
    <div class="fixed top-6 left-0 right-0 z-50 px-4 md:px-0 flex justify-center pointer-events-none">
        <nav class="w-full max-w-6xl bg-white/95 backdrop-blur-md shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-gray-100 rounded-full px-6 py-3 flex items-center justify-between pointer-events-auto transition-all">
            <div class="flex items-center gap-2 cursor-pointer hover:opacity-80 transition-opacity">
                <!-- Logo Icon -->
                <svg class="w-8 h-8 text-[#1A4D2E]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M8 12C8 12 9 8 12 8C15 8 16 12 16 12C16 12 15 16 12 16C9 16 8 12 8 12Z" fill="currentColor"/>
                </svg>
                <span class="text-xl font-bold tracking-tight text-[#171717]">EcoFlow</span>
            </div>
            
            <div id="nav-links" class="hidden md:flex items-center gap-8 text-sm font-semibold text-[#555555]">
                <a href="#beranda" class="nav-link text-[#1A4D2E] relative after:absolute after:bottom-[-4px] after:left-0 after:w-full after:h-[2px] after:bg-[#62F369] after:rounded-full">Beranda</a>
                <a href="#tentang-kami" class="nav-link hover:text-[#1A4D2E] transition-colors">Tentang Kami</a>
                <a href="#kalkulator" class="nav-link hover:text-[#1A4D2E] transition-colors">Kalkulator</a>
                <a href="#product-duel" class="nav-link hover:text-[#1A4D2E] transition-colors">Product duel</a>
            </div>
            
            <div>
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-[#171717] hover:bg-[#62F369] hover:text-[#171717] text-white px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="bg-[#171717] hover:bg-[#62F369] hover:text-[#171717] text-white px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300">Sign In</a>
                @endauth
            </div>
        </nav>
    </div>

    <!-- Hero Section -->
    <section id="beranda" class="relative pt-40 pb-24 md:pt-48 md:pb-32 px-6 lg:px-16 overflow-hidden bg-white">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full bg-[#62F369]/10 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] rounded-full bg-[#1A4D2E]/5 blur-[80px] pointer-events-none"></div>
        <div class="absolute inset-0 bg-dots opacity-40 pointer-events-none"></div>

        <div class="relative z-10 max-w-5xl mx-auto text-center reveal-up">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#F3F4F6] border border-gray-200 text-xs font-bold text-[#555555] mb-8 uppercase tracking-widest">
                <span class="w-2 h-2 rounded-full bg-[#62F369] animate-pulse"></span>
                Standar Emisi IPCC
            </div>
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-[#171717] leading-[1.15] mb-8 tracking-tight">
                Langkah Kecil untuk<br>
                <span class="relative">
                    Jejak yang Lebih <span class="text-[#1A4D2E] relative z-10">Hijau.</span>
                    <svg class="absolute w-full h-4 -bottom-1 left-0 text-[#62F369] -z-10" viewBox="0 0 100 20" preserveAspectRatio="none"><path d="M0 15 Q 50 0 100 15" fill="none" stroke="currentColor" stroke-width="8" stroke-linecap="round"/></svg>
                </span>
            </h1>
            <p class="text-lg md:text-xl text-[#555555] mb-12 leading-relaxed max-w-3xl mx-auto font-medium">
                Pantau dan kurangi emisi karbon harianmu dengan kalkulator cerdas berbasis standar IPCC. Aksi nyata untuk mitigasi perubahan iklim dimulai dari genggamanmu.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="#kalkulator" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-[#62F369] text-[#171717] hover:bg-[#4CE454] active:scale-[0.98] active:translate-y-0 px-8 py-4 rounded-full font-bold transition-all text-lg hover-lift">
                    Hitung Jejak Karbonmu
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
        
        <!-- Dashboard Mockup Image Reveal -->
        <div class="relative z-10 max-w-6xl mx-auto mt-20 reveal-up delay-200">
            <div class="rounded-[24px] overflow-hidden shadow-[0_20px_50px_rgb(0,0,0,0.1)] border border-gray-100 bg-white">
                <img src="/images/hero_forest.png" class="w-full h-[400px] md:h-[500px] object-cover" alt="EcoFlow Dashboard">
            </div>
        </div>
    </section>

    <!-- Mengapa EcoFlow Section -->
    <section id="tentang-kami" class="py-32 px-6 lg:px-16 bg-[#FAFAFA] overflow-hidden">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16 lg:gap-24">
            
            <!-- Left Text -->
            <div class="w-full md:w-1/2 reveal-left">
                <div class="inline-flex px-3 py-1 rounded-full bg-[#E0FBE1] text-[#1A4D2E] text-xs font-bold uppercase tracking-wider mb-6">
                    Tentang Platform
                </div>
                <h2 class="text-4xl lg:text-5xl font-extrabold text-[#171717] mb-6 tracking-tight leading-tight">
                    Mengapa <span class="text-[#1A4D2E]">EcoFlow?</span>
                </h2>
                <p class="text-xl text-[#555555] leading-relaxed font-medium mb-8">
                    Kami menghadirkan solusi digital untuk membantu kamu memahami dampak aktivitas harian terhadap lingkungan dan mengambil aksi nyata bagi iklim.
                </p>
                <div class="flex gap-2 mb-8">
                    <div class="w-12 h-1.5 bg-[#62F369] rounded-full"></div>
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
                <button id="slider-prev" class="absolute left-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur hover:bg-[#62F369] rounded-full flex items-center justify-center text-[#171717] shadow-xl transition-all transform hover:scale-110 opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <button id="slider-next" class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/90 backdrop-blur hover:bg-[#62F369] rounded-full flex items-center justify-center text-[#171717] shadow-xl transition-all transform hover:scale-110 opacity-0 group-hover:opacity-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                </button>
                
                <!-- Dots overlay -->
                <div class="absolute bottom-8 right-8 flex gap-2" id="slider-dots">
                    <button class="h-2 rounded-full bg-[#62F369] w-6 transition-all slider-dot" data-idx="0"></button>
                    <button class="w-2 h-2 rounded-full bg-white/50 transition-all slider-dot" data-idx="1"></button>
                    <button class="w-2 h-2 rounded-full bg-white/50 transition-all slider-dot" data-idx="2"></button>
                </div>
            </div>
            
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-32 px-6 lg:px-16 bg-[#171717] text-white text-center relative overflow-hidden">
        <!-- Abstract Dark Shapes -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-[#1A4D2E]/30 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-[#62F369]/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="inline-flex px-3 py-1 rounded-full bg-white/10 text-[#62F369] text-xs font-bold uppercase tracking-wider mb-6 reveal-up border border-white/10">
                Fitur Utama
            </div>
            <h2 class="text-4xl md:text-5xl font-extrabold mb-6 reveal-up">Fitur Andal untuk Aksi Hijau</h2>
            <p class="text-gray-400 mb-20 max-w-2xl mx-auto text-lg reveal-up delay-100 font-medium">Platform komprehensif yang didesain secara khusus untuk memudahkan kamu dalam mengelola, memantau, dan menurunkan jejak karbon.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-[#222222] border border-gray-800 p-10 rounded-[24px] text-left hover-lift reveal-up delay-100 transition-all duration-300">
                    <div class="w-14 h-14 bg-[#1A4D2E] rounded-[16px] mb-8 flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#62F369]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Kalkulasi Presisi</h3>
                    <p class="text-gray-400 mb-8 leading-relaxed">Hitung emisi CO2e dari transportasi hingga konsumsi makanan secara akurat menggunakan metodologi IPCC yang diakui dunia.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="bg-[#222222] border border-gray-800 p-10 rounded-[24px] text-left hover-lift reveal-up delay-200 transition-all duration-300">
                    <div class="w-14 h-14 bg-[#1A4D2E] rounded-[16px] mb-8 flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#62F369]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Analitik Personal</h3>
                    <p class="text-gray-400 mb-8 leading-relaxed">Pantau riwayat emisi mingguan dan bulanan kamu melalui dashboard bento-grid yang dirancang untuk memudahkan pemantauan progres.</p>
                </div>
                
                <!-- Feature 3 -->
                <div class="bg-[#222222] border border-gray-800 p-10 rounded-[24px] text-left hover-lift reveal-up delay-300 transition-all duration-300">
                    <div class="w-14 h-14 bg-[#1A4D2E] rounded-[16px] mb-8 flex items-center justify-center">
                        <svg class="w-7 h-7 text-[#62F369]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Aksi & Penghargaan</h3>
                    <p class="text-gray-400 mb-8 leading-relaxed">Dapatkan tips ramah lingkungan yang dipersonalisasi dan raih badge eksklusif sebagai apresiasi atas kontribusi hijaumu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mengapa EcoFlow Begitu Penting? Section -->
    <section class="py-32 px-6 lg:px-16 bg-white overflow-hidden relative">
        <div class="absolute inset-0 bg-dots opacity-30 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto relative z-10">
            <div class="text-center mb-20 reveal-up">
                <div class="inline-flex px-3 py-1 rounded-full bg-[#E0FBE1] text-[#1A4D2E] text-xs font-bold uppercase tracking-wider mb-6">
                    Dampak Iklim
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-[#171717] tracking-tight">
                    Mengapa <span class="text-[#1A4D2E]">EcoFlow</span> Begitu Penting?
                </h2>
            </div>
            
            <div class="flex flex-col lg:flex-row items-center justify-center gap-8 lg:gap-12 relative">
                
                <!-- Left Cards -->
                <div class="w-full lg:w-1/3 flex flex-col gap-6 relative z-10">
                    <!-- Card 1 -->
                    <div class="bg-white border border-gray-100 p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover-lift reveal-left relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-[#1A4D2E]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
                            <svg class="w-16 h-16 text-[#1A4D2E]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
                    <div class="absolute inset-0 bg-[#62F369]/20 rounded-full blur-[80px] animate-pulse"></div>
                    <img src="/images/earth_cartoon.png" class="w-64 h-64 md:w-80 md:h-80 object-contain drop-shadow-2xl relative z-10 animate-[spin_60s_linear_infinite]" alt="Earth Cartoon">
                </div>

                <!-- Right Cards -->
                <div class="w-full lg:w-1/3 flex flex-col gap-6 relative z-10">
                    <!-- Card 3 -->
                    <div class="bg-white border border-gray-100 p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover-lift reveal-right relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-16 h-16 text-[#1A4D2E]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
                            <svg class="w-16 h-16 text-[#1A4D2E]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
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
    <section id="kalkulator" class="pt-32 pb-48 bg-[#FAFAFA] relative overflow-hidden">
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

    <!-- Footer -->
    <footer class="bg-white pt-20 pb-8 px-6 lg:px-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between gap-12 mb-16">
            <!-- Footer Logo & Info -->
            <div class="w-full md:w-1/3 pr-8">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-8 h-8 text-[#1A4D2E]" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="currentColor" stroke-width="2"/>
                        <path d="M12 16V12M12 8H12.01" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <path d="M8 12C8 12 9 8 12 8C15 8 16 12 16 12C16 12 15 16 12 16C9 16 8 12 8 12Z" fill="currentColor"/>
                    </svg>
                    <span class="text-2xl font-extrabold text-[#171717]">EcoFlow</span>
                </div>
                <p class="text-[#555555] mb-8 leading-relaxed">Platform mitigasi perubahan iklim terpadu untuk individu dan perusahaan. Membantu menghitung, mengurangi, dan mengimbangi jejak karbon.</p>
                <div class="flex gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-[#171717] hover:bg-[#62F369] hover:border-[#62F369] transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-gray-50 border border-gray-200 flex items-center justify-center text-[#171717] hover:bg-[#62F369] hover:border-[#62F369] transition-all"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg></a>
                </div>
            </div>
            
            <div class="w-full md:w-1/4">
                <h4 class="font-bold text-[#171717] mb-6">Kontak</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Telepon</p>
                        <a href="#" class="text-[#1A4D2E] font-bold hover:underline">1-800-ECO-FLOW</a>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Email</p>
                        <a href="#" class="text-[#1A4D2E] font-bold hover:underline">hello@ecoflow.id</a>
                    </div>
                </div>
            </div>
            
            <div class="w-full md:w-1/3">
                <h4 class="font-bold text-[#171717] mb-6">Berlangganan Newsletter</h4>
                <p class="text-[#555555] mb-6 leading-relaxed">Dapatkan info terbaru seputar iklim, fitur baru, dan tips hijau langsung di inbox kamu.</p>
                <form class="flex gap-2">
                    <input type="email" placeholder="Email Anda" class="flex-1 bg-gray-50 border border-gray-200 rounded-[12px] px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#62F369]">
                    <button type="submit" class="bg-[#171717] hover:bg-[#62F369] hover:text-[#171717] text-white px-6 py-3 rounded-[12px] text-sm font-bold transition-all">Submit</button>
                </form>
            </div>
        </div>
        
        <div class="text-center pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center text-sm text-[#555555]">
            <p>&copy; {{ date('Y') }} EcoFlow. All rights reserved.</p>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-[#1A4D2E]">Kebijakan Privasi</a>
                <a href="#" class="hover:text-[#1A4D2E]">Syarat & Ketentuan</a>
            </div>
        </div>
    </footer>

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
            const sections = document.querySelectorAll('#beranda, #tentang-kami, #kalkulator, #product-duel');
            const navLinks = document.querySelectorAll('#nav-links .nav-link');
            
            const activeClasses = ['text-[#1A4D2E]', 'relative', 'after:absolute', 'after:bottom-[-4px]', 'after:left-0', 'after:w-full', 'after:h-[2px]', 'after:bg-[#62F369]', 'after:rounded-full'];
            
            function changeActiveLink(id) {
                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href === `#${id}`) {
                        link.classList.add(...activeClasses);
                        link.classList.remove('hover:text-[#1A4D2E]', 'transition-colors');
                    } else {
                        link.classList.remove(...activeClasses);
                        link.classList.add('hover:text-[#1A4D2E]', 'transition-colors');
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

            window.addEventListener('scroll', () => {
                if (window.scrollY === 0) {
                    changeActiveLink('beranda');
                }
            });

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
        });
    </script>

    <!-- Custom Cursor HTML -->
    <div id="custom-cursor" class="fixed top-0 left-0 pointer-events-none z-[9999] hidden md:block">
        <div class="relative">
            <!-- Arrow SVG -->
            <svg id="cursor-svg" class="absolute w-10 h-10 drop-shadow-md z-10 transition-transform duration-100 origin-top-left" style="top: 0; left: 0;" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 1 L16 7 L9 9 L6 17 Z" fill="#12A150" stroke="white" stroke-width="1.5" stroke-linejoin="round"/>
            </svg>
            
            <!-- Label -->
            <div id="cursor-label" class="absolute top-8 left-6 px-5 py-2.5 bg-[#12A150] text-white text-sm font-bold rounded-xl whitespace-nowrap shadow-md transition-transform duration-100 origin-top-left">
                Sustainability Hero
            </div>
        </div>
    </div>
</body>
</html>
