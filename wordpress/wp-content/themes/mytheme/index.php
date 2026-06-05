<?php get_header(); ?>
<?php
if ( ! function_exists( 'mytheme_render_home_products_section' ) ) {
    function mytheme_render_home_products_section( $query, $section_class = '' ) {
        $badge_map = array(
            'best-sellers-grid' => array(
                'label' => 'Best Seller',
                'color' => '#DC143C',
            ),
            'trending-grid'     => array(
                'label' => 'Trending Now',
                'color' => '#2563EB',
            ),
            'sales-50-grid'     => array(
                'label' => 'Sales 50%',
                'color' => '#16A34A',
            ),
        );
        $badge = isset( $badge_map[ $section_class ] ) ? $badge_map[ $section_class ] : null;

        if ( ! $query->have_posts() ) {
            echo '<div class="shops_empty_state"><h3>No products available</h3><p>There are no products in this category yet.</p></div>';
            return;
        }

        echo '<div class="shops_content_product ' . esc_attr( $section_class ) . '">';

        while ( $query->have_posts() ) {
            $query->the_post();
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
            $details_url = add_query_arg( 'product_id', get_the_ID(), home_url( '/product-details/' ) );

            echo '<div class="shop_card" onclick="window.location.href=\'' . esc_url_raw( $details_url ) . '\'">';
            if ( $badge ) {
                echo '<span class="fx_display" style="background-color: ' . esc_attr( $badge['color'] ) . ';"><p style="opacity: 1;">' . esc_html( $badge['label'] ) . '</p></span>';
            }
            echo '<img src="' . esc_url( $image ) . '" alt="pr_1">';
            echo '<p>' . esc_html( strtoupper( wp_strip_all_tags( $product->get_attribute( 'pa_brand' ) ? $product->get_attribute( 'pa_brand' ) : 'NIKE' ) ) ) . '</p>';
            echo '<h4>' . esc_html( $product->get_name() ) . '</h4>';
            echo '<p style="text-align: center;">(1,234)</p>';
            echo '<h2>' . wp_kses_post( $price ) . '</h2>';
            echo '<p style="margin-top: 8px; font-size: 12px; color: ' . esc_attr( $is_in_stock ? '#16A34A' : '#DC2626' ) . ';">' . esc_html( $is_in_stock ? 'In Stock' : 'Out of Stock' ) . '</p>';
            echo '</div>';
        }

        wp_reset_postdata();
        echo '</div>';
    }
}
?>
  <main class="container">
        <?php get_template_part( 'nav' ); ?>
        <section class="banner">
            <div class="banner_wrapper">
                <div class="banner_left">
                    <span class="banner_title">
                        <div class="banner_title_anim_fx">
                            <div>
                                <h1 style="color: #fff; margin-left: 15px;" >ICONIC BRAND</h1>
                                <h1>TIMELESS STYLE</h1> 
                            </div>
                            <div>
                                <h1 style="color: #fff; margin-left: 15px;" >BIG DEALS</h1>
                                <h1>BIGGER STEALS</h1> 
                            </div>
                            <div>
                                <h1 style="color: #fff; margin-left: 15px; font-size: 90px;" >New Balance</h1>
                            </div>
                        </div>
                    </span>
                    <span class="banner_para1">
                        <div class="banner_para1_fx">
                            <div>
                                <p>A 2000s running-inspired sneaker combining a sleek ’70s silhouette <br> with modern leather and mesh details for a fresh, everyday style. </p>
                            </div>
                            <div>
                                <p>Save up  to 40%  on select styles limited time only </p>
                            </div>
                            <div>
                                <p>A 2000s running-inspired sneaker combining a sleek ’70s silhouette <br> with modern leather and mesh details for a fresh, everyday style. </p>
                            </div>
                        </div>
                    </span>
                    <button class="banner_button">Explore Brand</button>
                    <div class="banner_icons">
                        <span><img src="<?php echo esc_url( get_theme_file_uri( 'assets/marketeq_car-shipping.png' ) ); ?>" alt="" title=""><p>Free Shipping</p></span>
                        <span><img src="<?php echo esc_url( get_theme_file_uri( 'assets/game-icons_return-arrow.png' ) ); ?>" alt="" title=""><p>Easy Return</p></span>
                        <span><img src="<?php echo esc_url( get_theme_file_uri( 'assets/hugeicons_payment-01.png' ) ); ?>" alt="" title=""><p>Secure Payment</p></span>
                    </div>
                    <div class="banner_shop_by_brand">
                        <h2>Shop by Brand</h2>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/NB.png' ) ); ?>" alt="">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/Nike.png' ) ); ?>" alt="">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/addidas.png' ) ); ?>" alt="">
                    </div>
                </div>
                <div class="banner_right">
                    <img class="img1_fx" src="<?php echo esc_url( get_theme_file_uri( 'assets/image/shoes1.png' ) ); ?>" alt="fx_shoes">
                    <img class="img2_fx" src="<?php echo esc_url( get_theme_file_uri( 'assets/image/shoes2.png' ) ); ?>" alt="fx_shoes">
                    <img class="img3_fx" src="<?php echo esc_url( get_theme_file_uri( 'assets/image/shoes3.png' ) ); ?>" alt="fx_shoes">
                </div>
            </div>
        </section>
        <section class="shop_brand">
            <div class="shop_brand_icons">
                <div class="shop_brand_wrapper_1">
                    <span class="shop_brand_icons_contain">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/div1.png' ) ); ?>" alt="div">
                        <span>
                            <h5>100% Authentic</h5><p>Verified genuine products</p>
                        </span>
                    </span>
                    <span class="shop_brand_icons_contain">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/div2.png' ) ); ?>" alt="div">
                        <span>
                            <h5>Easy Returns</h5><p>30-day return policy</p>
                        </span>
                    </span>
                    <span class="shop_brand_icons_contain">
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/div3.png' ) ); ?>" alt="div">
                        <span>
                            <h5>Fast Shipping</h5><p>Free delivery over $100</p>
                        </span>
                    </span>
                </div>
            </div>
            <div class="shop_brand_contain">
                    <span class="shop_brand_title">
                        <h2>Shop by Brand</h2>
                        <p>Explore iconic sneakers from the world's leading brands</p>
                    </span>
                    <div class="shop_brand_icons_2">
                        <span>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/div_nike.png' ) ); ?>" alt="nike">
                    </span>
                    <span>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/div_addidas.png' ) ); ?>" alt="nike">
                    </span>
                    <span>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/div_NewBalance.png' ) ); ?>" alt="nike">
                    </span>
                </div>
            </div>
        </section>
        <article class="shops">
            <div class="shops_wrapper">
                <div class="shops_content">
                    <span class="shops_content_title">
                        <h2>Best Sellers</h2>
                        <p>Most popular kicks right now</p>
                    </span>
                    <span class="shops_content_view_all">
                        <p>View All</p>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/arrow.png' ) ); ?>" alt="arrow">
                    </span>

                    <?php
                    $best_sellers = new WP_Query(
                        array(
                            'post_type'      => 'product',
                            'posts_per_page' => 4,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'slug',
                                    'terms'    => array( 'best-sellers' ),
                                ),
                            ),
                        )
                    );
                    mytheme_render_home_products_section( $best_sellers, 'best-sellers-grid' );
                    ?>
                </div>
                <div class="shops_content" style="background-color: var(--blend);">
                    <span class="shops_content_title">
                        <h2>Trending Now</h2>
                        <p>What everyone's talking about</p>
                    </span>
                    <span class="shops_content_view_all">
                        <p>View All</p>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/arrow.png' ) ); ?>" alt="arrow">
                    </span>
                    <?php
                    $trending = new WP_Query(
                        array(
                            'post_type'      => 'product',
                            'posts_per_page' => 4,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'slug',
                                    'terms'    => array( 'trending' ),
                                ),
                            ),
                        )
                    );
                    mytheme_render_home_products_section( $trending, 'trending-grid' );
                    ?>
                </div>
                <div class="shops_content">
                    <span class="shops_content_title" style="color: #16A34A;">
                        <h2>Sales 50% off</h2>
                        <p>Limited time deals on premium sneakers</p>
                    </span>
                    <span class="shops_content_view_all">
                        <p>View All</p>
                        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/arrow.png' ) ); ?>" alt="arrow">
                    </span>
                    <?php
                    $sales_50 = new WP_Query(
                        array(
                            'post_type'      => 'product',
                            'posts_per_page' => 4,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'slug',
                                    'terms'    => array( 'sales-50' ),
                                ),
                            ),
                        )
                    );
                    mytheme_render_home_products_section( $sales_50, 'sales-50-grid' );
                    ?>
                </div>
            </div>
        </article>
    </main>
  <?php get_footer(); ?>
