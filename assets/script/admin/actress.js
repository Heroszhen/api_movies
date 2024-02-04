import Alpine from 'alpinejs';
import {fetchGet, fetchPost, fetchPatch, getToken} from '../service/Httpservice.js';
import Pagination from 'tui-pagination';
import { loader, resetDateToInputDate, readFile } from '../service/UtilService.js';
import { Actress } from './models.js';
import { Modal } from 'bootstrap';
import validate from 'validate.js';
 
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
        async init() {
            this.modal = new Modal('#exampleModal', {
                keyboard: false
            });
            await this.getActresses();
        },
        async getActresses() {
            loader();
            const token = await getToken();
            let response = await fetchGet(`/api/actresses?page=${this.pageItem}`, token, null);
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
                let formDate = new FormData();
                let token;
                if(this.elmIndex === null) {
                    if(this.file !== null)this.actressM.setPhoto(this.file);
                    for (let index in this.actressM)formDate.append(index, this.actressM[index]);
                    token = await getToken();
                    let response = await fetchPost(`/api/actresses`, formDate, token, loader(false));
                } else {

                }
                loader(false);
            }
        }
    }))
})

Alpine.start();