document.querySelector(".checkout-btn").addEventListener("click", () => {
    const paymentMethod = document.querySelector("input[name='payment']:checked").value;
    
    if (paymentMethod === "upi") {
        var options = {
            "key": "rzp_test_hx0iKfup5dpdhj", // Replace with your Razorpay Key ID
            "amount": total * 100, // Amount in paisa (₹100 = 10000)
            "currency": "INR",
            "name": "Madz Thriftz",
            "description": "Order Payment",
            "image": "logow.jpeg", // Your Brand Logo
            "handler": function (response) {
                alert("Payment Successful! Payment ID: " + response.razorpay_payment_id);
                localStorage.removeItem("cart");
                window.location.href = "thriftpage.html"; // Redirect to Home Page
            },
            "theme": {
                "color": "#4CAF50"
            }
        };

        var rzp = new Razorpay(options);
        rzp.open();
    } else if (paymentMethod === "cod") {
        alert("Order Placed with Cash on Delivery!");
        localStorage.removeItem("cart");
        window.location.href = "thriftpage.html";
    } else {
        alert("Please select a payment method.");
    }
});
