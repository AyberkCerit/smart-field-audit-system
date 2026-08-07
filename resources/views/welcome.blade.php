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

    <!-- Navbar -->
    <nav class="relative z-10 w-full glass border-b border-secondary/20 py-4 px-6 md:px-12 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-accent flex items-center justify-center text-white shadow-[0_0_15px_rgba(31,201,221,0.4)]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <span class="font-display font-bold text-xl tracking-wider text-white">SmartField</span>
        </div>
        
        <div>
            @if (Route::has('login'))
                <div class="flex gap-4 items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-semibold text-white/80 hover:text-accent transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-white/80 hover:text-accent transition-colors">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 rounded-xl bg-primary text-white font-bold hover:bg-primary/80 transition-all shadow-[0_0_15px_rgba(27,95,197,0.4)] hover:shadow-[0_0_25px_rgba(27,95,197,0.6)]">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <main class="relative z-10 flex-grow flex flex-col lg:flex-row items-center justify-center px-6 md:px-12 py-12 gap-12 lg:gap-24 max-w-7xl mx-auto w-full">

        <!-- Center: 3D Animation -->
        <div class="w-full flex justify-center items-center relative h-[600px]">
            <div id="canvas-container" class="w-full max-w-[600px] h-full glass rounded-full overflow-hidden bg-glow border border-accent/20 relative group z-10 cursor-move shadow-[0_0_50px_rgba(31,201,221,0.2)] hover:shadow-[0_0_80px_rgba(31,201,221,0.4)] transition-shadow duration-500">
                <div id="loading-message" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-accent font-display font-bold tracking-widest text-sm glow-accent animate-pulse">LOADING...</div>
            </div>
            
            <!-- Glow under the container -->
            <div class="absolute -bottom-10 left-1/2 -translate-x-1/2 w-3/4 h-10 bg-accent/20 blur-2xl rounded-full"></div>
        </div>

    </main>

    <!-- Three.js Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/loaders/OBJLoader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/controls/OrbitControls.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/EffectComposer.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/RenderPass.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/ShaderPass.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/shaders/CopyShader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/shaders/LuminosityHighPassShader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.132.2/examples/js/postprocessing/UnrealBloomPass.js"></script>

    <script defer src="{{ asset('js/welcome.js') }}?v={{ time() }}"></script>
    <script>
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            const errDiv = document.createElement('div');
            errDiv.style.position = 'fixed';
            errDiv.style.bottom = '10px';
            errDiv.style.left = '10px';
            errDiv.style.background = 'red';
            errDiv.style.color = 'white';
            errDiv.style.padding = '10px';
            errDiv.style.zIndex = '9999';
            errDiv.innerText = "Error: " + msg + " at " + lineNo + ":" + columnNo;
            document.body.appendChild(errDiv);
            return false;
        };
    </script>
</body>
</html>
