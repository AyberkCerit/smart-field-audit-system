let scene, camera, renderer, composer;
let brainModel, backgroundParticles;

let networkGroup;
let networkCurves = [];
let networkSignals = [];

let container;
let loadingMessage;
const BRAIN_MODEL_URL = null; 

let sceneGroup;
let mouseX = 0;
let mouseY = 0;
let targetX = 0;
let targetY = 0;

let scrollPercent = 0;
let zoomPercent = 0;

function init() {
    console.log("Three.js init started");
    container = document.getElementById('canvas-container');
    loadingMessage = document.getElementById('loading-message');
    
    if (!container) {
        console.error("Canvas container not found!");
        return;
    }
    console.log("Canvas container found", container.clientWidth, container.clientHeight);

    const width = container.clientWidth || 500;
    const height = container.clientHeight || 500;

    scene = new THREE.Scene();
    scene.background = new THREE.Color(0x171717); 
    
    sceneGroup = new THREE.Group();
    scene.add(sceneGroup);

    camera = new THREE.PerspectiveCamera(75, width / height, 0.1, 1000);
    camera.position.set(0, 0, 6);

    renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(width, height);
    container.appendChild(renderer.domElement);

    const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
    scene.add(ambientLight);

    const pointLight = new THREE.PointLight(0x00ffff, 1);
    pointLight.position.set(5, 5, 5);
    scene.add(pointLight);

    composer = new THREE.EffectComposer(renderer);
    composer.addPass(new THREE.RenderPass(scene, camera));

    const bloomPass = new THREE.UnrealBloomPass(
        new THREE.Vector2(width, height),
        2.0, 
        0.2, 
        0.6  
    );
    composer.addPass(bloomPass);

    createBackgroundParticles();

    if (BRAIN_MODEL_URL) {
        loadBrainModel(BRAIN_MODEL_URL);
    } else {
        createPlaceholderBrain();
        if (loadingMessage) loadingMessage.style.display = 'none';
    }

    createGlobeNetwork();

    window.addEventListener('resize', onWindowResize, false);
    document.addEventListener('mousemove', onDocumentMouseMove, false);
    window.addEventListener('scroll', onScroll, false);
    
    animate();
}

function onScroll() {
    const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
    if (maxScroll > 0) {
        scrollPercent = Math.max(0, Math.min(window.scrollY / maxScroll, 1));
    }

    // Zoom finishes at 80vh (slightly faster than full screen scroll)
    zoomPercent = Math.max(0, Math.min(window.scrollY / (window.innerHeight * 0.8), 1));

    // Fade out welcome text and indicator early in the scroll
    const welcomeText = document.querySelector('h1');
    const scrollIndicator = document.querySelector('.bottom-0');
    const opacity = Math.max(0, 1 - (zoomPercent * 4)); // Fades out completely by 25% scroll
    
    if (welcomeText) welcomeText.style.opacity = opacity;
    if (scrollIndicator) scrollIndicator.style.opacity = opacity;
}

function onDocumentMouseMove(event) {
    // Normalize mouse coordinates from -1 to 1 based on window
    mouseX = (event.clientX / window.innerWidth) * 2 - 1;
    mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
}

function createGlobeNetwork() {
    networkGroup = new THREE.Group();
    sceneGroup.add(networkGroup);

    const RADIUS = 2.8; 
    const NODE_COUNT = 40; 
    const nodes = [];

    const nodeGeometry = new THREE.BufferGeometry();
    const nodeVertices = [];

    for (let i = 0; i < NODE_COUNT; i++) {
        const phi = Math.acos(-1 + (2 * i) / NODE_COUNT);
        const theta = Math.sqrt(NODE_COUNT * Math.PI) * phi;

        const x = RADIUS * Math.cos(theta) * Math.sin(phi);
        const y = RADIUS * Math.sin(theta) * Math.sin(phi);
        const z = RADIUS * Math.cos(phi);
        
        const vector = new THREE.Vector3(x, y, z);
        nodes.push(vector);
        nodeVertices.push(x, y, z);
    }

    nodeGeometry.setAttribute('position', new THREE.Float32BufferAttribute(nodeVertices, 3));
    const nodeMaterial = new THREE.PointsMaterial({ color: 0x1fc9dd, size: 0.08, transparent: true, opacity: 0.8 });
    const nodePoints = new THREE.Points(nodeGeometry, nodeMaterial);
    networkGroup.add(nodePoints);

    const lineMaterial = new THREE.LineBasicMaterial({ color: 0x1b5fc5, transparent: true, opacity: 0.15 });

    for (let i = 0; i < nodes.length; i++) {
        for (let j = i + 1; j < nodes.length; j++) {
            const dist = nodes[i].distanceTo(nodes[j]);
            if (dist < 4.0 && Math.random() > 0.6) { 
                let midPoint = nodes[i].clone().add(nodes[j]).multiplyScalar(0.5);
                midPoint.normalize().multiplyScalar(RADIUS + dist * 0.3); 

                const curve = new THREE.QuadraticBezierCurve3(nodes[i], midPoint, nodes[j]);
                networkCurves.push(curve);

                const curvePoints = curve.getPoints(50);
                const curveGeometry = new THREE.BufferGeometry().setFromPoints(curvePoints);
                const curveLine = new THREE.Line(curveGeometry, lineMaterial);
                networkGroup.add(curveLine);
            }
        }
    }

    const SIGNAL_COUNT = 25; 
    const signalGeometry = new THREE.SphereGeometry(0.03, 8, 8);
    const signalMaterial = new THREE.MeshBasicMaterial({ color: 0xffffff }); 

    for (let i = 0; i < SIGNAL_COUNT; i++) {
        const signalMesh = new THREE.Mesh(signalGeometry, signalMaterial);
        networkGroup.add(signalMesh);

        networkSignals.push({
            mesh: signalMesh,
            curveIndex: Math.floor(Math.random() * networkCurves.length), 
            progress: Math.random(), 
            speed: 0.003 + Math.random() * 0.005 
        });
    }
}

