import Alpine from 'alpinejs';
import {fetchGet, fetchPost} from '../service/Httpservice.js';
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_users', () => ({
        async init() {
          let response = await fetchGet('https://randomuser.me/api/');console.log(response);
        }
    }))
})

Alpine.start();

