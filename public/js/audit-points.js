let leafletMap = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize map with a default center if no points
    const defaultCenter = [41.0082, 28.9784]; // Istanbul
    
    // Check if map container exists
    const mapContainer = document.getElementById('map');
    if (!mapContainer) return;
    
    leafletMap = window.SahaMapHelper.init('map', defaultCenter, 10);
    
    const points = window.auditPointsData || [];
    const customIcon = window.SahaMapHelper.getCustomPinIcon();
    
    if (points.length > 0) {
        const bounds = [];
        points.forEach(point => {
            const marker = L.marker([point.latitude, point.longitude], {icon: customIcon}).addTo(leafletMap);
            
            // Redirect directly to the task details on click
            marker.on('click', function() {
                if (point.tasks && point.tasks.length > 0) {
                    window.location.href = `/tasks/${point.tasks[0].id}`;
                }
            });
            bounds.push([point.latitude, point.longitude]);
        });

        // Fit map to show all markers
        if (bounds.length > 0) {
            if (bounds.length === 1) {
                leafletMap.setView(bounds[0], 15);
            } else {
                // Using a little timeout to ensure map container is fully rendered before fitting bounds
                setTimeout(() => {
                    leafletMap.fitBounds(bounds, { padding: [50, 50] });
                }, 300);
            }
        }
    }
});

// Focus map on specific point when card is clicked
window.focusMap = function(lat, lng) {
    if (leafletMap) {
        leafletMap.setView([lat, lng], 16, {
            animate: true,
            duration: 1
        });
    }
};
