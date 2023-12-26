import Alpine from 'alpinejs';
import {fetchGet, fetchPost, fetchPatch} from '../service/Httpservice.js';
import env from '../env.js';
import User from './models.js';
import { Modal } from 'bootstrap';
import validate from 'validate.js';
import { loader } from '../service/UtilService.js';
import toastr from 'toastr'
 
window.Alpine = Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('admin_users', () => ({
        token: null,
        allUsers: [],
        elmIndex: null,
        userM: null,
        formType:null,//1:user, 2 password
        errors: void 0,
        modal:null,
        async init() {
            toastr.options = env.toastrOptions;
            this.getAllUsers();
            this.modal = new Modal('#exampleModal', {
                keyboard: false
            });
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
            if (toOpen === false) {
               this.modal.hide();
            } 

            this.formType = formType;
            this.elmIndex = index;
            
            if (toOpen === true) {
                this.userM = new User();
                this.errors = {};
                if (formType === 1) {
                    if (index !== null) {
                        Object.assign(this.userM, this.allUsers[index]);
                        delete this.userM['password'];
                    }
                } else if(formType === 2) {

                }
                this.modal.show();
            }
        },
        checkForm(form) {
            let constraints;
            validate.validators.password = function(value, options, key, attributes) {
                /*
                console.log(value);//password value
                console.log(options);//options
                console.log(key);//default
                console.log(attributes);//all fields with values in {}
                */
                let pattern = /[a-z]{1,}/;
                if (pattern.test(value) === false)return "Il faut au moins une lettre minuscules";

                pattern = /[A-Z]{1,}/;
                if (pattern.test(value) === false)return "Il faut au moins une lettre majuscules";

                pattern = /[0-9]{1,}/;
                if (pattern.test(value) === false)return "Il faut au moins un chiffre";

                pattern = /[\s]{1,}/;
                if (pattern.test(value) === true)return "Il ne faut pas mettre d'espaces";

                let specials = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '+', '=', '{', '}', '[', ']', ':', ';', '<', '>' , "'", , '?', '/', '.', '|'];
                let check = false;
                specials.forEach((specialValue, index) => {
                    if (value.includes(specialValue))check = true;
                });
                if (check === false)return "Il faut au moins un caractère spécial";

                return;
            };
            validate.validators.confirmation = function(value, options, key, attributes) {
                if (
                    attributes['password'] !== undefined && attributes['confirmation'] !== undefined && 
                    attributes['password'] !== attributes['confirmation']
                ) {
                     return "La confirmation n'est pas égale au mot de passe";
                }

                return;
            };
            if (this.formType === 1) {
                constraints = {
                    email: {
                        presence: {
                            allowEmpty: false,
                            message: "Il faut un email",
                        },
                        email: {
                            message: "Le mail n'est pas valide"
                        }
                    },
                    password: {
                        presence: {
                            allowEmpty: false,
                            message: "Il faut un mot de passe"
                        },
                        length: {
                            minimum: 8,
                            tooShort: 'Il faut au moins 8 caractères',
                            maximum: 20,
                            tooLong: 'Il faut au plus 20 caractères',
                        },
                        password: 'default'
                    },
                    lastname: {
                        presence: {
                            allowEmpty: false,
                            message: "Il faut un nom",
                        },
                    },
                    firstname: {
                        presence: {
                            allowEmpty: false,
                            message: "Il faut un prénom",
                        },
                    }
                }
                if (this.elmIndex !== null) {
                    delete constraints['password'];
                }
            } else if(this.formType === 2) {
                constraints = {
                    password: {
                        presence: {
                            allowEmpty: false,
                            message: "Il faut un mot de passe"
                        },
                        length: {
                            minimum: 8,
                            tooShort: 'Il faut au moins 8 caractères',
                            maximum: 20,
                            tooLong: 'Il faut au plus 20 caractères',
                        },
                        password: 'default'
                    },
                    confirmation: {
                        presence: {
                            allowEmpty: false,
                            message: "Il faut un mot de passe"
                        },
                        length: {
                            minimum: 8,
                            tooShort: 'Il faut au moins 8 caractères',
                            maximum: 20,
                            tooLong: 'Il faut au plus 20 caractères',
                        },
                        password: 'default',
                        confirmation: 'default'
                    },
                }
            }

            this.errors =  validate(form, constraints);//undefined or object
            if (this.errors === undefined)this.errors = void 0;
        },
        async editUser() {
            this.checkForm(this.$refs.userform);
            if (this.errors === void 0) {
                loader();
                await this.getToken();
                let response;
                if (this.elmIndex === null) {
                    response = await fetchPost(`${env['basUrl']}/api/users`, this.userM, this.token);
                    loader(false);
                    if (response['id'] !== undefined) {
                        this.allUsers.unshift(response);
                        this.switchModal(false);
                        toastr.success('Editer un utilisateur', 'Enregistré');
                    } else if(response['violations'] !== undefined) {
                        let violations = response['violations'];
                        this.errors = {};
                        violations.forEach(item => {
                            this.errors[item['propertyPath']] = [item['message']];
                        });
                        toastr.error('Editer un utilisateur', 'Il y a des erreurs');
                    } 
                } else {
                    response = await fetchPatch(`${env['basUrl']}/api/users/${this.allUsers[this.elmIndex]['id']}`, this.userM, this.token);
                    loader(false);
                    if (response['id'] !== undefined) {
                        this.allUsers[this.elmIndex] = response;
                        toastr.success('Editer un utilisateur', 'Enregistré');
                    } else if(response['violations'] !== undefined) {
                        let violations = response['violations'];
                        this.errors = {};
                        violations.forEach(item => {
                            this.errors[item['propertyPath']] = [item['message']];
                        });
                        toastr.error('Editer un utilisateur', 'Il y a des erreurs');
                    } 
                }
            }
        },
        async switchAdmin(event, index) {
            await this.getToken();
            if (this.allUsers[index]['roles'].includes('ROLE_ADMIN'))this.allUsers[index]['roles'] = ['ROLE_USER'];
            else this.allUsers[index]['roles'] = ['ROLE_USER', 'ROLE_ADMIN'];
            let response = await fetchPatch(`${env['basUrl']}/api/users/${this.allUsers[index]['id']}`, {'roles':this.allUsers[index]['roles']}, this.token);
            if (response['id'] !== undefined) {
                toastr.success('Editer un rôle', 'Enregistré');
            } else {
                toastr.error('Editer un rôle', 'Il y a des erreurs');
            }
        },
        switchPasswordType(ref) {
            if (ref.type === 'password')ref.type = 'text';
            else ref.type = 'password';
        },
        async editPassword() {
            this.checkForm(this.$refs.passwordform);
            if (this.errors === void 0) {
                loader();
                await this.getToken();
                let response = await fetchPatch(`${env['basUrl']}/api/users/${this.allUsers[this.elmIndex]['id']}/password`, {password:this.userM['password']}, this.token);
                loader(false);
                if (response['id'] !== undefined) {
                    this.switchModal(false);
                    toastr.success('Editer un mot de passe', 'Enregistré');
                } else if(response['violations'] !== undefined) {
                    let violations = response['violations'];
                    this.errors = {};
                    violations.forEach(item => {
                        this.errors[item['propertyPath']] = [item['message']];
                    });
                    toastr.error('Editer un mot de passe', 'Il y a des erreurs');
                } 
            }
        }
    }))
})

Alpine.start();

