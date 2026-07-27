// Leaflet Map Entegrasyonu (Dashboard)
document.addEventListener('DOMContentLoaded', function() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    // Haritayı map-helper.js üzerinden başlat
    var map = window.SahaMapHelper.init('map');

    // Backend'den gelen Audit Point verilerini window objesinden al
    const auditPoints = window.dashboardMapPoints || [];

    // Map Helper üzerinden ortak PIN SVG ikonunu al
    const customIcon = window.SahaMapHelper.getCustomPinIcon();

    // Pinleri haritaya yerleştir
    if (auditPoints && auditPoints.length > 0) {
        var bounds = [];
        auditPoints.forEach(point => {
            if(point.latitude && point.longitude) {
                var marker = L.marker([point.latitude, point.longitude], {icon: customIcon}).addTo(map);
                
                // Popup içeriği
                marker.bindPopup(`
                    <div style="padding: 6px; min-width: 150px;">
                        <div style="color: #1fc9dd; font-size: 15px; font-weight: bold; margin-bottom: 4px; border-bottom: 1px solid #666; padding-bottom: 4px;">${point.name}</div>
                        <div style="font-size: 13px; color: #dedede; line-height: 1.4;">${point.address}</div>
                    </div>
                `);
                
                bounds.push([point.latitude, point.longitude]);
            }
        });
        
        // Eklenen pinler varsa kamerayı hepsini görecek şekilde ayarla
        if(bounds.length > 0) {
            map.fitBounds(bounds, {padding: [50, 50]});
        }
    }
});
