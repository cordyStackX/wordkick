<?php
function mytheme_get_cart_payload_from_request() {
    $raw = isset( $_POST['payload'] ) ? wp_unslash( $_POST['payload'] ) : '';
    if ( '' === $raw ) {
        return array();
    }

    $data = json_decode( $raw, true );
    return is_array( $data ) ? $data : array();
}

function mytheme_store_cart_payload_in_session( $payload ) {
    if ( function_exists( 'WC' ) && WC() && WC()->session ) {
        WC()->session->set( 'mytheme_cart_payload', $payload );
    }
}

function mytheme_ajax_stage_cart_payload() {
    $payload = mytheme_get_cart_payload_from_request();
    mytheme_store_cart_payload_in_session( $payload );

    wp_send_json_success(
        array(
            'stored'  => true,
            'count'   => count( $payload ),
        )
    );
}
add_action( 'wp_ajax_mytheme_stage_cart_payload', 'mytheme_ajax_stage_cart_payload' );
add_action( 'wp_ajax_nopriv_mytheme_stage_cart_payload', 'mytheme_ajax_stage_cart_payload' );

function mytheme_create_order_from_payload( $payload ) {
    if ( ! function_exists( 'wc_create_order' ) || empty( $payload ) || ! is_array( $payload ) ) {
        return new WP_Error( 'invalid_payload', 'Invalid cart payload.' );
    }

    $order = wc_create_order();
    if ( is_wp_error( $order ) ) {
        return $order;
    }

    $currency = get_woocommerce_currency();
    $subtotal = 0;

    foreach ( $payload as $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }

        $quantity = isset( $item['quantity'] ) ? max( 1, absint( $item['quantity'] ) ) : 1;
        $price    = isset( $item['price_amount'] ) ? (float) $item['price_amount'] : 0;
        $total    = $price * $quantity;
        $subtotal += $total;

        $order_item_id = $order->add_item(
            array(
                'order_item_name' => sanitize_text_field( $item['title'] ?? '' ),
                'order_item_type' => 'line_item',
                'order_id'        => $order->get_id(),
            )
        );

        if ( ! $order_item_id ) {
            continue;
        }

        wc_add_order_item_meta( $order_item_id, '_qty', $quantity, true );
        wc_add_order_item_meta( $order_item_id, '_line_total', $total, true );
        wc_add_order_item_meta( $order_item_id, '_line_subtotal', $total, true );
        wc_add_order_item_meta( $order_item_id, '_mytheme_raw_data', wp_json_encode( $item ), true );
    }

    $order->set_currency( $currency );
    $order->set_status( 'pending' );
    $order->set_total( $subtotal );
    $order->set_created_via( 'mytheme-localstorage' );
    $order->save();

    $order_id = $order->get_id();
    if ( ! $order_id ) {
        return new WP_Error( 'order_create_failed', 'Could not create order.' );
    }

    global $wpdb;
    $order_items_table    = $wpdb->prefix . 'woocommerce_order_items';
    $order_itemmeta_table = $wpdb->prefix . 'woocommerce_order_itemmeta';

    $billing_email = sanitize_email( $payload[0]['billing_email'] ?? '' );
    if ( $billing_email ) {
        $order->set_billing_email( $billing_email );
        $order->save();
    }

    foreach ( $payload as $item ) {
        if ( ! is_array( $item ) ) {
            continue;
        }

        $quantity = isset( $item['quantity'] ) ? max( 1, absint( $item['quantity'] ) ) : 1;
        $price    = isset( $item['price_amount'] ) ? (float) $item['price_amount'] : 0;
        $total    = $price * $quantity;
        $name     = sanitize_text_field( $item['title'] ?? '' );

        $wpdb->insert(
            $order_items_table,
            array(
                'order_item_name' => $name,
                'order_item_type' => 'line_item',
                'order_id'        => absint( $order_id ),
            ),
            array( '%s', '%s', '%d' )
        );

        $order_item_id = (int) $wpdb->insert_id;
        if ( ! $order_item_id ) {
            continue;
        }

        $meta_map = array(
            '_qty'             => $quantity,
            '_line_total'      => $total,
            '_line_subtotal'   => $total,
            '_mytheme_raw_data' => wp_json_encode( $item ),
        );

        foreach ( $meta_map as $meta_key => $meta_value ) {
            $wpdb->insert(
                $order_itemmeta_table,
                array(
                    'order_item_id' => $order_item_id,
                    'meta_key'      => $meta_key,
                    'meta_value'    => is_scalar( $meta_value ) ? (string) $meta_value : wp_json_encode( $meta_value ),
                ),
                array( '%d', '%s', '%s' )
            );
        }
    }

    if ( method_exists( $order, 'update_meta_data' ) ) {
        $order->update_meta_data( '_mytheme_cart_payload', wp_json_encode( $payload ) );
        $order->save();
    } else {
        update_post_meta( $order_id, '_mytheme_cart_payload', wp_json_encode( $payload ) );
    }

    return $order;
}

function mytheme_ajax_create_order_from_payload() {
    try {
        $payload = mytheme_get_cart_payload_from_request();
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
            error_log( 'mytheme checkout payload: ' . wp_json_encode( $payload ) );
        }
        $order   = mytheme_create_order_from_payload( $payload );

        if ( is_wp_error( $order ) ) {
            throw new Exception( $order->get_error_message() );
        }

        if ( function_exists( 'WC' ) && WC() && WC()->session ) {
            WC()->session->__unset( 'mytheme_cart_payload' );
        }

        wp_send_json_success(
            array(
                'order_id' => $order->get_id(),
                'order_key' => $order->get_order_key(),
                'redirect'  => home_url( '/my-order/?order_id=' . $order->get_id() ),
            )
        );
    } catch ( Throwable $e ) {
        error_log( 'mytheme checkout failed: ' . $e->getMessage() );
        wp_send_json_error(
            array(
                'message' => $e->getMessage(),
                'trace'   => WP_DEBUG ? $e->getTraceAsString() : '',
            )
        );
    }
}
add_action( 'wp_ajax_mytheme_create_order_from_payload', 'mytheme_ajax_create_order_from_payload' );
add_action( 'wp_ajax_nopriv_mytheme_create_order_from_payload', 'mytheme_ajax_create_order_from_payload' );

function mytheme_enqueue_page_assets() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-header', get_theme_file_uri( 'styles/landpage/header.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-page-cart', get_theme_file_uri( 'styles/acc-profile/cart.css' ), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-page-checkout', get_theme_file_uri( 'styles/acc-profile/checkout.css' ), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-footer', get_theme_file_uri( 'styles/landpage/footer.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-global', get_theme_file_uri( 'styles/global.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_page_assets' );
