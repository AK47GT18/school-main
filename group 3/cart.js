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
            productDiv.innerHTML =` <table>
        <tr>
            <td><img id="cart-img" src="${product.Image}" alt="${product.Name}"></td>
            <td><label for="cart-img" id="Cart-ProductName">${product.Name}</label></td>
            <td><label for="Price" id="Cart-ProductPrice">${product.Price}</label></td>
            <td> <input type="text" placeholder ="0" id="NumberOfProducts"></td>
        </tr>
    </table>`;
            cartItemsContainer.appendChild(productDiv);

            
            totalPrice += parseFloat(product.Price.replace('MWK', ''));
        });

        
        const productTotalInput = document.getElementById("ProductTotal");
        if (productTotalInput) {
            productTotalInput.value = `MWK ${totalPrice}`;
        }
    } else {
        document.querySelector(".cart-item-container").innerHTML = "No products in local storage";
    }
});
