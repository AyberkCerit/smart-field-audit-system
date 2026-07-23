<!DOCTYPE html>
<html class="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Saha Denetim Sistemi') }}</title>
    
    <!-- Tailwind CDN with Plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- Three.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- Tailwind Config -->
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-white min-h-screen flex flex-col">

<main class="flex-grow flex flex-col md:flex-row overflow-hidden relative">
    
    <!-- Left Section: 3D Animation & Welcome Text -->
    <section class="flex-grow hidden md:flex md:w-[65%] p-4 md:p-8 items-center justify-center relative">
        <div class="w-full h-full rounded-3xl bg-neutral-900 overflow-hidden relative border border-neutral-800">
            <div class="canvas-container opacity-60" id="canvas-container"></div>
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center p-6">
                <div class="docs-demo-html w-full max-w-md mx-auto text-white mb-4">
                <svg viewBox="0 0 440 112">
    <g stroke="currentColor" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke-width="4">
      <!-- W -->
      <polyline class="line" points="10 22, 25 90, 42 45, 59 90, 74 22"/>
      <!-- E -->
      <path class="line" d="M125 22H90v68h35 M90 56h30"/>
      <!-- L -->
      <polyline class="line" points="145 22, 145 90, 180 90"/>
      <!-- C -->
      <path class="line" d="M235 37C228 27 217 22 205 22C185 22 170 37 170 56C170 75 185 90 205 90C217 90 228 85 235 75"/>
      <!-- O -->
      <path class="line" d="M280 22C260 22 245 37 245 56C245 75 260 90 280 90C300 90 315 75 315 56C315 37 300 22 280 22Z"/>
      <!-- M -->
      <polyline class="line" points="330 90, 330 22, 355 60, 380 22, 380 90"/>
      <!-- E -->
      <path class="line" d="M430 22H395v68h35 M395 56h30"/>
    </g>
  </svg>
</div>
                <p class="text-xl md:text-2xl text-accent font-medium tracking-wide opacity-90 max-w-md italic">
                    for manage your field
                </p>
                <div class="mt-12 flex gap-4">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-full border border-white/10 backdrop-blur-sm">
                        <span class="w-2 h-2 bg-accent rounded-full animate-pulse"></span>
                        <span class="text-xs font-medium uppercase tracking-widest text-neutral-400">System Online</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Right Section: Dynamic Form Content -->
    <section class="w-full md:w-[35%] bg-primary p-4 md:p-8 flex items-center justify-center">
        <div class="w-full max-w-md h-full min-h-[600px] bg-card-dark rounded-3xl md:rounded-[3.5rem] p-8 md:p-12 flex flex-col shadow-2xl relative overflow-hidden overflow-y-auto custom-scrollbar">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-primary/20 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 h-full flex flex-col">
                {{ $slot }}
            </div>
        </div>
    </section>
</main>

<!-- Custom JS -->
<script type="module" src="{{ asset('js/login.js') }}"></script>

</body>
</html>
