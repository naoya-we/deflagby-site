<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- 1. LOADING SCREEN (電車アニメーション) -->
<div id="loading-screen">
    <div class="loading-content">
        <div class="loading-rail">
            <div class="loading-train"></div>
        </div>
        <div class="loading-text">KICHIGURU PORTAL LOADING...</div>
    </div>
</div>

<!-- SITE HEADER -->
<header class="site-header">
    <div class="header-container">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="brand-logo">
            <span>吉グル ポータル</span>
            <span class="brand-badge">地域文化</span>
        </a>

        <nav class="nav-desktop">
            <a href="#map-section">三鷹</a>
            <a href="#map-section">吉祥寺</a>
            <a href="#map-section">西荻窪</a>
            <a href="#gallery-section">イベント</a>
            <a href="#gallery-section">カルチャー</a>
        </nav>
    </div>
</header>
