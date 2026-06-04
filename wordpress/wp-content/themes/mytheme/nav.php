<?php ?>
<header>
    <div class="expnadable_div">
        <div>
            <h2>Shop Category</h2>
            <nav>
                <ul>
                    <li>Sneakers (Lifestyle)</li>
                    <li>Running</li>
                    <li>Basketball</li>
                    <li>Training/Gym</li>
                    <li>Sandals/Slides</li>
                </ul>
            </nav>
        </div>
        <div>
            <h2>Shop By Style</h2>
            <nav>
                <ul>
                    <li>Low top</li>
                    <li>Mid-type</li>
                    <li>High-type</li>
                    <li>Chunky</li>
                    <li>Minimalist</li>
                </ul>
            </nav>
        </div>
        <div>
            <h2>Shop By Brand</h2>
            <nav>
                <ul>
                    <li>New Balance</li>
                    <li>Nike</li>
                    <li>Addidas</li>
                </ul>
            </nav>
        </div>
        <div>
            <h2>Feature</h2>
            <nav>
                <ul>
                    <li>Best Sellers</li>
                    <li>New Arrivals</li>
                    <li>Trending Now</li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="header_wrapper">
        
        <figure>
            <a href="<?php echo esc_url( home_url( '/index.php/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/logo.png' ) ); ?>" alt="logo" title="Logo"></a>
        </figure>
        <nav>
            <ul>
                <li><a href="<?php echo esc_url( home_url( '/index.php/men/' ) ); ?>">Men</a></li>
                <li><a href="<?php echo esc_url( home_url( '/index.php/women/' ) ); ?>">Women</a></li>
                <li><a href="<?php echo esc_url( home_url( '/index.php/brands/' ) ); ?>">Brands</a></li>
                <li><a href="<?php echo esc_url( home_url( '/index.php/new-drops/' ) ); ?>">NewDrops</a></li>
                <li><a href="<?php echo esc_url( home_url( '/index.php/sale/' ) ); ?>">Sale</a></li>
            </ul>
        </nav>
        <div class="search">
            <input type="text">
            <span><img src="<?php echo esc_url( get_theme_file_uri( 'assets/search.png' ) ); ?>" alt="search" title="search"></span>
        </div>
        <div class="icons">
            <span><a href="<?php echo esc_url( home_url( '/index.php/my-order/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/solar_user-broken.png' ) ); ?>" alt="user" title="user"></a></span>
            <span><a href="<?php echo esc_url( home_url( '/index.php/wishlist/' ) ); ?>"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/heart.png' ) ); ?>" alt="heart" title="heart"></a></span>
            <span class="cart-link-wrap">
                <a href="<?php echo esc_url( home_url( '/cart/' ) ); ?>">
                    <img src="<?php echo esc_url( get_theme_file_uri( 'assets/cart.png' ) ); ?>" alt="cart" title="cart">
                    <span class="cart-badge" id="mytheme-cart-badge">0</span>
                </a>
            </span>
        </div>
    </div>
</header>
<script>
(function() {
    var badge = document.getElementById('mytheme-cart-badge');
    if (!badge) return;

    function updateBadge() {
        var cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('mytheme_cart') || '[]');
        } catch (e) {
            cart = [];
        }

        var count = cart.reduce(function(total, item) {
            return total + (parseInt(item.quantity, 10) || 1);
        }, 0);

        badge.textContent = String(count);
        badge.style.display = count > 0 ? 'inline-flex' : 'none';
    }

    updateBadge();
    window.addEventListener('storage', updateBadge);
    window.addEventListener('mytheme-cart-updated', updateBadge);
})();
</script>
