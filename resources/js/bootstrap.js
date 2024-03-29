window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */
try {
    window.moment = require('moment/moment.js');
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');
    require('select2/dist/js/select2.min.js');
    require('air-datepicker/dist/js/datepicker.min.js')
    require('air-datepicker/dist/js/i18n/datepicker.en.js')
    require('timepicker/jquery.timepicker.min.js')
    window.toastr = require('toastr/build/toastr.min.js')
    require('bootstrap');
    window._ = require('underscore/underscore-min.js')
    require( 'jszip' );
} catch (e) {}

toastr.options = {
    "progressBar": true,
    "closeButton": true,
    "positionClass": "toast-bottom-right",

}
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Access-Control-Allow-Credentials'] = true;
window.axios.defaults.withCredentials = true;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "e82681024e058d3bc19b",
    cluster: 'ap1',
    forceTLS: true
});

