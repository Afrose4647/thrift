$(document).ready(function () {
    $(".add-to-cart").click(function () {
        let productId = $(this).data("id");

        $.ajax({
            url: "cart/add_to_cart.php",
            type: "POST",
            data: { product_id: productId },
            success: function (response) {
                alert("Product added to cart!");
            }
        });
    });
});
