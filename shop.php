<!DOCTYPE html>
<html>
<head>
    <title>Shop</title>
    <link rel="stylesheet" href="shop.css">
</head>
<style>
    /* Shopping cart styles */
.cart {
  background-color: #fff;
  border: 1px solid #ddd;
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: 0 auto;
  text-align: center;
}

.cart-items {
  margin-bottom: 20px;
}

table#cartTable {
  width: 100%;
  margin-bottom: 20px;
}

table#cartTable th,
table#cartTable td {
  padding: 10px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

table#cartTable th:first-child,
table#cartTable td:first-child {
  text-align: left;
}

.cart-total {
  font-weight: bold;
  margin-bottom: 20px;
}

.cart-total:before {
  content: "Total: $";
}

form button.send {
  background-color: #4caf50;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-bottom: 10px;
}

button.close {
  background-color: #f44336;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button.close:hover {
  background-color: #d32f2f;
}

/* Responsive styles */
@media screen and (max-width: 600px) {
  .cart {
    padding: 10px;
  }

  table#cartTable th,
  table#cartTable td {
    padding: 5px;
  }

  form button.send,
  button.close {
    font-size: 14px;
    padding: 8px 16px;
  }
}

</style>
<body>
    <!-- Sidebar -->
    <?php include('sideBar.html'); ?>

    <!-- Rest of the page content -->
    <!-- View Cart button -->
    <form id="viewCartForm" method="post" action="shopaction.php">
        <button class="button view-cart" onclick="viewCart(event)">View Cart</button>
    </form>

    <div class="container">
        <div class="items">
            <!-- Product cards -->
            <div class="card-container" id="cardContainer"></div>
        </div>

        <!-- Cart items -->
        <div class="cart" id="shopping-cart" style="display:none">
            <div class="cart-items" id="cartItems" style="display: none;"></div>
            <table id="cartTable">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="cartTableBody"></tbody>
            </table>
            <div class="cart-total" id="cartTotal"></div>
            <form action="shopaction.php" method="POST">
                <input type="hidden" name="total" id="totalInput">
                <input type="hidden" name="cartItems" id="cartItemsInput">
                <button class="button send" type="submit">Send Request</button>
            </form>
            <button class="button close" onclick="closeCart()">Close</button>
        </div>
    </div>
    <script>
        // JavaScript functions for cart functionality
        const cartItems = [];

        function closeCart() {
            document.getElementById("shopping-cart").style.display = "none";
        }

        function addToCart(itemDescription, price) {
            const quantity = prompt('Enter the quantity:');
            if (quantity) {
                cartItems.push({ itemDescription, quantity, price });
                updateCart();
            }
        }

        function removeItem(index) {
            cartItems.splice(index, 1);
            updateCart();
        }

        function updateCart() {
            const cartTableBody = document.getElementById('cartTableBody');
            const cartTotal = document.getElementById('cartTotal');
            cartTableBody.innerHTML = '';
            let total = 0;
            cartItems.forEach((item, index) => {
                const { itemDescription, quantity, price } = item;
                const itemName = itemDescription;
                const itemPrice = price * quantity;
                total += itemPrice;
                const row = `
                    <tr>
                        <td>${itemName}</td>
                        <td>${quantity}</td>
                        <td>$${itemPrice}</td>
                        <td><button onclick="removeItem(${index})">Remove</button></td>
                    </tr>
                `;
                cartTableBody.innerHTML += row;
            });

            cartTotal.textContent = `${total}`;
            document.getElementById('totalInput').value = total;
            document.getElementById('cartItemsInput').value = JSON.stringify(cartItems);

            if (cartItems.length > 0) {
                document.getElementById('cartItems').style.display = 'block';
            } else {
                document.getElementById('cartItems').style.display = 'none';
            }
        }

        function viewCart(event) {
            event.preventDefault();
            updateCart();
            document.getElementById("shopping-cart").style.display = "block";
        }

        // Fill out the cards
        var productList = [
        { name: 'Romex 1200W', details: 'Inverter Charger\n Rated Power:1500VA/1200W\nDC Input:24VDC/100A\nSolar Charging Mode:\nRated Power:1000W\nMax Solar Voltage:102vdc ', type: 'Inverter', price: 230, imageUrl: './images/romex1200w.png' },
        { name: 'Romex 2400W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Inverter', price: 300, imageUrl: './images/romex1200w.png' },
        { name: 'Romex 3500W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Inverter', price: 400, imageUrl: './images/romex3500w.png' },
        { name: 'Livguard 200AH', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Battery', price: 210, imageUrl: './images/livguard.png' },
        { name: 'Digital Ammeter', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 15, imageUrl: './images/ammeter.png' },
        { name: 'Livguard 150AH', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Battery', price: 160, imageUrl: './images/liv150.png' },
        { name: 'Philadelphia 545W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Panel', price: 200, imageUrl: './images/phila.png' },
        { name: 'MC4', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 5, imageUrl: './images/mc4.png' },
        { name: 'Growatt 5000W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Inverter', price: 650, imageUrl: './images/growatt.png' },
        { name: 'Growatt Wifi', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Battery', price: 200, imageUrl: './images/hind.png' },
        { name: 'DC Cables', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 50, imageUrl: './images/growattwifi.png' },
        { name: 'Hind 200AH', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 300, imageUrl: './images/cablesdc.png' },
        { name: 'AC MCB', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 7, imageUrl: './images/mcbac.png' },
        { name: 'Fuse', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 10, imageUrl: './images/fuse.PNG' },
        { name: 'DC MCB', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 15, imageUrl: './images/mcbac.png' },
        { name: 'Romex 5500W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Inverter', price: 600, imageUrl: './images/romex3500w.png' },
        { name: 'Cable 6mm', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 200, imageUrl: './images/cableslb.png' },
        { name: 'Hestia 480W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Panel', price: 190, imageUrl: './images/panel400s.png' },
        { name: 'Hestia 605W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Panel', price: 240, imageUrl: './images/panel400s.png' },
        { name: 'Wifi-03', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 50, imageUrl: './images/Wifi-03.png' },
        { name: 'Livguard 2400W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Inverter', price: 250, imageUrl: './images/livinv.png' },
        { name: 'Livguard 1200W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Inverter', price: 170, imageUrl: './images/livinv.png' },
        { name: 'Luminous 200AH', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Battery', price: 200, imageUrl: './images/luminous.png' },
        { name: 'Smart Plug', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Accessories', price: 13, imageUrl: './images/plug.png' },
        { name: 'Luminous 230AH', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Battery', price: 230, imageUrl: './images/luminous.png' },
        { name: 'JINKO 545W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Panel', price: 200, imageUrl: './images/panel400s.png' },
        { name: 'Hestia 500W', details: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', type: 'Panel', price: 200, imageUrl: './images/panel400s.png' }
        // Add more product objects as needed
        ];

        function createCard(name, details, type, price, imageUrl) {
            var cardDiv = document.createElement('div');
            cardDiv.classList.add('card');

            var image = document.createElement('img');
            image.src = imageUrl;
            image.alt = name;

            var cardContentDiv = document.createElement('div');
            cardContentDiv.classList.add('card-content');

            var productName = document.createElement('h3');
            productName.textContent = name;

            var productDetails = document.createElement('p');
            productDetails.textContent = 'Details: ' + details;

            var productType = document.createElement('p');
            productType.textContent = 'Type: ' + type;

            var productPrice = document.createElement('p');
            productPrice.textContent = 'Price: $' + price;

            var addToCartButton = document.createElement('button');
            addToCartButton.classList.add('button', 'add');
            addToCartButton.textContent = 'Add to Cart';
            addToCartButton.addEventListener('click', function() {
                addToCart1(name, price);
            });

            cardContentDiv.appendChild(productName);
            cardContentDiv.appendChild(productDetails);
            cardContentDiv.appendChild(productType);
            cardContentDiv.appendChild(productPrice);
            cardContentDiv.appendChild(addToCartButton);

            cardDiv.appendChild(image);
            cardDiv.appendChild(cardContentDiv);

            return cardDiv;
        }

        function addToCart1(name, price) {
            addToCart(name, price)
        }

        // Generate card divs for each product in the productList array
        var cardContainer = document.getElementById('cardContainer');
        productList.forEach(function(product) {
        var card = createCard(product.name, product.details, product.type, product.price, product.imageUrl);
        cardContainer.appendChild(card);
        });
    </script>
</body>
</html>
