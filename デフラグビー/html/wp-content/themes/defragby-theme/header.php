<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="profile" href="https://gmpg.org/xfn/11">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@700;900&display=swap" rel="stylesheet">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
// Get current language from query string for visual demo
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
?>

<header id="masthead" class="site-header">
  <div class="header-container">
    <div class="site-branding">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>" rel="home" class="logo-link">
        <?php
        $site_logo_url = get_option( 'defragby_logo_image_url', '' );
        if ( ! empty( $site_logo_url ) ) : ?>
          <img
            src="<?php echo esc_url( $site_logo_url ); ?>"
            alt="<?php echo esc_attr( get_multilang_text('第3回7人制デフラグビー世界大会 TOKYO 2026', '3rd World Deaf Rugby Sevens TOKYO 2026') ); ?>"
            class="site-logo-img"
          >
        <?php endif; ?>
        <div class="logo-text">
          <span class="logo-main"><?php echo esc_html( get_multilang_text('第3回7人制デフラグビー世界大会', '3rd World Deaf Rugby Sevens') ); ?></span>
          <span class="logo-sub"><?php echo esc_html( get_multilang_text('TOKYO 2026', 'TOKYO 2026 CHAMPIONSHIP') ); ?></span>
        </div>
      </a>
    </div>

    <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
      <span class="bar"></span>
      <span class="bar"></span>
      <span class="bar"></span>
    </button>

    <nav id="site-navigation" class="main-navigation">
      <ul id="primary-menu" class="nav-menu">
        <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('大会について', 'About') ); ?></a></li>
        
        <li class="menu-item-has-children">
          <a href="#"><?php echo esc_html( get_multilang_text('大会の楽しみ方', 'How to Enjoy') ); ?> <i class="fa-solid fa-chevron-down"></i></a>
          <ul class="sub-menu">
            <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/experience/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('体験する', 'Experience') ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/watch/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('観戦する', 'Spectate') ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/sponsor/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('スポンサー紹介', 'Sponsors') ); ?></a></li>
            <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/support/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('応援する', 'Support') ); ?></a></li>
          </ul>
        </li>

        <li><a href="<?php echo esc_url( home_url( '/information/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('競技・会場情報', 'Info') ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/information/faq/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('FAQ', 'FAQ') ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/teams/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('チーム', 'Teams') ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/goods/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('グッズ', 'Shop') ); ?></a></li>
        <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>" class="nav-cta"><?php echo esc_html( get_multilang_text('お問い合わせ', 'Contact') ); ?></a></li>
      </ul>
    </nav>

    <div class="lang-switcher">
      <a href="?lang=ja" class="lang-btn <?php echo $current_lang === 'ja' ? 'active' : ''; ?>">JA</a>
      <span class="lang-divider">|</span>
      <a href="?lang=en" class="lang-btn <?php echo $current_lang === 'en' ? 'active' : ''; ?>">EN</a>
    </div>
  </div>
</header>

<div id="content" class="site-content">
