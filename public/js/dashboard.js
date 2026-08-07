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

// Feedback Form AJAX Submission for Personnel
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('feedback-form');
    const container = document.getElementById('feedback-container');
    const input = form ? form.querySelector('input[name="message"]') : null;

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = input.value;
            if (!message.trim()) return;

            const formData = new FormData(form);
            const btn = form.querySelector('button');
            const originalBtnText = btn.innerHTML;
            
            btn.disabled = true;
            input.disabled = true;
            btn.innerHTML = '...';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const emptyMsg = container.querySelector('.text-center');
                    if (emptyMsg) emptyMsg.remove();
                    
                    const html = `
                        <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 ml-auto border-primary/30 bg-primary/10 max-w-[85%]">
                            <div class="flex justify-between items-center mb-1 gap-4">
                                <span class="text-xs font-bold text-primary">You</span>
                                <span class="text-[10px] text-secondary">${data.time}</span>
                            </div>
                            <p class="text-sm text-white/90">${data.feedback.message}</p>
                        </div>
                    `;
                    
                    container.insertAdjacentHTML('afterbegin', html);
                    input.value = '';
                    container.scrollTop = 0; // flex-col-reverse makes 0 the bottom
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                btn.disabled = false;
                input.disabled = false;
                btn.innerHTML = originalBtnText;
                input.focus();
            });
        });
    }

    // Manager Chat Logic
    const userSelect = document.getElementById('chat-user-select');
    const managerForm = document.getElementById('manager-feedback-form');
    const managerContainer = document.getElementById('manager-feedback-container');
    const managerPersonnelIdInput = document.getElementById('manager-personnel-id');
    const managerInput = managerForm ? managerForm.querySelector('input[name="message"]') : null;

    if (userSelect && managerForm && managerContainer) {
        userSelect.addEventListener('change', function() {
            const personnelId = this.value;
            if (!personnelId) {
                managerForm.style.display = 'none';
                managerPersonnelIdInput.value = '';
                managerContainer.innerHTML = '<div class="text-sm text-secondary text-center py-4 w-full h-full flex items-center justify-center">Please select a personnel to view their feedback thread.</div>';
                return;
            }

            managerForm.style.display = 'flex';
            managerPersonnelIdInput.value = personnelId;
            managerContainer.innerHTML = '<div class="text-sm text-secondary text-center py-4 w-full h-full flex items-center justify-center">Loading...</div>';

            fetch(`/feedbacks/history/${personnelId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    managerContainer.innerHTML = '';
                    if (data.feedbacks.length === 0) {
                        managerContainer.innerHTML = '<div class="text-sm text-secondary text-center py-4 w-full h-full flex items-center justify-center">No feedback sent yet. Start the conversation!</div>';
                    } else {
                        data.feedbacks.forEach(feedback => {
                            const alignClass = feedback.is_own ? 'ml-auto border-primary/30 bg-primary/10' : 'mr-auto';
                            const nameColor = feedback.is_own ? 'text-primary' : 'text-white';
                            
                            const html = `
                                <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 ${alignClass} max-w-[85%]">
                                    <div class="flex justify-between items-center mb-1 gap-4">
                                        <span class="text-xs font-bold ${nameColor}">${feedback.name}</span>
                                        <span class="text-[10px] text-secondary">${feedback.time}</span>
                                    </div>
                                    <p class="text-sm text-white/90">${feedback.message}</p>
                                </div>
                            `;
                            // Append to end since it's flex-col-reverse (end = top)
                            managerContainer.insertAdjacentHTML('beforeend', html);
                        });
                    }
                }
            })
            .catch(err => {
                console.error(err);
                managerContainer.innerHTML = '<div class="text-sm text-red-500 text-center py-4 w-full h-full flex items-center justify-center">Failed to load history.</div>';
            });
        });

        managerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = managerInput.value;
            if (!message.trim()) return;

            const formData = new FormData(managerForm);
            const btn = managerForm.querySelector('button');
            const originalBtnText = btn.innerHTML;
            
            btn.disabled = true;
            managerInput.disabled = true;
            btn.innerHTML = '...';

            fetch(managerForm.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const emptyMsg = managerContainer.querySelector('.text-center');
                    if (emptyMsg) emptyMsg.remove();
                    
                    const html = `
                        <div class="bg-background/50 rounded-lg p-3 border border-secondary/20 ml-auto border-primary/30 bg-primary/10 max-w-[85%]">
                            <div class="flex justify-between items-center mb-1 gap-4">
                                <span class="text-xs font-bold text-primary">You</span>
                                <span class="text-[10px] text-secondary">${data.time}</span>
                            </div>
                            <p class="text-sm text-white/90">${data.feedback.message}</p>
                        </div>
                    `;
                    
                    managerContainer.insertAdjacentHTML('afterbegin', html);
                    managerInput.value = '';
                    managerContainer.scrollTop = 0;
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                btn.disabled = false;
                managerInput.disabled = false;
                btn.innerHTML = originalBtnText;
                managerInput.focus();
            });
        });
    }
});
