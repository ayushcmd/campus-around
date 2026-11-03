import './bootstrap';
import Alpine from 'alpinejs';
import * as lucide from 'lucide';
import L from 'leaflet';

window.Alpine = Alpine;
window.lucide = lucide;
window.L = L;

// Fix Leaflet icon paths in Vite bundles
delete L.Icon.Default.prototype._getIconUrl;
L.Icon.Default.mergeOptions({
    iconRetinaUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png',
    iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
    shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
});

// Helper for rendering Lucide icons whenever content updates
window.renderIcons = () => {
    if (lucide && typeof lucide.createIcons === 'function') {
        try {
            lucide.createIcons({ icons: lucide.icons });
        } catch (e) {
            console.debug('Lucide render note:', e);
        }
    }
};

document.addEventListener('DOMContentLoaded', () => {
    window.renderIcons();
});

// Start Alpine
Alpine.start();
