// Product Upload - Read JSON and Display Products
document.getElementById('file-input').addEventListener('change', handleFileUpload);

function handleFileUpload(event) {
    const file = event.target.files[0];
    if (file && file.type === 'application/json') {
        const reader = new FileReader();
        reader.onload = function (e) {
            const data = JSON.parse(e.target.result);
            displayProducts(data);
            localStorage.setItem('products', JSON.stringify(data)); // Store products in localStorage
        };
        reader.readAsText(file);
    } else {
        alert('Please upload a valid JSON file.');
    }
}

function displayProducts(products) {
    const productList = document.getElementById('product-list');
    productList.innerHTML = ''; // Clear previous data

    products.forEach(product => {
        const productCard = document.createElement('div');
        productCard.classList.add('product-card');

        productCard.innerHTML = `
            <h2>${product.name}</h2>
            <img src="${product.images[0]}" alt="${product.name}" width="200">
            <p>Price: ${product.price}</p>
            <button onclick="selectProduct('${product.name}', ${JSON.stringify(product.images)}, '${product.price}')">
                View Product
            </button>
        `;

        productList.appendChild(productCard);
    });
}

function selectProduct(name, images, price) {
    const product = { name, images, price };
    localStorage.setItem('selectedProduct', JSON.stringify(product));
    window.location.href = 'product1.html'; // Redirect to product view page
}
