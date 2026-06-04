<?php
require_once get_theme_file_path( 'functions4.php' );

  function mytheme_setup() {
      add_theme_support( 'title-tag' );
  }
  add_action( 'after_setup_theme', 'mytheme_setup' );

function mytheme_enqueue_assets() {
    if ( ! is_front_page() && ! is_home() ) {
        return;
    }

    wp_enqueue_style( 'mytheme-style', get_stylesheet_uri(), array(), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-header', get_theme_file_uri( 'styles/landpage/header.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-banner', get_theme_file_uri( 'styles/landpage/banner.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-shop-brand', get_theme_file_uri( 'styles/landpage/shop_brand.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-shops', get_theme_file_uri( 'styles/landpage/shops.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-footer', get_theme_file_uri( 'styles/landpage/footer.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
    wp_enqueue_style( 'mytheme-global', get_theme_file_uri( 'styles/global.css' ), array( 'mytheme-style' ), wp_get_theme()->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'mytheme_enqueue_assets' );
