// A simple function to handle the product click event
function showProductDetails(productId) {
    // This could redirect the user to a new page with product details
    // For example: /product-page.html?product=product1
    window.location.href = `product.html?product=${productId}`;
}
 // product.html (Product Page)
function selectProduct(name, images, price) {
    const product = { name, images, price };
    localStorage.setItem('selectedProduct', JSON.stringify(product));
    window.location.href = 'product.html';
}

// product-view.html (Product Viewing Page)
document.addEventListener('DOMContentLoaded', () => {
    const product = JSON.parse(localStorage.getItem('selectedProduct'));
    if (product) {
        const mainImage = document.getElementById('main-image');
        mainImage.src = product.images[0];
        document.getElementById('product-name').textContent = product.name;
        document.getElementById('product-price').textContent = `${product.price}`;

        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.src = product.images[index];
            thumbnail.addEventListener('click', () => {
                mainImage.src = product.images[index];
                mainImage.classList.add('zoom-animation');
                setTimeout(() => mainImage.classList.remove('zoom-animation'), 300);
            });
        });
    } else {
        document.getElementById('product-container').innerHTML = '<p>Product not found.</p>';
    }
});

// CSS (product.css)
// .zoom-animation {
//     transform: scale(1.2);
//     transition: transform 0.3s ease;
// }

// HTML Structure (product-view.html)
// <div id="product-container">
//     <h1 id="product-name"></h1>
//     <div class="image-gallery">
//         <img id="main-image" alt="Main Product Image" width="300" height="300" />
//         <div class="thumbnails">
//             <img class="thumbnail" alt="Thumbnail 1" width="100" height="100" />
//             <img class="thumbnail" alt="Thumbnail 2" width="100" height="100" />
//             <img class="thumbnail" alt="Thumbnail 3" width="100" height="100" />
//         </div>
//     </div>
//     <p id="product-price"></p>
// </div>

// Example Usage (product.html)
// <button onclick="selectProduct('Cool T-Shirt', ['./img/tshirt1.jpg', './img/tshirt2.jpg', './img/tshirt3.jpg'], 19.99)">View Product</button>
document.querySelector(".cart-button").addEventListener("click", () => {
    const productName = document.getElementById("product-name").innerText;
    const productPrice = document.getElementById("product-price").innerText.replace("₹", "").trim();
    const productImage = document.getElementById("main-image").src;

    let cart = JSON.parse(localStorage.getItem("cart")) || [];

    let product = {
        name: productName,
        price: parseFloat(productPrice),
        image: productImage,
        quantity: 1
    };

    // Check if product already exists in cart
    const existing = cart.find(item => item.name === product.name);
    if (existing) {
        existing.quantity++;
    } else {
        cart.push(product);
    }

    localStorage.setItem("cart", JSON.stringify(cart));

    // Display Message in Page
    const cartMessage = document.querySelector(".cart-message");
    cartMessage.innerText = "Added to Cart ✅";
    cartMessage.style.display = "block"; // Show message
    cartMessage.style.color = "#4CAF50"; // Green color

    // Hide message after 3 seconds
    setTimeout(() => {
        cartMessage.style.display = "none";
    }, 3000);});
















