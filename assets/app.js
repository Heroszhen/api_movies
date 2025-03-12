/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉')


import 'bootstrap/dist/css/bootstrap.min.css';
import 'toastr/build/toastr.min.css';
import '@popperjs/core';
import 'bootstrap';
import $ from 'jquery';
import 'tui-pagination/dist/tui-pagination.min.css';

if ($('#admin-home').length > 0) {
    import('./script/socket.js'); 
}

if ($('#test-chartjs').length) {
    import('./script/test/chartjs.js'); 
}

if ($('#milleret-registration').length) {
    import('./script/test/milleret_registration.js'); 
}
