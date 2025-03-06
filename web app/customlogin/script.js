const firebaseConfig = {
    apiKey: "AIzaSyA9sSG63b79wYqnUfPFXqBE-JHsEtXe9nk",
    authDomain: "madzthriftz-a0b1e.firebaseapp.com",
    projectId: "madzthriftz-a0b1e",
    storageBucket: "madzthriftz-a0b1e.appspot.com",
    messagingSenderId: "820149104704",
    appId: "1:820149104704:web:fdeea00b54eda2e3682fa5"
};

firebase.initializeApp(firebaseConfig);
const auth = firebase.auth();

function showMessage(text, icon) {
    Swal.fire({
        title: text,
        icon: icon,
        timer: 2000,
        showConfirmButton: false
    });
}

function register() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    auth.createUserWithEmailAndPassword(email, password)
        .then(() => {
            showMessage("Account Created Successfully!", "success");
            window.location.href = "index.html";
        })
        .catch(error => {
            showMessage(error.message, "error");
        });
}

function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    auth.signInWithEmailAndPassword(email, password)
        .then(() => {
            showMessage("Login Successful!", "success");
            window.location.href = "thriftpage.html";
        })
        .catch(error => {
            showMessage(error.message, "error");
        });
}

function logout() {
    auth.signOut().then(() => {
        window.location.href = "index.html";
    });
}
