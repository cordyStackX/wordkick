<?php
/*
Template Name: Cart
*/
require_once get_theme_file_path( 'functions4.php' );
get_header();
$currency_symbol = '₱';
?>
	<main>
        <?php get_template_part( 'nav' ); ?>
        <section class="cart-container">
            <h1 class="cart-title">Shopping Cart</h1>
            <div class="cart-layout">
                <div class="cart-items" id="mytheme-cart-items">
                    <div class="empty-cart">
                        <h2>Your cart is empty</h2>
                        <p>Go back and add a product.</p>
                    </div>
                </div>
                <aside class="cart-summary">
                    <h2 class="summary-title">Order Summary</h2>
                    <div class="summary-rows">
                        <div class="summary-row"><span>Subtotal</span><span id="mytheme-cart-subtotal">$0.00</span></div>
                        <div class="summary-row"><span>Shipping</span><span>Calculated at checkout</span></div>
                        <div class="summary-row"><span>Tax</span><span>Calculated at checkout</span></div>
                        <div class="summary-row total"><span>Estimated Total</span><span id="mytheme-cart-total">$0.00</span></div>
                    </div>
                    <a class="checkout-btn" href="<?php echo esc_url( home_url( '/checkout/' ) ); ?>">
                        Proceed to Checkout
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M3.333 8h9.334M8 3.333L12.667 8 8 12.667" stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <p class="continue-shopping">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">or Continue Shopping</a>
                    </p>
                </aside>
            </div>
        </section>
    </main>
    <script>
    (function() {
        var storageKey = 'mytheme_cart';
        var itemsEl = document.getElementById('mytheme-cart-items');
        var subtotalEl = document.getElementById('mytheme-cart-subtotal');
        var totalEl = document.getElementById('mytheme-cart-total');

        function getCart() {
            try {
                return JSON.parse(localStorage.getItem(storageKey) || '[]');
            } catch (e) {
                return [];
            }
        }

        function saveCart(cart) {
            localStorage.setItem(storageKey, JSON.stringify(cart));
        }

        function parsePrice(priceText) {
            var cleaned = String(priceText || '').replace(/[^0-9.,]/g, '').replace(/,/g, '');
            var value = parseFloat(cleaned);
            return isNaN(value) ? 0 : value;
        }

        function getItemPrice(item) {
            var amount = parseFloat(item.price_amount);
            if (!isNaN(amount) && amount > 0) {
                return amount;
            }
            return parsePrice(item.price_text);
        }

        var currencySymbol = <?php echo wp_json_encode( $currency_symbol ); ?>;

        function formatMoney(value) {
            return currencySymbol + value.toFixed(2);
        }

        function render() {
            var cart = getCart();
            var subtotal = 0;

            if (!cart.length) {
                itemsEl.innerHTML = '<div class="empty-cart"><h2>Your cart is empty</h2><p>Go back and add a product.</p></div>';
                subtotalEl.textContent = formatMoney(0);
                totalEl.textContent = formatMoney(0);
                return;
            }

            itemsEl.innerHTML = cart.map(function(item, index) {
                var unitPrice = getItemPrice(item);
                var quantity = parseInt(item.quantity, 10) || 1;
                var itemCurrency = item.currency_symbol || currencySymbol;
                var lineTotal = unitPrice * quantity;
                subtotal += lineTotal;

                return (
                    '<div class="cart-item" data-index="' + index + '">' +
                        '<div class="item-image"><img src="' + item.image + '" alt="' + item.title + '"></div>' +
                        '<div class="item-details">' +
                            '<div class="item-info">' +
                                '<h3 class="item-name">' + item.title + '</h3>' +
                                '<p class="item-variant">' + item.brand + ' &bull; Size ' + (item.size || '10') + '</p>' +
                            '</div>' +
                            '<div class="item-actions">' +
                                '<div class="item-qty">' +
                                    '<button class="qty-btn" type="button" data-action="decrease" aria-label="Decrease">−</button>' +
                                    '<input class="qty-val" type="number" value="' + quantity + '" min="1" step="1" aria-label="Quantity">' +
                                    '<button class="qty-btn" type="button" data-action="increase" aria-label="Increase">+</button>' +
                                '</div>' +
                                '<button class="item-remove" type="button" aria-label="Remove">' +
                                    '<svg width="16" height="16" viewBox="0 0 16 16" fill="none">' +
                                        '<path d="M2 4h12M5.333 4V2.667a1.333 1.333 0 0 1 1.334-1.334h2.666a1.333 1.333 0 0 1 1.334 1.334V4M12.667 4v9.333a1.333 1.333 0 0 1-1.334 1.334H4.667a1.333 1.333 0 0 1-1.334-1.334V4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                                        '<path d="M6.667 7.333v4M9.333 7.333v4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>' +
                                    '</svg>' +
                                    'Remove' +
                                '</button>' +
                            '</div>' +
                        '</div>' +
                        '<span class="item-price">' + itemCurrency + lineTotal.toFixed(2) + '</span>' +
                    '</div>'
                );
            }).join('');

            subtotalEl.textContent = formatMoney(subtotal);
            totalEl.textContent = formatMoney(subtotal);
        }

        itemsEl.addEventListener('click', function(event) {
            var button = event.target.closest('button');
            if (!button) return;

            var itemEl = event.target.closest('.cart-item');
            if (!itemEl) return;

            var index = parseInt(itemEl.dataset.index, 10);
            var cart = getCart();
            var item = cart[index];
            if (!item) return;

            if (button.dataset.action === 'increase' || button.dataset.action === 'decrease') {
                var currentQty = parseInt(item.quantity, 10) || 1;
                item.quantity = Math.max(1, currentQty + (button.dataset.action === 'increase' ? 1 : -1));
                saveCart(cart);
                render();
                return;
            }

            if (button.classList.contains('item-remove')) {
                cart.splice(index, 1);
                saveCart(cart);
                render();
            }
        });

        itemsEl.addEventListener('change', function(event) {
            if (!event.target.classList.contains('qty-val')) return;
            var itemEl = event.target.closest('.cart-item');
            var index = parseInt(itemEl.dataset.index, 10);
            var cart = getCart();
            var item = cart[index];
            if (!item) return;
            item.quantity = Math.max(1, parseInt(event.target.value, 10) || 1);
            saveCart(cart);
            render();
        });

        render();
    })();
    </script>
<?php get_footer(); ?>