function createBackgroundParticles() {
    const geometry = new THREE.BufferGeometry();
    const vertices = [];
    for (let i = 0; i < 1500; i++) {
        vertices.push((Math.random() - 0.5) * 100, (Math.random() - 0.5) * 100, (Math.random() - 0.5) * 100);
    }
    geometry.setAttribute('position', new THREE.Float32BufferAttribute(vertices, 3));
    const material = new THREE.PointsMaterial({ color: 0x1fc9dd, size: 0.04, transparent: true, opacity: 0.15 });
    backgroundParticles = new THREE.Points(geometry, material);
    sceneGroup.add(backgroundParticles);
}

function createPlaceholderBrain() {
    const geometry = new THREE.IcosahedronGeometry(1.5, 16);
    
    const wireframeGeometry = new THREE.WireframeGeometry(geometry);
    const wireframeMaterial = new THREE.LineBasicMaterial({ color: 0x1b5fc5, transparent: true, opacity: 0.2 });
    const wireframeBrain = new THREE.LineSegments(wireframeGeometry, wireframeMaterial);

    const pointsMaterial = new THREE.PointsMaterial({ color: 0x1fc9dd, size: 0.03, transparent: true, opacity: 0.6 });
    const pointsBrain = new THREE.Points(geometry, pointsMaterial);

    brainModel = new THREE.Group();
    brainModel.add(wireframeBrain);
    brainModel.add(pointsBrain);
    
    sceneGroup.add(brainModel);
}

function loadBrainModel(url) {
    if (loadingMessage) loadingMessage.style.display = 'block';
    const loader = new THREE.OBJLoader();
    loader.load(url, (object) => {
        if (loadingMessage) loadingMessage.style.display = 'none';
        object.traverse((child) => {
            if (child.isMesh) {
                const wireframeGeometry = new THREE.WireframeGeometry(child.geometry);
                const wireframeMaterial = new THREE.LineBasicMaterial({ color: 0x1b5fc5, transparent: true, opacity: 0.2 });
                const wireframe = new THREE.LineSegments(wireframeGeometry, wireframeMaterial);

                const pointsMaterial = new THREE.PointsMaterial({ color: 0x1fc9dd, size: 0.02, transparent: true, opacity: 0.7 });
                const points = new THREE.Points(child.geometry, pointsMaterial);

                child.material = new THREE.MeshBasicMaterial({ visible: false }); 
                child.add(wireframe);
                child.add(points);
            }
        });
        brainModel = object;
        sceneGroup.add(brainModel);
    });
}

function onWindowResize() {
    if(!container) return;
    const width = container.clientWidth;
    const height = container.clientHeight;
    camera.aspect = width / height;
    camera.updateProjectionMatrix();
    renderer.setSize(width, height);
    composer.setSize(width, height);
}

function animate() {
    requestAnimationFrame(animate);

    // Camera Zoom based on zoom percentage (6.0 down to 0.1)
    const targetZ = 6.0 - (zoomPercent * 5.9);
    camera.position.z += (targetZ - camera.position.z) * 0.1;

    // Mouse tracking interpolation (anlık ama yumuşak takip)
    targetX = mouseX * 0.5; // Max rotation angle
    targetY = mouseY * 0.5;
    
    if (sceneGroup) {
        sceneGroup.rotation.y += (targetX - sceneGroup.rotation.y) * 0.1;
        sceneGroup.rotation.x += (-targetY - sceneGroup.rotation.x) * 0.1;
    }

    if (brainModel) {
        brainModel.rotation.y += 0.002;
    }

    if (networkGroup) {
        networkGroup.rotation.y += 0.001; 
        networkGroup.rotation.x += 0.0005;

        networkSignals.forEach(signal => {
            signal.progress += signal.speed;
            
            if (signal.progress >= 1.0) {
                signal.progress = 0; 
                signal.curveIndex = Math.floor(Math.random() * networkCurves.length);
            }

            const currentCurve = networkCurves[signal.curveIndex];
            if (currentCurve) {
                const pointOnCurve = currentCurve.getPoint(signal.progress);
                signal.mesh.position.copy(pointOnCurve);
            }
        });
    }

    if (backgroundParticles) {
        backgroundParticles.rotation.y -= 0.0005;
    }

    composer.render();
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
