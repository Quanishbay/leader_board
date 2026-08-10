importScripts('https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.8.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyB5TMgLCdZ2LNSCU0TrMQp4DLGa604TMJQ",
    authDomain: "leaderboard-ed326.firebaseapp.com",
    projectId: "leaderboard-ed326",
    storageBucket: "leaderboard-ed326.firebasestorage.app",
    messagingSenderId: "978551092470",
    appId: "1:978551092470:web:b219b8e81f2b36a93ae8d7"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/favicon.ico'
    };

    self.registration.showNotification(notificationTitle, notificationOptions);
});
