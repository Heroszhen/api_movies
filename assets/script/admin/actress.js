import Alpine from 'alpinejs';
import {fetchGet, fetchPost, fetchPatch, getToken} from '../service/Httpservice.js';
import Pagination from 'tui-pagination';
import { loader } from '../service/UtilService.js';
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_actresses', () => ({
        allActresses: [],
        elmIndex: null,
        pageItem: 1,
        totalItems: 0,
        async init() {
            await this.getActresses();
        },
        async getActresses() {
            loader();
            const token = await getToken();
            let response = await fetchGet(`/api/actresses?page=${this.pageItem}`, token, null);
            loader(false);
            if (response['hydra:member'] !== undefined) {
                this.allActresses = response['hydra:member'];console.log(this.allActresses)
                this.totalItems = response['hydra:totalItems'];
                this.setPaginator();
            }
        },
        setPaginator() {
            const container = document.getElementById('tui-pagination-container');
            const instance = new Pagination(container, {
                totalItems: this.totalItems,
                itemsPerPage: 18,
                visiblePages: 5,
                page: this.pageItem,
                centerAlign: true,
                firstItemClassName: 'tui-first-child',
                lastItemClassName: 'tui-last-child',
            });
            instance.getCurrentPage();
            instance.on('beforeMove', (eventData) => {
                this.pageItem = eventData.page;
                this.getActresses();
            });
        
            instance.on('afterMove', async (eventData) => {
                this.pageItem = eventData.page;
                this.getActresses();
                // this.pageItem = eventData.page;
                // if(this.keywords === "")await this.getAllUsers();
                // else await this.searchByKeywords();
            });
        },
    }))
})

Alpine.start();