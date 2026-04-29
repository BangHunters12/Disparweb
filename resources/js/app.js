import './bootstrap';
import './landing';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Toast notification helper
window.toast = function(message, type = 'success') {
    const icons = {
        success: '✓',
        error: '✕',
        info: 'ℹ',
        warning: '⚠',
    };
    const colors = {
        success: 'text-emerald-400',
        error: 'text-red-400',
        info: 'text-blue-400',
        warning: 'text-amber-400',
    };

    const el = document.createElement('div');
    el.className = `toast ${type === 'error' ? 'toast-error' : 'toast-success'} animate-in slide-in-from-right`;
    el.innerHTML = `
        <span class="text-xl ${colors[type]}">${icons[type]}</span>
        <p class="text-gray-200 text-sm flex-1">${message}</p>
        <button onclick="this.parentElement.remove()" class="text-gray-500 hover:text-gray-300">×</button>
    `;
    document.body.appendChild(el);
    setTimeout(() => el.remove(), 4000);
};
