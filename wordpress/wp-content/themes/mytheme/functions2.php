<?php
function mytheme_enqueue_catalog_assets() {
    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-header', get_theme_file_uri( 'styles/landpage/header.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-page-banner', get_theme_file_uri( 'styles/content/banner.css' ), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-page-content', get_theme_file_uri( 'styles/content/content.css' ), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-footer', get_theme_file_uri( 'styles/landpage/footer.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-global', get_theme_file_uri( 'styles/global.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-checkout', get_theme_file_uri( 'styles/acc-profile/checkout.css' ), array(), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_catalog_assets' );
