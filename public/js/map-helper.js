// Leaflet Map Yardımcı Sınıfı (Reusable Map Helper)
window.SahaMapHelper = {
    // Haritayı verilen ID'deki element içine kurar ve döndürür
    init: function(elementId, center = [39.92077, 32.85411], zoom = 6) {
        var map = L.map(elementId).setView(center, zoom);
        
        // Google Maps Koyu Modu (Dark Mode) için özel CSS filtreleri
        if (!document.getElementById('google-map-dark-style')) {
            const style = document.createElement('style');
            style.id = 'google-map-dark-style';
            // Invert, hue-rotate ve contrast ile tam Google Maps Dark Mode renkleri elde edilir
            style.innerHTML = `
                .google-dark-mode-tiles {
                    filter: invert(100%) hue-rotate(180deg) brightness(95%) contrast(85%) saturate(80%);
                }
                .leaflet-container {
                    background: #17202A !important;
                }
            `;
            document.head.appendChild(style);
        }

        // Google Maps Standart Yol Haritası Katmanı
        L.tileLayer('https://mt1.google.com/vt/lyrs=m&hl=tr&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            attribution: 'Map data &copy; <a href="https://www.google.com/maps">Google</a>',
            className: 'google-dark-mode-tiles' // CSS filtresini uygulamak için özel sınıf
        }).addTo(map);
        
        return map;
    },
    
    // Projenin tasarım DNA'sına uygun (Neon Turkuaz) özel harita işareti (Pin) döndürür
    getCustomPinIcon: function() {
        const pinSvg = `
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="filter: drop-shadow(0px 3px 4px rgba(0,0,0,0.6));">
                <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2Z" fill="#1fc9dd" stroke="#ffffff" stroke-width="1.5"/>
                <circle cx="12" cy="9" r="3.5" fill="#ffffff"/>
            </svg>
        `;
        return L.divIcon({
            className: 'bg-transparent border-none',
            html: pinSvg,
            iconSize: [36, 36],
            iconAnchor: [18, 36],
            popupAnchor: [0, -32]
        });
    }
};
