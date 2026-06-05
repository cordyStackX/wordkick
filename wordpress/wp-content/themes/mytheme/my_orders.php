<?php
/*
Template Name: My Orders
*/
require_once get_theme_file_path( 'functions_my_order.php' );
get_header();
$currency_symbol = '₱';

global $wpdb;
$order_table    = $wpdb->prefix . 'wc_orders';
$items_table    = $wpdb->prefix . 'woocommerce_order_items';
$itemmeta_table = $wpdb->prefix . 'woocommerce_order_itemmeta';
$order_id       = isset( $_GET['order_id'] ) ? absint( $_GET['order_id'] ) : 0;

$orders = $order_id
    ? $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, status, total_amount, currency, date_created_gmt FROM {$order_table} WHERE id = %d ORDER BY id DESC",
            $order_id
        )
    )
    : $wpdb->get_results(
        "SELECT id, status, total_amount, currency, date_created_gmt FROM {$order_table} ORDER BY id DESC"
    );

$orders_with_items = array();

foreach ( $orders as $order ) {
    $db_items = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT order_item_id, order_item_name, order_item_type FROM {$items_table} WHERE order_id = %d ORDER BY order_item_id ASC",
            $order->id
        )
    );

    $items = array();
    foreach ( $db_items as $db_item ) {
        $meta = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT meta_key, meta_value FROM {$itemmeta_table} WHERE order_item_id = %d",
                $db_item->order_item_id
            )
        );

        $meta_map = array();
        foreach ( $meta as $row ) {
            $meta_map[ $row->meta_key ] = $row->meta_value;
        }

        $items[] = array(
            'name'     => $db_item->order_item_name,
            'type'     => $db_item->order_item_type,
            'qty'      => isset( $meta_map['_qty'] ) ? (int) $meta_map['_qty'] : 1,
            'total'    => isset( $meta_map['_line_total'] ) ? (float) $meta_map['_line_total'] : 0,
            'subtotal' => isset( $meta_map['_line_subtotal'] ) ? (float) $meta_map['_line_subtotal'] : 0,
            'raw'      => isset( $meta_map['_mytheme_raw_data'] ) ? json_decode( $meta_map['_mytheme_raw_data'], true ) : array(),
        );
    }

    $orders_with_items[] = array(
        'order' => $order,
        'items' => $items,
    );
}
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
                <div id="mytheme-order-history">
                    <?php if ( ! empty( $orders_with_items ) ) : ?>
                        <?php foreach ( $orders_with_items as $bundle ) : ?>
                            <?php $order = $bundle['order']; ?>
                            <div class="product_sale_cons_ordered">
                                <span class="product_sale_cons_status">
                                    <p style="opacity: 0.5;">Order #<?php echo esc_html( $order->id ); ?></p>
                                    <p>Placed on <?php echo esc_html( mysql2date( 'F j, Y', $order->date_created_gmt ) ); ?></p>
                                    <?php
                                    $raw_status = strtolower( (string) $order->status );
                                    $normalized_status = preg_replace( '/^wc-/', '', $raw_status );

                                    $status_colors = array(
                                        'pending'    => '#b45309',
                                        'processing' => '#2563eb',
                                        'on-hold'    => '#7c3aed',
                                        'completed'  => '#166534',
                                        'failed'     => '#dc2626',
                                        'cancelled'  => '#991b1b',
                                        'refunded'   => '#0f766e',
                                        'trash'      => '#6b7280',
                                    );

                                    $status_label = $raw_status ? str_replace( 'wc-', '', $raw_status ) : 'unknown';
                                    $status_color = $status_colors[ $normalized_status ] ?? '#6b7280';
                                    $status_backgrounds = array(
                                        'pending'    => '#fef3c7',
                                        'processing' => '#dbeafe',
                                        'on-hold'    => '#ede9fe',
                                        'completed'  => '#dcfce7',
                                        'failed'     => '#fee2e2',
                                        'cancelled'  => '#ffe4e6',
                                        'refunded'   => '#ccfbf1',
                                        'trash'      => '#f3f4f6',
                                    );
                                    $status_background = $status_backgrounds[ $normalized_status ] ?? '#f3f4f6';
                                    ?>
                                    <span class="product_sale_cons_status_icons" style="background: <?php echo esc_attr( $status_background ); ?>; border-radius: 999px; padding: 4px 10px; display: inline-flex; align-items: center;">
                                        <p style="font-size: 12px; color: <?php echo esc_attr( $status_color ); ?>;">
                                            <?php echo esc_html( ucfirst( $status_label ) ); ?>
                                        </p>
                                    </span>
                                    <h3><?php echo esc_html( $order->currency ); ?><?php echo esc_html( number_format( (float) $order->total_amount, 2 ) ); ?></h3>
                                </span>
                                <?php foreach ( $bundle['items'] as $item ) : ?>
                                    <?php $raw = is_array( $item['raw'] ) ? $item['raw'] : array(); ?>
                                    <div class="product_sale_cons_relative">
                                        <figure class="product_sale_cons_figure">
                                            <?php if ( ! empty( $raw['image'] ) ) : ?>
                                                <img src="<?php echo esc_url( $raw['image'] ); ?>" alt="<?php echo esc_attr( $raw['title'] ?? $item['name'] ); ?>">
                                            <?php endif; ?>
                                            <figcaption>
                                                <span>
                                                    <h4><?php echo esc_html( $raw['title'] ?? $item['name'] ); ?></h4>
                                                    <?php if ( ! empty( $raw['brand'] ) ) : ?>
                                                        <p style="opacity: 0.5;"><?php echo esc_html( $raw['brand'] ); ?></p>
                                                    <?php endif; ?>
                                                    <?php if ( ! empty( $raw['size'] ) ) : ?>
                                                        <p style="opacity: 0.5;">Size: <?php echo esc_html( $raw['size'] ); ?></p>
                                                    <?php endif; ?>
                                                    <p style="opacity: 0.5;">Qty: <?php echo esc_html( $item['qty'] ); ?></p>
                                                </span>
                                                <p style="opacity: 0.5;">Item total: <?php echo esc_html( $order->currency ); ?><?php echo esc_html( number_format( (float) $item['total'], 2 ) ); ?></p>
                                            </figcaption>
                                        </figure>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="empty-cart"><h2>No order found</h2><p>There are no stored orders in the database yet.</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>
<?php get_footer(); ?>
