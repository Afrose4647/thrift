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
const db = firebase.firestore();

function showMessage(text) {
    const messageDiv = document.getElementById("message");
    messageDiv.textContent = text;
    messageDiv.style.display = "block";
    setTimeout(() => {
        messageDiv.style.display = "none";
    }, 3000);
}

function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    auth.signInWithEmailAndPassword(email, password).then(() => {
        showMessage("Login Successful");
        loadProducts();
    }).catch(err => alert(err.message));
}

function uploadImage() {
    const file = document.getElementById("image").files[0];
    if (!file) {
        alert("Please select an image.");
        return;
    }
    const formData = new FormData();
    formData.append("image", file);

    fetch("https://api.imgbb.com/1/upload?key=3b7a4c1077c49c890c0e45249a6c870d", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const imageUrl = data.data.url;
            showMessage("Image Uploaded Successfully");
            addProduct(imageUrl);
        } else {
            alert("Image Upload Failed");
        }
    })
    .catch(err => alert("Image Upload Error: " + err));
}

function addProduct(imageUrl) {
    const name = document.getElementById("name").value;
    const price = document.getElementById("price").value;
    const description = document.getElementById("description").value;

    if (!name || !price || !description) {
        alert("Please fill all fields");
        return;
    }

    db.collection("products").add({
        name: name,
        price: price,
        description: description,
        image: imageUrl
    }).then(() => {
        showMessage("Product Added Successfully");
        loadProducts();
    }).catch(err => alert("Product Upload Failed: " + err));
}

function deleteProduct(id) {
    db.collection("products").doc(id).delete().then(() => {
        showMessage("Product Deleted Successfully");
        loadProducts();
    }).catch(err => alert("Failed to Delete Product: " + err));
}

function loadProducts() {
    const productList = document.getElementById("productList");
    productList.innerHTML = "";
    db.collection("products").get().then(snapshot => {
        snapshot.forEach(doc => {
            const product = doc.data();
            productList.innerHTML += `<div class='card mb-3'><img src='${product.image}' class='card-img-top' style='height: 200px;'><div class='card-body'><h5>${product.name}</h5><p>${product.description}</p><p>$${product.price}</p><button onclick="deleteProduct('${doc.id}')" class='btn btn-danger'>Delete</button></div></div>`;
        });
    }).catch(err => alert("Failed to Load Products: " + err));
}