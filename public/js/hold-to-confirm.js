document.addEventListener('DOMContentLoaded', function() {
    const btn = document.getElementById('holdToConfirmBtn');
    const progress = document.getElementById('holdProgress');
    const text = document.getElementById('holdText');
    const content = document.getElementById('holdContent');
    const form = btn ? btn.closest('form') : null;
    
    if (!btn || !progress || !text || !content || !form) return;
    
    let holdTimer;
    let isComplete = false;
    const HOLD_DURATION = 1200; // 1.2 saniye

    function startHold(e) {
        if (e.type !== 'touchstart' && e.button !== 0) return;
        if (isComplete) return;

        if (!form.checkValidity()) {
            form.reportValidity();
            if (window.Toast) {
                window.Toast.showWarning("Lütfen zorunlu alanları (İsim, E-posta, Şifre) doldurunuz!");
            }
            return;
        }

        e.preventDefault();
        
        // Efekti başlat
        progress.style.transition = `width ${HOLD_DURATION}ms linear`;
        progress.style.width = '100%';
        content.classList.add('scale-95');
        text.innerText = "Onaylanıyor...";

        holdTimer = setTimeout(() => {
            isComplete = true;
            text.innerText = "Gönderiliyor!";
            content.classList.remove('scale-95');
            const bgClass = btn.dataset.bgClass || 'bg-primary';
            const shadowClass = btn.dataset.shadowClass || 'shadow-[0_0_20px_rgba(27,95,197,0.8)]';
            btn.classList.add(bgClass, shadowClass);
            btn.style.pointerEvents = 'none';
            
            form.submit();
        }, HOLD_DURATION);
    }

    function cancelHold() {
        if (isComplete) return;
        
        clearTimeout(holdTimer);
        progress.style.transition = 'width 0.2s ease-out';
        progress.style.width = '0%';
        
        content.classList.remove('scale-95');
        text.innerText = "Oluşturmak İçin Basılı Tut";
    }

    // Mouse
    btn.addEventListener('mousedown', startHold);
    btn.addEventListener('mouseup', cancelHold);
    btn.addEventListener('mouseleave', cancelHold);
    
    // Touch
    btn.addEventListener('touchstart', startHold, {passive: false});
    btn.addEventListener('touchend', cancelHold);
    btn.addEventListener('touchcancel', cancelHold);
});
