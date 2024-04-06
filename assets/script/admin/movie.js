import Alpine from 'alpinejs';

import {fetchGet, fetchPost, fetchPatch, getToken} from '../service/Httpservice.js';
import Pagination from 'tui-pagination';
import { loader, sortArray, resetDateToInputDate, readFile, wait } from '../service/UtilService.js';
import env from '../env.js';
import toastr from 'toastr'
import { Modal } from 'bootstrap';
import { Movie } from './models.js';
import validate from 'validate.js';
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_movies', () => ({
        allMovies: [],
        allActresses: [],
        elmIndex: null,
        pageItem: 1,
        totalItems: 0,
        movieM: new Movie(),
        modal:null,
        modalAction:null,
        errors: undefined,
        // file:null,
        // filePath:null,
        async init() {
            this.modal = new Modal('#exampleModal', {
                keyboard: false
            });
            toastr.options = env.toastrOptions;
            this.getMovies();
            this.getActresses()
        },
        async getMovies() {
            loader();
            const token = await getToken();
            let query = `/api/movies?page=${this.pageItem}`;
            let response = await fetchGet(query, token);
            loader(false);
            if (response['hydra:member'] !== undefined) {
                this.allMovies = response['hydra:member'];
                this.totalItems = response['hydra:totalItems'];
                this.setPaginator();
            }
        },
        async getActresses() {
            const token = await getToken();
            let query = '/api/actresses/name/asc';
            let response = await fetchGet(query, token);
            if (response['hydra:member'] !== undefined) {
                this.allActresses = response['hydra:member'];
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

            if (action === 1) {
                this.movieM = new Movie();
                if (key !== null) {
                    this.movieM.setData(this.allMovies[key]);
                    this.movieM['released'] = resetDateToInputDate(this.movieM['released']);
                }
            }

            this.modal.show();
        },
        checkForm(form) {
            let constraints = {
                name: {
                    presence: {
                        allowEmpty: false,
                        message: "Il faut un nom",
                    },
                },
                released: {
                    presence: {
                        allowEmpty: false,
                        message: "Il faut une date",
                    },
                },
                last: {
                    presence: {
                        allowEmpty: false,
                        message: "Il faut une durée",
                    },
                    numericality: {
                        onlyInteger: true,
                        greaterThan: 0,
                        message: "La durée doit être supérieure à 0",
                        notGreaterThan: "0",
                    }
                }
            };
            this.errors =  validate(form, constraints);
        },
        editMovie() {
            this.checkForm(this.$refs.movieform);console.log(this.errors, this.movieM)
            if (this.errors === undefined) {
            }
        }
    }))
})

Alpine.start();