<?php
/*
Template Name: wishlist
*/
require_once get_theme_file_path( 'functions_wishlist.php' );
get_header();
?>
	<main>
        <?php get_template_part( 'nav' ); ?>
        <section class="wishlist-container">
            <div class="wishlist-header">
                <h1 class="wishlist-title">Wishlist</h1>
                <p class="wishlist-count" data-wishlist-count>0 items saved</p>
            </div>
            <div class="wishlist-grid" data-wishlist-grid hidden></div>
            <section class="empty-wishlist" data-wishlist-empty>
                <div class="empty-content">
                    <h1 class="empty-title">Your Wishlist is Empty</h1>
                    <p class="empty-subtitle">Save your favorite pairs here and never lose track of the sneakers you love.</p>
                    <a class="empty-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>">Start Shopping</a>
                </div>
            </section>
        </section>
    </main>
<?php get_footer(); ?>
