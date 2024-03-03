import Alpine from 'alpinejs';

import {fetchGet, fetchPost, fetchPatch, getToken} from '../service/Httpservice.js';
import Pagination from 'tui-pagination';
import { loader, sortArray, readFile, wait } from '../service/UtilService.js';
import env from '../env.js';
import toastr from 'toastr'
import { Modal } from 'bootstrap';
import { Movie } from './models.js';
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_movies', () => ({
        allMovies: [],
        elmIndex: null,
        pageItem: 1,
        totalItems: 0,
        movieM: new Movie(),
        modal:null,
        modalAction:null,
        // errors: undefined,
        // file:null,
        // filePath:null,
        async init() {
            this.modal = new Modal('#exampleModal', {
                keyboard: false
            });
            toastr.options = env.toastrOptions;
            await this.getMovies();
        },
        async getMovies() {
            loader();
            const token = await getToken();
            let query = `/api/movies?page=${this.pageItem}`;
            let response = await fetchGet(query, token);
            loader(false);console.log(response['hydra:member']);
            if (response['hydra:member'] !== undefined) {
                this.allMovies = response['hydra:member'];
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
                this.getMovies();
            });
        
            instance.on('afterMove', async (eventData) => {
                this.pageItem = eventData.page;
                this.getMovies();
            });
        },
        sortMovies(field, order, type) {
            this.allMovies = sortArray(this.allMovies, field, order, type);
        },
        switchModal(action, key = null) {
            this.elmIndex = key;
            this.modalAction = action;
            if (action === null) {
                this.modal.hide();
                return;
            } 

            this.modal.show();
        }
    }))
})

Alpine.start();