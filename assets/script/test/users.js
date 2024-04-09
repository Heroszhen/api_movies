import $ from 'jquery';
import '../../styles/test.scss';

$(() => {
   getUsers();
});

function getUsers() {
    $.get("https://randomuser.me/api/?gender=female&results=20", function(data) {
        //console.log(data.results);
        data.results.forEach(user => {
            $('#girls').append(`
                <div class='girl'>
                    <h5>${user['name']['first']} ${user['name']['last']}</h5>
                    <img src='${user['picture']['large']}'>
                </div>
            `);
        })
    });
}

$('#girls').on('scroll', function(){
    if ($(this).scrollTop() + $(this).height() + 20 === $(this).prop('scrollHeight')) {
        getUsers();
    }
});

$('#girls').on('click', 'img', function(){
    $('#big-img').remove();
    const parent = $(this).parent();
    const clientReact = $(this)[0].getBoundingClientRect();
    let newImg = $(`<img src='${$(this).attr('src')}' alt='' class='position-absolute' id='big-img'>`).css({
        'width': `${$(this).width()}px`,
        'height': `${$(this).height()}px`,
        'top': clientReact.top,
        'left': clientReact.left
    });
    parent.append(newImg);
    
    newImg.animate(
        {
            top: $('body').height() - window.innerHeight / 2  -  newImg.height() / 2,
            left: (window.innerWidth - newImg.width()) / 2
        },
        1000,
        function() {
            const newWidth = 700;
            const newHeight = newWidth / $(this).width() * $(this).height();
            $(this).animate({
                'width': `${newWidth}px`,
                'height': `${newHeight}px`,
                top: $('body').height() - window.innerHeight / 2  -  newWidth / 2,
                left: (window.innerWidth - newHeight) / 2
            }, 1000);
        }
    );
});
