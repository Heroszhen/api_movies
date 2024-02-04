export function wait(seconds){
    return new Promise((resolve, reject) => {
        setTimeout(() => {
          resolve(1);
        }, seconds * 1000);
    });
}

export function loader(toShow = true) {
    let dom = document.querySelector('#loading');
    if (toShow === true)dom.style.display = 'flex';
    else dom.style.display = 'none';
}

export function resetDateToInputDate(date) {
    if (!['', null].includes(date)) {
        let tab = date.split("T");
        return tab[0];
    }
    return date;
}

export function readFile(file) {
    return new Promise((resolve, err) => {
        let reader = new FileReader();
        reader.onload = (e) => {
            resolve(e.target.result);
        };
        reader.readAsDataURL(file);
    });
}