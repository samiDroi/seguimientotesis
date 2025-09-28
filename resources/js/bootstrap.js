/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
*/
import $ from "jquery";
window.$ = $;
window.jQuery = $;
import 'bootstrap'
import select2 from "select2"
import 'select2/dist/css/select2.css'
import 'select2-bootstrap-5-theme/dist/select2-bootstrap-5-theme.css'
select2($);

$(function () {
    $(".select2").select2({
        theme: "bootstrap-5",
        dropdownParent: $("#tesisModal")
    });
});


// 📌 Importar DataTables con estilo Bootstrap 5
import dt from 'datatables.net-bs5';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';

// 📌 Importar extensión Responsive (si la usas)
import dtResponsive from 'datatables.net-responsive-bs5';
import 'datatables.net-responsive-bs5/css/responsive.bootstrap5.css';

// // Inicializar DataTables con jQuery
// dt(window, $);
// dtResponsive(window, $);

import axios from 'axios';
window.axios = axios;

import * as Popper from '@popperjs/core'
window.Popper = Popper



window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

