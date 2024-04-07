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

