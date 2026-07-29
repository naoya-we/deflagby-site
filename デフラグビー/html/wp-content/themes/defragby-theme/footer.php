</div><!-- #content -->

<?php
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
?>

<footer id="colophon" class="site-footer">
  <!-- Main Footer Content -->
  <div class="footer-main">
    <div class="footer-container main-grid">
      <div class="footer-branding">
        <h2 class="footer-logo">
          <span class="logo-main"><?php echo esc_html( get_multilang_text('第3回7人制デフラグビー世界大会', '3rd World Deaf Rugby Sevens') ); ?></span>
          <span class="logo-sub">TOKYO 2026</span>
        </h2>
        <p class="footer-desc">
          <?php echo esc_html( get_multilang_text(
            'ラグビーを通しての平等（Equal through Rugby）を掲げ、聴覚障がい者ラグビーの更なる普及と国際交流を目指す世界大会です。',
            'Dedicated to "Equal through Rugby," promoting the development of deaf rugby and encouraging international exchange through sports.'
          ) ); ?>
        </p>
        <div class="footer-socials">
          <a href="https://x.com/JapanDeafRugby" class="social-link" aria-label="X (Twitter)" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-x-twitter"></i></a>
          <a href="https://www.facebook.com/JapanDeafRugby" class="social-link" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/japandeafrugby/" class="social-link" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram"></i></a>
          <a href="https://www.youtube.com/@world_deaf_rugby_2026" class="social-link" aria-label="YouTube" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-youtube"></i></a>
        </div>
      </div>

      <div class="footer-nav-block">
        <h4 class="nav-title"><?php echo esc_html( get_multilang_text('コンテンツ', 'Site Map') ); ?></h4>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url( home_url( '/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('トップページ', 'Top') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('大会について', 'About the Tournament') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/experience/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('体験する（疑似体験）', 'Experience') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/watch/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('観戦する', 'Spectate') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/how-to-enjoy/sponsor/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('スポンサー紹介', 'Sponsors') ); ?></a></li>
        </ul>
      </div>

      <div class="footer-nav-block">
        <h4 class="nav-title"><?php echo esc_html( get_multilang_text('インフォメーション', 'Info & Shop') ); ?></h4>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url( home_url( '/information/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('競技・会場情報', 'Competition & Venue') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/information/faq/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('よくある質問 (FAQ)', 'FAQ') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/teams/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('チーム情報', 'Teams') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/goods/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('グッズ紹介', 'Merchandise') ); ?></a></li>
          <li><a href="<?php echo esc_url( home_url( '/goods/terms/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('特定商取引法の表記', 'Legal Notice') ); ?></a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Copyright -->
  <div class="footer-bottom">
    <div class="footer-container bottom-flex">
      <p class="copyright">&copy; 2026 <?php echo esc_html( get_multilang_text('日本聴覚障がい者ラグビーフットボール連盟', 'Japan Deaf Rugby Football Union') ); ?>. All Rights Reserved.</p>
      <div class="bottom-links">
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>"><?php echo esc_html( get_multilang_text('お問い合わせ', 'Contact Us') ); ?></a>
      </div>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
