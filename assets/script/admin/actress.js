import Alpine from 'alpinejs';
import {fetchGet, fetchPost, fetchPatch, getToken} from '../service/Httpservice.js';
import Pagination from 'tui-pagination';
import { loader, resetDateToInputDate, readFile, wait } from '../service/UtilService.js';
import { Actress } from './models.js';
import { Modal } from 'bootstrap';
import validate from 'validate.js';
import env from '../env.js';
import toastr from 'toastr'
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_actresses', () => ({
        allActresses: [],
        elmIndex: null,
        pageItem: 1,
        totalItems: 0,
        actressM: new Actress(),
        modal:null,
        errors: undefined,
        file:null,
        filePath:null,
        keywords:"",
        orderByBirthday: '',
        searchAction: null,
        async init() {
            this.modal = new Modal('#exampleModal', {
                keyboard: false
            });
            toastr.options = env.toastrOptions;
            await this.getActresses();
        },
        async getActresses() {
            loader();
            const token = await getToken();
            let query = `/api/actresses?page=${this.pageItem}`;
            if (this.orderByBirthday !== '')query = `/api/actresses/birthday/${this.orderByBirthday}?page=${this.pageItem}`;
            let response = await fetchGet(query, token);
            loader(false);
            if (response['hydra:member'] !== undefined) {
                this.allActresses = response['hydra:member'];
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
        switchModal(toOpen, key = null) {
            this.elmIndex = key;

            if (toOpen === false) {
                this.modal.hide();
                return;
            } 

            this.actressM = new Actress();
            this.file = null;
            this.filePath = null;
            if (key !== null) {
                this.actressM.setData(this.allActresses[key]);
                this.actressM['birthday'] = resetDateToInputDate(this.actressM['birthday']);
            }

            this.modal.show();
        },
        async handleFile(target) {
            let file = target.files.item(0);
            if(file.type.includes('image')) {
                this.file = file;
                this.filePath = await readFile(file);
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
            };
            this.errors =  validate(form, constraints);
        },
        async editActress() {
            this.checkForm(this.$refs.actressform);
            if(this.errors === undefined) {
                loader(true);
                let formData = new FormData();
                let response;
                for(let index in this.actressM)formData.append(index, this.actressM[index]);
                let token = await getToken();
                if(this.elmIndex === null) {
                    if(this.file !== null)this.actressM.setPhoto(this.file);
                    response = await fetchPost(`/api/actresses`, formData, token, loader(false));
                    this.switchModal(false);
                    loader(false);
                    if (response['id']) {
                        this.allActresses.push(response);
                        toastr.success('Ajouter un utilisateur', 'Enregistré');
                    } else {
                        toastr.error('Ajouter un utilisateur', 'Error');
                    }
                } else {
                    response = await fetchPatch(`/api/actresses/${this.allActresses[this.elmIndex]['id']}`, this.actressM, token);
                    if(this.file !== null) {
                        formData.append('invoiceFile', this.file);
                        response = await fetchPost(`/api/actresses/${this.allActresses[this.elmIndex]['id']}/update-photo`, formData, token);
                    }
                    this.allActresses[this.elmIndex] = response;
                    this.switchModal(true, this.elmIndex);
                    loader(false);
                    toastr.success('Modifier un utilisateur', 'Enregistré');
                }
            }
        },
        async searchByKeywords(e) {
            this.searchAction = true;
            if(e.keyCode === 13) {
                if (this.keywords === '') {
                   //this.clearKeywords();
                } else {
                    loader(true);
                    let token = await getToken();
                    let query = `/api/actresses?page=${this.pageItem}&name=${this.keywords}`;
                    if (this.orderByBirthday !== '')query = `/api/actresses/birthday/${this.orderByBirthday}?page=${this.pageItem}&name=${this.keywords}`;
                    let response = await fetchGet(query, token);
                    loader(false);
                    if (response['hydra:member'] !== undefined) {
                        this.allActresses = response['hydra:member'];
                        this.totalItems = response['hydra:totalItems'];
                        this.setPaginator();
                    }
                }
            }

            this.searchAction = null;
        },
        async clearKeywords(e) {
            if (this.searchAction === null) {
                this.pageItem = 1;
                await this.getActresses();
            }
        }
    }))
})

Alpine.start();