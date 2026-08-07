<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Smart Field Audit System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|orbitron:400,700,900&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}?v={{ time() }}">
</head>
<body class="antialiased selection:bg-accent selection:text-white min-h-screen flex flex-col relative overflow-x-hidden">
    
    <!-- Background Abstract Shapes -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/20 blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-accent/10 blur-[150px]"></div>
    </div>


    <!-- Main Content (Scroll Container) -->
    <main class="relative w-full z-10 block">
        
        <!-- Fixed Viewport for 3D Animation and Text -->
        <div class="fixed inset-0 flex flex-col items-center justify-center pointer-events-none">
            
            <!-- Welcome Text -->
            <h1 class="text-3xl md:text-5xl font-display font-extrabold text-white text-center mb-12 tracking-wide glow-accent relative z-10 pointer-events-none transition-opacity duration-75">
                Welcome, scroll for manage your field
            </h1>

            <!-- Center: 3D Animation (Full Screen) -->
            <div id="canvas-container" class="absolute inset-0 w-full h-full z-0 pointer-events-auto">
                <div id="loading-message" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-accent font-display font-bold tracking-widest text-sm glow-accent animate-pulse">LOADING...</div>
            </div>

            <!-- Scroll Indicator Icon -->
            <div class="absolute bottom-0 flex flex-col items-center justify-center text-accent opacity-80 pointer-events-none z-10 transition-opacity duration-75">
                <!-- Mouse Icon -->
                <div class="w-6 h-10 border-2 border-accent rounded-full flex justify-center p-1 mb-2 shadow-[0_0_10px_rgba(31,201,221,0.5)]">
                    <div class="w-1 h-2 bg-accent rounded-full animate-bounce"></div>
                </div>
                <!-- Vertical Line -->
                <div class="w-[2px] h-16 bg-gradient-to-b from-accent to-transparent"></div>
            </div>
            
        </div>

        <!-- Scrollable Content Sections -->
        <div class="relative z-20 w-full flex flex-col items-center" style="background-color: #171717; margin-top: 130vh; padding-top: 4rem;">
            
            <!-- Section 1 -->
            <div class="w-full max-w-5xl px-6 py-24 min-h-screen flex items-center">
                <div class="glass p-12 rounded-3xl border border-primary/30 shadow-[0_0_40px_rgba(27,95,197,0.15)] flex flex-col md:flex-row gap-12 items-center">
                    <div class="flex-1">
                        <h2 class="text-4xl md:text-5xl font-display font-bold text-white mb-6">Intelligent <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-accent glow-primary">Field Audits</span></h2>
                        <p class="text-secondary text-lg leading-relaxed mb-8">
                            Transform how your field teams operate. Smart Field Audit System uses advanced AI and networking capabilities to keep your workforce connected, monitor operations in real-time, and seamlessly integrate all field data into one powerful dashboard.
                        </p>
                        <ul class="flex flex-col gap-4 text-white/80">
                            <li class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Real-time location tracking
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                AI-driven insights & reporting
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Instant feedback communication
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Section 2 / Call to Action -->
            <div class="w-full max-w-5xl px-6 py-24 min-h-[50vh] flex flex-col items-center justify-center text-center">
                <h2 class="text-4xl font-display font-bold text-white mb-6">Ready to upgrade your workflow?</h2>
                <p class="text-secondary text-lg max-w-2xl mb-10">
                    Join the future of field management. Get started today and bring your entire operation into a single, cohesive network.
                </p>
                <div class="flex flex-wrap gap-4 justify-center">
                    <a href="{{ route('login') }}" class="px-10 py-4 rounded-xl bg-gradient-to-r from-primary to-accent text-white font-bold text-lg hover:opacity-90 transition-all shadow-[0_0_20px_rgba(31,201,221,0.4)] hover:shadow-[0_0_30px_rgba(31,201,221,0.6)]">
                        Login to Dashboard
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-10 py-4 rounded-xl glass text-white font-bold text-lg hover:bg-white/5 transition-all border border-secondary/30">
                            Create Account
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </main>

    <!-- Three.js Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/EffectComposer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/RenderPass.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/ShaderPass.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/shaders/CopyShader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/shaders/LuminosityHighPassShader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/UnrealBloomPass.js"></script>

    <script defer src="{{ asset('js/welcome.js') }}?v={{ time() }}"></script>

</body>
</html>
