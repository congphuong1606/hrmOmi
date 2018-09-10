importScripts('https://www.gstatic.com/firebasejs/5.0.4/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.0.4/firebase-messaging.js');


firebase.initializeApp({
    'messagingSenderId': '721007937213'
});

const messaging = firebase.messaging();
