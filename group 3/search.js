function searchProducts() {
    const searchTerm = document.querySelector('.srh').value;
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'search-products.php?q=' + encodeURIComponent(searchTerm), true);
    xhr.onload = function() {
        if (this.status === 200) {
            const products = JSON.parse(this.responseText);
            let output = '';
            if (products.length > 0) {
                products.forEach(product => {
                    output += `
                        <div class="items">
                            <p class="description">${product.name}</p>
                            <p class="Price-description">Price: $${product.price}</p>
                            <img class="ProductImg" src="${product.image}" alt="${product.name}" style="width:100px;">
                            <input class="btn3"   type="button" value="Add To Cart">
                            </div>
                    `;
                });
            } else {
                output = '<p>No products found</p>';
            }
            document.querySelector('.product-list').innerHTML = output;
        }
    };
    xhr.send();
}
document.querySelector(".items").addEventListener('click',function{
    
});