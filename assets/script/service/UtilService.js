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

/**
 * 
 * @param {Array<Object>} tab 
 * @param {string} field 
 * @param {string} order ['asc' or 'desc']
 * @param {string} type [string, number, date]
 * 
 * @returns {Array<Object>}
 */
export function sortArray(tab, field, order, type) {
    let newTab = JSON.parse(JSON.stringify(tab));
    newTab.sort((current, next) => {
        switch (type) {
            case 'string':
                if (order === 'desc') return current[field].toString().localeCompare(next[field].toString());
                else return next[field].toString().localeCompare(current[field].toString());
            case 'number':
                if (order === 'desc') return parseFloat(current[field]) - parseFloat(next[field]);
                else return parseFloat(next[field]) - parseFloat(current[field]);
            case 'date':
                if (order === 'desc') return new Date(current[field]).getTime() - new Date(next[field]).getTime();
                else return new Date(next[field]).getTime() - new Date(current[field]).getTime();
            default:
          }
    });

    return newTab;
}