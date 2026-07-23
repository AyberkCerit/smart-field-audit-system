document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('canvas-container');
    if (!container) return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(75, container.clientWidth / container.clientHeight, 0.1, 1000);
    const renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    container.appendChild(renderer.domElement);

    // Simple abstract brain-like shape using multiple spheres and points
    const brainGroup = new THREE.Group();

    // Central Core
    const coreGeometry = new THREE.IcosahedronGeometry(1.5, 2);
    const coreMaterial = new THREE.MeshBasicMaterial({
        color: 0x1fc9dd,
        wireframe: true,
        transparent: true,
        opacity: 0.3
    });
    const core = new THREE.Mesh(coreGeometry, coreMaterial);
    brainGroup.add(core);

    // Point Cloud for neural network effect
    const pointsGeometry = new THREE.BufferGeometry();
    const count = 1500;
    const positions = new Float32Array(count * 3);
    for (let i = 0; i < count; i++) {
        const r = 2 + Math.random() * 0.5;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.random() * Math.PI;
        positions[i * 3] = r * Math.sin(phi) * Math.cos(theta);
        positions[i * 3 + 1] = r * Math.sin(phi) * Math.sin(theta);
        positions[i * 3 + 2] = r * Math.cos(phi);
    }
    pointsGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    const pointsMaterial = new THREE.PointsMaterial({
        size: 0.04,
        color: 0x1fc9dd,
        transparent: true,
        opacity: 0.8
    });
    const pointCloud = new THREE.Points(pointsGeometry, pointsMaterial);
    brainGroup.add(pointCloud);

    // Neural connections (lines)
    const linesMaterial = new THREE.LineBasicMaterial({ color: 0x1fc9dd, transparent: true, opacity: 0.1 });
    const linesGeometry = new THREE.BufferGeometry();
    const linePositions = [];
    for (let i = 0; i < 50; i++) {
        const p1 = new THREE.Vector3(
            (Math.random() - 0.5) * 4,
            (Math.random() - 0.5) * 4,
            (Math.random() - 0.5) * 4
        );
        const p2 = new THREE.Vector3(
            (Math.random() - 0.5) * 4,
            (Math.random() - 0.5) * 4,
            (Math.random() - 0.5) * 4
        );
        linePositions.push(p1.x, p1.y, p1.z, p2.x, p2.y, p2.z);
    }
    linesGeometry.setAttribute('position', new THREE.Float32BufferAttribute(linePositions, 3));
    const lines = new THREE.LineSegments(linesGeometry, linesMaterial);
    brainGroup.add(lines);

    scene.add(brainGroup);
    camera.position.z = 5;

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);
        brainGroup.rotation.y += 0.003;
        brainGroup.rotation.x += 0.001;
        const time = Date.now() * 0.001;
        core.scale.set(
            1 + Math.sin(time) * 0.05,
            1 + Math.sin(time) * 0.05,
            1 + Math.sin(time) * 0.05
        );
        renderer.render(scene, camera);
    }

    // Handle Resize
    window.addEventListener('resize', () => {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    animate();
});

import { animate, svg, stagger } from 'https://esm.sh/animejs@4.5.0';

animate(svg.createDrawable('.line'), {
    draw: ['0 0', '0 1', '1 1'],
    ease: 'inOutQuad',
    duration: 4000,
    delay: stagger(200),
    loop: true
});

// Password visibility toggle functionality
document.addEventListener('DOMContentLoaded', function () {
    function setupToggle(btnId, inputId, iconId) {
        const btn = document.getElementById(btnId);
        if (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);
                if (input && icon) {
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.textContent = 'visibility_off';
                    } else {
                        input.type = 'password';
                        icon.textContent = 'visibility';
                    }
                }
            });
        }
    }

    setupToggle('toggle-password', 'password-input', 'toggle-icon');
    setupToggle('toggle-password-confirm', 'password-confirm-input', 'toggle-icon-confirm');
});
