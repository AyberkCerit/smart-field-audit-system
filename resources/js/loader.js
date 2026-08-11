import { animate, svg, utils } from 'animejs';

document.addEventListener('DOMContentLoaded', () => {
    const loader = document.getElementById('global-page-loader');
    if (!loader) return;

    // Anime.js Morphing Logic
    const [ $path1, $path2 ] = utils.$('polygon');

    // Only run if paths exist
    if ($path1 && $path2) {
        function animateRandomPoints() {
            // Update the points attribute on #path-2
            utils.set($path2, { points: generatePoints() });
            // Morph the points of #path-1 into #path-2
            animate($path1, {
                points: svg.morphTo($path2),
                ease: 'inOutCirc',
                duration: 500,
                onComplete: animateRandomPoints
            });
        }

        // Start the animation
        animateRandomPoints();

        // A function to generate random points on #path-2 on each iteration
        function generatePoints() {
            const total = utils.random(4, 64);
            const r1 = utils.random(4, 56);
            const r2 = 56;
            const isOdd = n => n % 2;
            let points = '';
            for (let i = 0, l = isOdd(total) ? total + 1 : total; i < l; i++) {
                const r = isOdd(i) ? r1 : r2;
                const a = (2 * Math.PI * i / l) - Math.PI / 2;
                const x = 152 + utils.round(r * Math.cos(a), 0);
                const y = 56 + utils.round(r * Math.sin(a), 0);
                points += `${x},${y} `;
            }
            return points;
        }
    }

    // Page Transition Logic
    window.addEventListener('load', () => {
        setTimeout(() => {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
                document.body.classList.remove('loading-active');
            }, 500);
        }, 300);
    });

    document.querySelectorAll('a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = this.getAttribute('target');
            const href = this.getAttribute('href');
            
            if (target === '_blank' || !href || href.startsWith('#') || href.startsWith('javascript:')) {
                return;
            }
            
            try {
                const url = new URL(href, window.location.origin);
                if (url.origin === window.location.origin) {
                    e.preventDefault();
                    
                    loader.style.display = 'flex';
                    void loader.offsetWidth; 
                    loader.style.opacity = '1';
                    document.body.classList.add('loading-active');
                    
                    setTimeout(() => {
                        window.location.href = href;
                    }, 400); 
                }
            } catch(err) {
                // Ignore
            }
        });
    });
    
    window.addEventListener('pageshow', (e) => {
        if (e.persisted) {
            loader.style.opacity = '0';
            setTimeout(() => {
                loader.style.display = 'none';
                document.body.classList.remove('loading-active');
            }, 500);
        }
    });
});
