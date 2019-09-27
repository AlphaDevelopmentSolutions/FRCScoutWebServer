<script src="<?php echo JS_URL ?>material.min.js"></script>

<!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/7.0.0/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/7.0.0/firebase-analytics.js"></script>

<script>
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyCNBlYMVlfzg2tnm4nrOelJQeupyIZrHsU",
        authDomain: "frcscout-568f6.firebaseapp.com",
        databaseURL: "https://frcscout-568f6.firebaseio.com",
        projectId: "frcscout-568f6",
        storageBucket: "",
        messagingSenderId: "217379215788",
        appId: "1:217379215788:web:ccec1d8a7ce8682517c209",
        measurementId: "G-4B7NY1M0VC"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();

    var performance_standalone = 'https://www.gstatic.com/firebasejs/6.6.2/firebase-performance-standalone.js';

    (function(sa,fbc){function load(f,c){var a=document.createElement('script');
        a.async=1;a.src=f;var s=document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(a,s);}load(sa);window.onload = function() {firebase.initializeApp(fbc).performance();};
    })(performance_standalone, firebaseConfig);
</script>
