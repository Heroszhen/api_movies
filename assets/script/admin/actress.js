import Alpine from 'alpinejs';

 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_actresses', () => ({
        async init() {
        }
    }))
})

Alpine.start();