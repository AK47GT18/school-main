document.addEventListener("DOMContentLoaded", () => {
    const storedProducts = localStorage.getItem('SelectedProducts');
    
    if (storedProducts) {
        const productList = JSON.parse(storedProducts);
        const cartItemsContainer = document.querySelector(".cart-item-container");
        cartItemsContainer.innerHTML = "";

        let totalPrice = 0;

        productList.forEach(product => {
            const productDiv = document.createElement("div");
            productDiv.classList.add("cart-items");
            productDiv.innerHTML = `
                <img id="cart-img" src="${product.Image}" alt="${product.Name}">
                <label for="cart-img" id="Cart-ProductName">${product.Name}</label>
                <label for="Price" id="Cart-ProductPrice">${product.Price}</label>
                <input type="text" placeholder ="0" id="NumberOfProducts">`;
            cartItemsContainer.appendChild(productDiv);

            
            totalPrice += parseFloat(product.Price.replace('MWK', ''));
        });

        
        const productTotalInput = document.getElementById("ProductTotal");
        if (productTotalInput) {
            productTotalInput.value = `MWK ${totalPrice.toFixed(2)}`;
        }
    } else {
        document.querySelector(".cart-item-container").innerHTML = "No products in local storage";
    }
});
