<?php get_header(); ?>
	<main>
        <?php get_template_part( 'nav' ); ?>
        <section class="wishlist-container">
            <div class="wishlist-header">
                <h1 class="wishlist-title">Wishlist</h1>
                <p class="wishlist-count">1 items saved</p>
            </div>
            <div class="wishlist-grid">
                <div class="wishlist-card">
                    <div class="card-image">
                        <img src="../assets/image/wishlist.png" alt="Nike Air Force 1 Shadow">
                        <span class="card-badge">New</span>
                        <button class="card-remove" aria-label="Remove">&#9829;</button>
                    </div>
                    <div class="card-info">
                        <h3 class="card-name">Nike Air Force 1 Shadow</h3>
                        <p class="card-brand">Nike &bull; Women</p>
                        <span class="card-price">$130</span>
                    </div>
                </div>
            </div>
        </section>
    </main>
<?php get_footer(); ?>