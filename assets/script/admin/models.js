class AbstractModel {
    constructor() {
        if(this.constructor == AbstractModel) {
            throw new Error("Class is of abstract type and can't be instantiated");
        };
    }

    setData(source) {
        Object.assign(this, source);
    }
}
 

const User = class {
    email = null;
    roles = ['ROLE_USER'];
    password = null;
    lastname = null;
    firstname= null;
};

class Actress extends AbstractModel{
    name = null;
    birthday = null;
    country = null;
    description = null;
    invoiceFile = null;
    photoName = null;

    setPhoto(file) {
        this.invoiceFile = file;
        delete this.photoName;
        delete this.photoSize;
        delete this.photoMimeType;
        delete this.photoOriginalName;
        delete this.photoDimensions;
    }
}

export {User, Actress};
export default User;