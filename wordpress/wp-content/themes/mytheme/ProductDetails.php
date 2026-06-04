<?php
/*
Template Name: Product Details
*/
require_once get_theme_file_path( 'functions_product_details.php' );
get_header();

$product_id = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0;
$product    = $product_id ? wc_get_product( $product_id ) : false;

if ( ! $product ) {
    global $post;
    $product = wc_get_product( get_the_ID() );
}

if ( ! $product ) {
    $product = false;
}

$product_title = $product ? $product->get_name() : 'Product Details';
$product_brand = $product ? wc_get_product_category_list( $product->get_id(), ', ' ) : 'Jordan';
$product_price  = $product ? $product->get_price_html() : '$210';
$currency_symbol = '₱';
$product_price_amount = $product ? (float) $product->get_price() : 210;
$product_description = $product ? $product->get_description() : '';
$product_short_description = $product ? $product->get_short_description() : '';
$product_image  = $product ? get_the_post_thumbnail_url( $product->get_id(), 'full' ) : '';
$product_image  = $product_image ? $product_image : get_theme_file_uri( 'assets/image/pd-img1.png' );
$product_gallery_ids = $product ? $product->get_gallery_image_ids() : array();
$product_gallery_images = array();

if ( $product ) {
    $product_gallery_images[] = $product_image;

    foreach ( $product_gallery_ids as $gallery_id ) {
        $gallery_image = wp_get_attachment_image_url( $gallery_id, 'full' );
        if ( $gallery_image ) {
            $product_gallery_images[] = $gallery_image;
        }
    }

    $product_gallery_images = array_slice( array_unique( $product_gallery_images ), 0, 3 );
    while ( count( $product_gallery_images ) < 3 ) {
        $product_gallery_images[] = $product_image;
    }
} else {
    $product_gallery_images = array( $product_image, $product_image, $product_image );
}

$size_options = array();
if ( $product ) {
    $attributes = $product->get_attributes();
    foreach ( array( 'pa_size', 'size' ) as $attribute_key ) {
        if ( isset( $attributes[ $attribute_key ] ) ) {
            $attribute = $attributes[ $attribute_key ];
            if ( $attribute->is_taxonomy() ) {
                $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
                if ( ! empty( $terms ) ) {
                    $size_options = $terms;
                    break;
                }
            } else {
                $size_options = $attribute->get_options();
                if ( ! empty( $size_options ) ) {
                    break;
                }
            }
        }
    }
}

