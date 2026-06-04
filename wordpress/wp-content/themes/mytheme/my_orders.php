<?php
/*
Template Name: My Orders
*/
require_once get_theme_file_path( 'functions_my_order.php' );
get_header();
$currency_symbol = '₱';
?>
	<main>
        <?php get_template_part( 'nav' ); ?>
        <section class="content">
            <aside class="content_aside">
                <h2>My Account</h2>
                <div>
                    <button style="background-color: #000; color: #fff;" onclick="window.location.href='<?php echo esc_url( home_url( '/my-order/' ) ); ?>'">
                        <span>
                            <svg width="220" height="220" viewBox="0 0 220 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <polygon points="110,30 180,70 110,110 40,70" stroke="white" stroke-width="4" fill="none"/>
                                <polygon points="40,70 110,110 110,190 40,150" stroke="white" stroke-width="4" fill="none"/>
                                <polygon points="180,70 110,110 110,190 180,150" stroke="white" stroke-width="4" fill="none"/>
                            </svg>
                        </span>My Orders
                    </button>
                    <button style="opacity: 0.7;" onclick="window.location.href='<?php echo esc_url( home_url( '/address/' ) ); ?>'">
                        <span>
                            <svg style="color: #000;" width="220" height="220" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 21C12 21 19 14.5 19 9.5C19 5.35786 15.866 2 12 2C8.13401 2 5 5.35786 5 9.5C5 14.5 12 21 12 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="9.5" r="2.5" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </span>Addresses
                    </button>
                    <button style="opacity: 0.7;" onclick="window.location.href='<?php echo esc_url( home_url( '/settings/' ) ); ?>'">
                        <span>
                            <svg style="color:#000;" width="220" height="220" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8.5A3.5 3.5 0 1 1 12 15.5A3.5 3.5 0 1 1 12 8.5Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M19.4 15A1.7 1.7 0 0 0 19.74 16.87L19.8 16.94A2 2 0 1 1 16.97 19.77L16.9 19.71A1.7 1.7 0 0 0 15.03 19.37A1.7 1.7 0 0 0 14 21V21.2A2 2 0 1 1 10 21.2V21A1.7 1.7 0 0 0 8.97 19.37A1.7 1.7 0 0 0 7.1 19.71L7.03 19.77A2 2 0 1 1 4.2 16.94L4.26 16.87A1.7 1.7 0 0 0 4.6 15.03A1.7 1.7 0 0 0 3 14H2.8A2 2 0 1 1 2.8 10H3A1.7 1.7 0 0 0 4.6 8.97A1.7 1.7 0 0 0 4.26 7.1L4.2 7.03A2 2 0 1 1 7.03 4.2L7.1 4.26A1.7 1.7 0 0 0 8.97 4.6H9A1.7 1.7 0 0 0 10 3V2.8A2 2 0 1 1 14 2.8V3A1.7 1.7 0 0 0 15.03 4.6A1.7 1.7 0 0 0 16.9 4.26L16.97 4.2A2 2 0 1 1 19.8 7.03L19.74 7.1A1.7 1.7 0 0 0 19.4 8.97V9A1.7 1.7 0 0 0 21 10H21.2A2 2 0 1 1 21.2 14H21A1.7 1.7 0 0 0 19.4 15Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>Settings
                    </button>
                    <button style="color: #f00;">
                        <span>
                           <svg style="color:#f00;" width="220" height="220" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 3H5C4.44772 3 4 3.44772 4 4V20C4 20.5523 4.44772 21 5 21H10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14 17L19 12L14 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M19 12H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>Sign Out
                    </button>
                </div>
            </aside>
            <div class="product_sale_cons">
                <h2>Order History</h2>
                <div id="mytheme-order-history"></div>
            </div>
        </section>
    </main>
    <script>
    (function() {
        var holder = document.getElementById('mytheme-order-history');
        var currencySymbol = <?php echo wp_json_encode( $currency_symbol ); ?>;
        var cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('mytheme_cart') || '[]');
        } catch (e) {
            cart = [];
        }

        if (!cart.length) {
            holder.innerHTML = '<div class="empty-cart"><h2>No orders yet</h2><p>Checkout a product first.</p></div>';
            return;
        }

        holder.innerHTML = cart.map(function(item) {
            var price = parseFloat(item.price_amount);
            if (isNaN(price) || price <= 0) {
                price = parseFloat(String(item.price_text || '').replace(/[^0-9.,]/g, '').replace(/,/g, '')) || 0;
            }

            var quantity = parseInt(item.quantity, 10) || 1;
            var lineTotal = price * quantity;

            return (
                '<div class="product_sale_cons_ordered">' +
                    '<span class="product_sale_cons_status">' +
                        '<p style="opacity: 0.5;">Order #' + item.product_id + '</p>' +
                        '<p>Place on ' + new Date().toLocaleDateString() + '</p>' +
                        '<span class="product_sale_cons_status_icons">' +
                            '<p style="font-size: 12px; color: #166534;">Confirmed</p>' +
                        '</span>' +
                        '<h3>' + currencySymbol + lineTotal.toFixed(2) + '</h3>' +
                    '</span>' +
                    '<div class="product_sale_cons_relative">' +
                        '<figure class="product_sale_cons_figure">' +
                            '<img src="' + item.image + '" alt="' + item.title + '">' +
                            '<figcaption>' +
                                '<span>' +
                                    '<h4>' + item.title + '</h4>' +
                                    '<p style="opacity: 0.5;">Size: ' + (item.size || '10') + ' • Qty: ' + quantity + '</p>' +
                                '</span>' +
                                '<p style="cursor: pointer;">Track Package</p>' +
                            '</figcaption>' +
                        '</figure>' +
                    '</div>' +
                '</div>'
            );
        }).join('');
    })();
    </script>
<?php get_footer(); ?>
