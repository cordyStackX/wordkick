<?php
function mytheme_enqueue_wishlist_assets() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-header', get_theme_file_uri( 'styles/landpage/header.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-page-wishlist', get_theme_file_uri( '/styles/wishlist/wishlist.css' ), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-page-wishlist-empty', get_theme_file_uri( '/styles/wishlist/wishlist-empty.css' ), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-footer', get_theme_file_uri( 'styles/landpage/footer.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-global', get_theme_file_uri( 'styles/global.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_script( 'mytheme-wishlist', get_theme_file_uri( 'js/wishlist.js' ), array(), wp_get_theme()->get( 'Version' ), true );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_wishlist_assets' );
