<?php
/**
 * 吉グルポータル Theme functions and definitions
 */

if (!function_exists('kichiguru_setup')) :
    function kichiguru_setup() {
        add_theme_support('automatic-feed-links');
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');

        register_nav_menus(array(
            'primary' => __('Primary Menu', 'kichiguru'),
        ));
    }
endif;
add_action('after_setup_theme', 'kichiguru_setup');

/**
 * Enqueue scripts and styles.
 */
function kichiguru_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'kichiguru-google-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap',
        array(),
        null
    );

    // Theme main style sheet
    wp_enqueue_style('kichiguru-style', get_stylesheet_uri(), array(), '1.0.0');

    // GSAP Core
    wp_enqueue_script(
        'gsap',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js',
        array(),
        '3.12.5',
        true
    );

    // GSAP ScrollTrigger
    wp_enqueue_script(
        'gsap-scroll-trigger',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js',
        array('gsap'),
        '3.12.5',
        true
    );

    // GSAP MotionPathPlugin (線路パスに完全に一致して走行させる用)
    wp_enqueue_script(
        'gsap-motion-path',
        'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/MotionPathPlugin.min.js',
        array('gsap'),
        '3.12.5',
        true
    );

    // Theme Main Custom Script
    wp_enqueue_script(
        'kichiguru-main-js',
        get_template_directory_uri() . '/assets/js/main.js',
        array('gsap', 'gsap-scroll-trigger', 'gsap-motion-path'),
        '1.0.1',
        true
    );
}
add_action('wp_enqueue_scripts', 'kichiguru_scripts');
