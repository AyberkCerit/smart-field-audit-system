// Leaflet Map Entegrasyonu (Create Task Page)
document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('taskMap');
    if (!mapElement) return;

    // Haritayı map-helper.js üzerinden başlat (Varsayılan Türkiye)
    var map = window.SahaMapHelper.init('taskMap', [39.92077, 32.85411], 5);

    var marker;
    var latInput = document.getElementById('latitude');
    var lngInput = document.getElementById('longitude');

    // Ortak PIN ikonunu al
    const customIcon = window.SahaMapHelper.getCustomPinIcon();

    // Eğer form hata verdiyse ve daha önce seçilmiş koordinat varsa haritada göster
    if (latInput.value && lngInput.value) {
        var lat = parseFloat(latInput.value);
        var lng = parseFloat(lngInput.value);
        marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
        map.setView([lat, lng], 13);
    }

    // Haritaya tıklandığında Marker ekle ve Hidden Input'ları doldur
    map.on('click', function(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Eski marker varsa sil
        if (marker) {
            map.removeLayer(marker);
        }

        // Yeni marker ekle
        marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);

        // Form alanlarını güncelle
        latInput.value = lat;
        lngInput.value = lng;
    });
});
