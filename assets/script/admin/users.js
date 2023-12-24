import Alpine from 'alpinejs';
import {fetchGet, fetchPost} from '../service/Httpservice.js';
import env from '../env.js';
import User from './models.js';
import { Modal } from 'bootstrap';
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_users', () => ({
        token: null,
        allUsers: [],
        elmIndex: null,
        userM: null,
        formType:null,//1:user, 2 password
        async init() {
            this.getAllUsers();
        },
        async getToken() {
           let response = await fetchGet(`${env['basUrl']}/admin/get-token`);
           if (response['status'] === 1)this.token = response['data'];
        },
        async getAllUsers() {
            await this.getToken();
            let response = await fetchGet(`${env['basUrl']}/api/users`, this.token);
            if (response['hydra:member'] !== undefined) {
                this.allUsers = response['hydra:member'];
            }
        },
        switchModal(toOpen, formType = null, index = null) {
            this.formType = formType;
            const myModal = new Modal('#exampleModal', {
                keyboard: false
            })
            if (toOpen === true) {
                console.log(myModal)
                myModal.show();
            } else {

            }
        },
    }))
})

Alpine.start();

