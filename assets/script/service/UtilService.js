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