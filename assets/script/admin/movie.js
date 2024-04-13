import Alpine from 'alpinejs';

import {fetchGet, getToken} from '../service/Httpservice.js';
import Pagination from 'tui-pagination';
import { sortArray, resetDateToInputDate } from '../service/UtilService.js';
import { Modal } from 'bootstrap';
import { Movie } from './models.js';
import validate from 'validate.js';
import {store} from '../store/store.js';
import { fetchGetMovies, sortMovies, fetchAddMovie, fetchUpdateMovie, fetchDeleteMovie } from '../store/movie.slice.js';
 
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
            
            store.subscribe(()=>{
                this.allMovies = store.getState().movieReducer.movies;
                this.totalItems = store.getState().movieReducer.totalItems;
                this.setPaginator();
            })

            this.getMovies();
            this.getActresses()
        },
        async getMovies() {
            store.dispatch(fetchGetMovies({pageItem: this.pageItem}));
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
            store.dispatch(sortMovies({movies: this.allMovies, field:field, order:order, type:type}))
            //this.allMovies = sortArray(this.allMovies, field, order, type);
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
        switchActor(e, id) {
            if (e.target.checked === true) {
                this.movieM['actors'].push(id);
            } else {
                let index = this.movieM['actors'].indexOf(id);
                this.movieM['actors'].splice(index, 1);
            }
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
        async editMovie() {
            this.checkForm(this.$refs.movieform);
            if (this.errors === undefined) {
                let movie = {...this.movieM}
                movie['actors'] = [];
                movie['last'] = parseInt(movie['last']);
                this.movieM["actors"].forEach(id => movie['actors'].push('/api/actresses/' + id));

                if (this.elmIndex === null) {
                    store.dispatch(fetchAddMovie({movie: movie}));
                } else {
                    store.dispatch(fetchUpdateMovie({movie: movie, index: this.elmIndex}));
                }
            }
        },
        deleteMovie(index) {
            store.dispatch(fetchDeleteMovie({index: index, id: this.allMovies[index]["id"]}));
        }
    }))
})

Alpine.start();