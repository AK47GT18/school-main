document.addEventListener("DOMContentLoaded", function() {
    const cartItems = [];
    let totalItems = 0;
    let totalPrice = 0;

    function addToCart(product) {
        const itemName = product.dataset.name;
        const itemPrice = parseFloat(product.dataset.price);
        const itemImage = product.querySelector("img").src;
        const existingItem = cartItems.find(item => item.name === itemName);

        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cartItems.push({
                name: itemName,
                price: itemPrice,
                quantity: 1,
                image: itemImage
            });
        }

        totalItems += 1;
        totalPrice += itemPrice;
        updateCartDisplay();
    }

    function updateCartDisplay() {
        const cartItemsList = document.getElementById("cart-items");
        cartItemsList.innerHTML = "";

        cartItems.forEach(item => {
            const cartItem = document.createElement("li");
            cartItem.innerHTML = `
                <img src="${item.image}" alt="${item.name}" width="50">
                <span>${item.name}</span>
                <span>$${item.price}</span>
                <span>${item.quantity}</span>`;
            cartItemsList.appendChild(cartItem);
        });

        document.getElementById("total-items").textContent = totalItems;
        document.getElementById("total-price").textContent = totalPrice.toFixed(2);
    }

    document.querySelectorAll(".add-to-cart").forEach(button => {
        button.addEventListener("click", function() {
            const productCard = this.closest(".product-card");
            addToCart(productCard);
        });
    });

    document.querySelector('check-out').addEventListener('click', () => {
        if (cartItems.length > 0) {
            fetch('cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ items: cartItems })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Items successfully added to the database!');
                } else {
                    alert('There was an error processing your request.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your request.');
            });
        } else {
            alert('Your cart is empty!');
        }
    });
});
