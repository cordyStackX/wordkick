<?php
/*
Template Name: Checkout
*/
require_once get_theme_file_path( 'functions4.php' );
get_header();
$currency_symbol = '₱';
?>
    <main>
        <?php get_template_part( 'nav' ); ?>
        <section class="checkout-container">
            <div class="checkout-card">
                <div class="checkout-header">
                    <span class="checkout-kicker">Order Summary</span>
                    <h1 class="checkout-title">Checkout Complete</h1>
                    <p class="checkout-text">Review your order before continuing.</p>
                </div>
                <div class="checkout-meta">
                    <div>
                        <span>Payment</span>
                        <strong>Secure</strong>
                    </div>
                    <div>
                        <span>Status</span>
                        <strong>Confirmed</strong>
                    </div>
                </div>
                <div class="checkout-summary" id="mytheme-checkout-summary"></div>
                <a class="checkout-order-btn" href="<?php echo esc_url( home_url( '/my-order/' ) ); ?>">Your Order</a>
            </div>
        </section>
    </main>
    <script>
    (function() {
        var summaryEl = document.getElementById('mytheme-checkout-summary');
        var currencySymbol = <?php echo wp_json_encode( $currency_symbol ); ?>;
        var cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('mytheme_cart') || '[]');
        } catch (e) {
            cart = [];
        }

        if (!cart.length) {
            summaryEl.innerHTML = '<p class="checkout-empty">Your cart is empty.</p>';
            return;
        }

        function getItemPrice(item) {
            var amount = parseFloat(item.price_amount);
            if (!isNaN(amount) && amount > 0) {
                return amount;
            }
            var cleaned = String(item.price_text || '').replace(/[^0-9.,]/g, '').replace(/,/g, '');
            var value = parseFloat(cleaned);
            return isNaN(value) ? 0 : value;
        }

        var subtotal = 0;
        summaryEl.innerHTML = cart.map(function(item) {
            var price = getItemPrice(item);
            var quantity = parseInt(item.quantity, 10) || 1;
            var lineTotal = price * quantity;
            subtotal += lineTotal;

            return (
                '<div class="checkout-line">' +
                    '<img src="' + item.image + '" alt="' + item.title + '">' +
                    '<div>' +
                        '<h3>' + item.title + '</h3>' +
                        '<p>' + item.brand + ' • Size ' + (item.size || '10') + ' • Qty ' + quantity + '</p>' +
                    '</div>' +
                    '<strong>' + currencySymbol + lineTotal.toFixed(2) + '</strong>' +
                '</div>'
            );
        }).join('') + '<div class="checkout-line checkout-total"><span>Total</span><strong>' + currencySymbol + subtotal.toFixed(2) + '</strong></div>';
    })();
    </script>
<?php get_footer(); ?>
