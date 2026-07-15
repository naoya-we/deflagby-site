<?php
/**
 * footer.php — 共通フッター
 */
?>
</main><!-- /.site-main -->

<!-- ===== SITE FOOTER ===== -->
<footer class="site-footer" role="contentinfo">
  <div class="footer-inner">
    <!-- ブランド -->
    <div class="footer-brand">
      <div class="footer-logo">
        <span class="footer-logo__icon" aria-hidden="true">🍜</span>
        <span class="footer-logo__text"><?php bloginfo( 'name' ); ?></span>
      </div>
      <p class="footer-desc">
        吉祥寺エリアの美味しいお店を探せるグルメポータル。<br>
        Instagramと連動した豊富な写真と口コミで、<br>あなたにぴったりのお店が見つかります。
      </p>
    </div>

    <!-- リンク -->
    <div>
      <ul class="footer-links" role="list">
        <li class="footer-link-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップページ</a></li>
        <li class="footer-link-item"><a href="<?php echo esc_url( home_url( '/shop' ) ); ?>">お店を探す</a></li>
        <?php if ( is_user_logged_in() ) : ?>
          <li class="footer-link-item"><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'mypage' ) ) ); ?>">マイページ</a></li>
        <?php else : ?>
          <li class="footer-link-item"><a href="<?php echo esc_url( wp_login_url() ); ?>">ログイン</a></li>
          <li class="footer-link-item"><a href="<?php echo esc_url( wp_registration_url() ); ?>">会員登録</a></li>
        <?php endif; ?>
        <li class="footer-link-item"><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>">お問い合わせ</a></li>
        <li class="footer-link-item"><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'privacy-policy' ) ) ); ?>">プライバシーポリシー</a></li>
        <li class="footer-link-item"><a href="<?php echo esc_url( get_permalink( get_page_by_path( 'terms' ) ) ); ?>">利用規約</a></li>
      </ul>
    </div>
  </div>

  <div class="footer-inner">
    <div class="footer-bottom">
      <p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All Rights Reserved.</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
