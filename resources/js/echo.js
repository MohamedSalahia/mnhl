import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

window.Pusher = Pusher;

let port = 6001;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    // wsHost: import.meta.env.VITE_REVERB_HOST,
    // wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    // wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    wsHost: window.location.hostname,
    wsPort: port,
    wssPort: port,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

$(function () {

    initEcho();

});//end of document ready

window.initEcho = () => {

    let userId = $('meta[name="user-id"]').attr('content');

    if (window.Echo) {

        window.Echo.leaveAllChannels();
    }

    if (userId) {

        window.Echo.private(`users.${userId}`)
            .notification((notification) => {

                let unreadNotificationsCount = 0;

                var audio = new Audio('/sounds/notification.wav');
                audio.play();

                switch (notification.type) {

                    case "App\\Notifications\\RequisitionCreated":
                    case "App\\Notifications\\RequisitionRoleStatusUpdated":
                        unreadNotificationsCount = notification.unread_notifications_count;
                        $('#unread-notifications-count').text(unreadNotificationsCount < 9 ? unreadNotificationsCount : '+9')
                        break;

                }//end of switch

            });

    }//end of if

}