if ( empty( $size_options ) ) {
    $size_options = array( '7', '7.5', '8', '8.5', '9', '9.5', '10', '10.5', '11', '11.5', '12', '13' );
}
?>
	<main>
       <?php get_template_part( 'nav' ); ?>
        <section class="product-details-container">
            <div class="breadcrumb">
                <span>Home</span>
                <span class="sep">/</span>
                <span>Shop</span>
                <span class="sep">/</span>
                <span><?php echo esc_html( wp_strip_all_tags( $product_brand ) ); ?></span>
                <span class="sep">/</span>
                <span class="active"><?php echo esc_html( $product_title ); ?></span>
            </div>
            <div class="product-grid">
                <div class="product-images">
                    <div class="thumbs-strip">
                        <?php foreach ( $product_gallery_images as $gallery_image ) : ?>
                            <img src="<?php echo esc_url( $gallery_image ); ?>" alt="thumb">
                        <?php endforeach; ?>
                    </div>
                    <div class="main-image">
                        <img src="<?php echo esc_url( $product_image ); ?>" alt="Product Image">
                    </div>
                </div>
                <form class="product-info" method="get" action="<?php echo esc_url( home_url( '/cart/' ) ); ?>" data-cart-form>
                    <input type="hidden" name="product_id" value="<?php echo esc_attr( $product ? $product->get_id() : 0 ); ?>">
                    <p class="product-brand"><?php echo esc_html( wp_strip_all_tags( $product_brand ) ); ?></p>
                    <div class="title-row">
                        <h1 class="product-title"><?php echo esc_html( $product_title ); ?></h1>
                        <button class="share-btn" aria-label="Share">&#9825;</button>
                    </div>
                    <div class="price-row">
                        <span class="price"><?php echo wp_kses_post( $product_price ); ?></span>
                    </div>

                    <div class="size-selector">
                        <div class="size-header">
                            <label>Select Size (US)</label>
                            <span class="size-guide">Size Guide</span>
                        </div>
                        <div class="sizes-grid">
                            <?php foreach ( $size_options as $index => $size_option ) : ?>
                                <label class="size-btn">
                                    <input type="radio" name="size" value="<?php echo esc_attr( $size_option ); ?>" <?php checked( 0 === $index ); ?>>
                                    <span><?php echo esc_html( $size_option ); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="cart-row">
                        <div class="qty-control">
                            <button class="qty-btn minus" type="button" aria-label="Decrease">−</button>
                            <input class="qty-val" type="number" name="quantity" value="1" min="1" step="1" aria-label="Quantity">
                            <button class="qty-btn plus" type="button" aria-label="Increase">+</button>
                        </div>
                        <button class="add-to-cart" type="submit">Add to Cart</button>
                    </div>

                    <div class="authenticity-badge">
                        <span class="badge-icon">&#10003;</span>
                        <div class="badge-text">
                            <strong>100% Authentic Guarantee</strong>
                            <span>Every item is verified by our</span>
                        </div>
                    </div>

                    <div class="product-tabs">
                        <div class="tabs-header">
                            <button class="tab active">description</button>
                            <button class="tab">shipping</button>
                        </div>
                        <div class="tab-content">
                            <?php if ( ! empty( $product_short_description ) ) : ?>
                                <?php echo wp_kses_post( wpautop( $product_short_description ) ); ?>
                            <?php elseif ( ! empty( $product_description ) ) : ?>
                                <?php echo wp_kses_post( wpautop( $product_description ) ); ?>
                            <?php else : ?>
                                <p>No description available for this product yet.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
                </form>
            </div>
        </section>
    </main>
    <script>
    document.querySelectorAll('.product-info').forEach(function(form) {
        var qtyInput = form.querySelector('.qty-val');
        var minusBtn = form.querySelector('.qty-btn.minus');
        var plusBtn = form.querySelector('.qty-btn.plus');

        if (!qtyInput || !minusBtn || !plusBtn) return;

        minusBtn.addEventListener('click', function() {
            var current = parseInt(qtyInput.value, 10) || 1;
            qtyInput.value = Math.max(1, current - 1);
        });

        plusBtn.addEventListener('click', function() {
            var current = parseInt(qtyInput.value, 10) || 1;
            qtyInput.value = current + 1;
        });
    });

    (function() {
        var form = document.querySelector('[data-cart-form]');
        if (!form) return;

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var productId = form.querySelector('input[name="product_id"]').value;
            var title = <?php echo wp_json_encode( $product_title ); ?>;
            var brand = <?php echo wp_json_encode( wp_strip_all_tags( $product_brand ) ); ?>;
            var image = <?php echo wp_json_encode( $product_image ); ?>;
            var priceText = <?php echo wp_json_encode( wp_strip_all_tags( $product_price ) ); ?>;
            var priceAmount = <?php echo wp_json_encode( (float) $product_price_amount ); ?>;
            var currencySymbol = <?php echo wp_json_encode( $currency_symbol ); ?>;
            var sizeInput = form.querySelector('input[name="size"]:checked');
            var size = sizeInput ? sizeInput.value : '';
            var quantity = parseInt(form.querySelector('input[name="quantity"]').value, 10) || 1;

            var cartItem = {
                product_id: productId,
                title: title,
                brand: brand,
                image: image,
                price_text: priceText,
                price_amount: priceAmount,
                currency_symbol: currencySymbol,
                size: size,
                quantity: quantity
            };

            var cart = [];
            try {
                cart = JSON.parse(localStorage.getItem('mytheme_cart') || '[]');
            } catch (e) {
                cart = [];
            }

            var existingIndex = cart.findIndex(function(item) {
                return String(item.product_id) === String(productId) && String(item.size) === String(size);
            });

            if (existingIndex > -1) {
                cart[existingIndex].quantity = (parseInt(cart[existingIndex].quantity, 10) || 1) + quantity;
            } else {
                cart.push(cartItem);
            }

            localStorage.setItem('mytheme_cart', JSON.stringify(cart));
            window.dispatchEvent(new CustomEvent('mytheme-cart-updated'));
            form.querySelector('.add-to-cart').textContent = 'Added';
            window.setTimeout(function() {
                form.querySelector('.add-to-cart').textContent = 'Add to Cart';
            }, 1200);
        });
    })();
    </script>
<?php get_footer(); ?>
