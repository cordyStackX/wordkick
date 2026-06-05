<?php
/*
Template Name: Sale Page
*/
require_once get_theme_file_path( 'functions2.php' );
get_header();
?>
	<main>
        <?php get_template_part( 'nav' ); ?>
        <section class="banner">
            <img src="<?php echo esc_url( get_theme_file_uri( 'assets/Background_sales.png' ) ); ?>" alt="">
        </section>
        <section class="content">
            <aside class="content_aside">
                <p style="opacity: 0.8;">Showing 3 result</p>
                <div>
                    <p>Brand</p>
                    <span><input type="checkbox" name="" id=""><p style="opacity: 0.8;">Nike</p></span>
                    <span><input type="checkbox" name="" id=""><p style="opacity: 0.8;">Addidas</p></span>
                    <span><input type="checkbox" name="" id=""><p style="opacity: 0.8;">New Balance</p></span>
                </div>
                <div>
                    <p>Gender</p>
                    <span><input type="checkbox" name="" id=""><p style="opacity: 0.8;">Men</p></span>
                    <span><input type="checkbox" name="" id=""><p style="opacity: 0.8;">Women</p></span>
                    <span><input type="checkbox" name="" id=""><p style="opacity: 0.8;">Unisex</p></span>
                </div>
            </aside>
            <div class="product_sale_cons">
                <?php
                $sale_products = new WP_Query(
                    array(
                        'post_type'      => 'product',
                        'posts_per_page' => 12,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'tax_query'      => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'slug',
                                'terms'    => array( 'sale' ),
                            ),
                        ),
                    )
                );

                if ( $sale_products->have_posts() ) :
                    while ( $sale_products->have_posts() ) :
                        $sale_products->the_post();
                        $product = wc_get_product( get_the_ID() );
                        if ( ! $product ) {
                            continue;
                        }

                        $is_sale = $product->is_on_sale();
                        $is_new  = get_the_date( 'U' ) >= strtotime( '-30 days' );
                        $is_in_stock = $product->is_in_stock();
                        $price   = $product->get_price_html();
                        $image   = get_the_post_thumbnail_url( get_the_ID(), 'woocommerce_thumbnail' );
                        $image   = $image ? $image : wc_placeholder_img_src();
                        $term_slugs = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'slugs' ) );
                        $brand_terms = array_values( array_intersect( $term_slugs, array( 'nike', 'adidas', 'addidas', 'new-balance' ) ) );
                        $gender_terms = array_values( array_intersect( $term_slugs, array( 'men', 'women', 'unisex' ) ) );
                        $details_url = add_query_arg( 'product_id', get_the_ID(), home_url( '/product-details/' ) );
                        ?>
                        <div class="product_sale_cons_relative" data-brand="<?php echo esc_attr( implode( ',', $brand_terms ) ); ?>" data-gender="<?php echo esc_attr( implode( ',', $gender_terms ) ); ?>" data-categories="<?php echo esc_attr( implode( ',', $term_slugs ) ); ?>">
                            <?php if ( $is_sale ) : ?>
                                <span class="product_sale_cons_status" style="background-color: #f00; color: #fff;">
                                    <p>Sales</p>
                                </span>
                            <?php elseif ( $is_new ) : ?>
                                <span class="product_sale_cons_status" style="border: 1px solid #000;">
                                    <p>New</p>
                                </span>
                            <?php endif; ?>
                            <span class="wishlist" role="button" tabindex="0" aria-label="Add to wishlist" data-wishlist-item='<?php echo esc_attr( wp_json_encode( array(
                                'id'       => get_the_ID(),
                                'name'     => get_the_title(),
                                'brand'    => 'Sales',
                                'price'    => wp_strip_all_tags( $price ),
                                'image'    => $image,
                                'url'      => $details_url,
                                'category' => 'Sales',
                            ) ) ); ?>'>
                                <img src="<?php echo esc_url( get_theme_file_uri( 'assets/heart_wishlist.png' ) ); ?>" alt="wishlist" title="wishlist">
                            </span>
                            <a href="<?php echo esc_url( $details_url ); ?>" class="product_sale_cons_figure">
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
                                <figcaption>
                                    <span>
                                        <h4><?php the_title(); ?></h4>
                                        <h4><?php echo wp_kses_post( $price ); ?></h4>
                                    </span>
                                    <p>Sales</p>
                                    <?php if ( $is_sale ) : ?>
                                        <p style="color: #f00; opacity: 0.9; font-size: 0.8rem;">Only a few left</p>
                                    <?php endif; ?>
                                    <p style="margin-top: 6px; font-size: 0.8rem; color: <?php echo esc_attr( $is_in_stock ? '#16A34A' : '#DC2626' ); ?>;"><?php echo esc_html( $is_in_stock ? 'In Stock' : 'Out of Stock' ); ?></p>
                                </figcaption>
                            </a>
                        </div>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<div class="empty-cart"><h2>No products available</h2><p>There are no products in this category yet.</p></div>';
                endif;
                ?>
            </div>
        </section>
    </main>
<?php get_footer(); ?>
