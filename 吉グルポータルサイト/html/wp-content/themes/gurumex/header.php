<?php
/**
 * header.php — 共通ヘッダー
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#E8734A">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ===== SITE HEADER ===== -->
<header class="site-header" role="banner">
  <div class="header-inner">

    <!-- ロゴ -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php bloginfo( 'name' ); ?> ホームへ">
      <span class="site-logo__icon" aria-hidden="true">🍜</span>
      <span class="site-logo__text">
        <?php bloginfo( 'name' ); ?>
        <span class="site-logo__sub">吉祥寺グルメ情報</span>
      </span>
    </a>

    <!-- デスクトップナビ -->
    <nav class="header-nav" aria-label="メインナビゲーション">
      <?php
        wp_nav_menu( [
          'theme_location' => 'primary',
          'container'      => false,
          'menu_class'     => 'header-nav__list',
          'items_wrap'     => '<ul id="%1$s" class="%2$s" role="list">%3$s</ul>',
          'fallback_cb'    => 'gurumex_default_nav',
        ] );
      ?>
      <?php if ( is_user_logged_in() ) : ?>
        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'mypage' ) ) ); ?>" class="header-nav__cta">マイページ</a>
      <?php else : ?>
        <a href="<?php echo esc_url( wp_login_url() ); ?>" class="header-nav__cta">ログイン</a>
      <?php endif; ?>
    </nav>

    <!-- ハンバーガーボタン（モバイル） -->
    <button class="hamburger" id="hamburger-btn" aria-label="メニューを開く" aria-expanded="false" aria-controls="nav-drawer">
      <span class="hamburger__line" aria-hidden="true"></span>
      <span class="hamburger__line" aria-hidden="true"></span>
      <span class="hamburger__line" aria-hidden="true"></span>
    </button>
  </div>
</header>

<!-- ===== NAV DRAWER（モバイル） ===== -->
<div class="nav-drawer" id="nav-drawer" role="dialog" aria-modal="true" aria-label="ナビゲーションメニュー">
  <div class="nav-drawer__overlay" id="nav-overlay"></div>
  <nav class="nav-drawer__panel">
    <ul class="nav-menu" role="list">
      <li class="nav-menu__item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">🏠 トップページ</a></li>
      <li class="nav-menu__item"><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">🔍 お店を探す</a></li>
      <?php if ( is_user_logged_in() ) : ?>
        <li class="nav-menu__item"><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'mypage' ) ) ); ?>">👤 マイページ</a></li>
        <li class="nav-menu__item"><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">ログアウト</a></li>
      <?php else : ?>
        <li class="nav-menu__item"><a href="<?php echo esc_url( wp_login_url() ); ?>" class="nav-cta">ログイン / 会員登録</a></li>
      <?php endif; ?>
    </ul>
  </nav>
</div>

<!-- ===== MAIN ===== -->
<main id="main-content" class="site-main">
<?php

/**
 * ナビゲーションが未設定時のフォールバック
 */
function gurumex_default_nav() {
    echo '<ul class="header-nav__list" role="list">
      <li class="header-nav__item"><a href="' . esc_url( home_url( '/' ) ) . '">トップ</a></li>
      <li class="header-nav__item"><a href="' . esc_url( home_url( '/shop' ) ) . '">お店を探す</a></li>
    </ul>';
}
