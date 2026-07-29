window.Toast = {
    show: function(message, type = 'warning') {
        let toastId = 'customToast';
        let toast = document.getElementById(toastId);
        
        if (!toast) {
            toast = document.createElement('div');
            toast.id = toastId;
            document.body.appendChild(toast);
        }

        // Determine colors based on type
        let bgClass = type === 'success' ? 'bg-lime-500/95' : 'bg-red-500/95';
        let borderClass = type === 'success' ? 'border-lime-400/50' : 'border-red-400/50';
        let shadowClass = type === 'success' ? 'shadow-[0_0_20px_rgba(132,204,22,0.5)]' : 'shadow-[0_0_20px_rgba(239,68,68,0.5)]';
        let iconHtml = type === 'success' 
            ? `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`
            : `<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>`;

        toast.className = `fixed top-10 left-1/2 -translate-x-1/2 z-[10000] text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 translate-y-[-150%] opacity-0 flex items-center gap-3 pointer-events-none border ${bgClass} ${borderClass} ${shadowClass}`;
        
        toast.innerHTML = `${iconHtml}<span class="text-sm">${message}</span>`;
        
        // Reset animation state
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-[-150%]', 'opacity-0');

        // Show
        setTimeout(() => {
            toast.classList.remove('translate-y-[-150%]', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        }, 10);
        
        // Hide after 3.5 seconds
        if (this.hideTimeout) clearTimeout(this.hideTimeout);
        this.hideTimeout = setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-[-150%]', 'opacity-0');
        }, 3500);
    },
    
    showWarning: function(message) {
        this.show(message, 'warning');
    },
    
    showSuccess: function(message) {
        this.show(message, 'success');
    }
};
