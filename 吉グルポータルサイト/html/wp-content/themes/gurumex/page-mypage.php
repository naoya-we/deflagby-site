<?php
/**
 * page-mypage.php — マイページ（お気に入り一覧）
 * このファイルを使うには固定ページのスラッグを "mypage" にする
 */
get_header();

// 未ログインはログインページへリダイレクト
if ( ! is_user_logged_in() ) {
    wp_redirect( wp_login_url( get_permalink() ) );
    exit;
}

$user     = wp_get_current_user();
$favs     = get_user_meta( $user->ID, 'gurumex_favorites', true );
$fav_ids  = is_array( $favs ) ? $favs : [];

// お気に入り店舗クエリ
$fav_query = null;
if ( ! empty( $fav_ids ) ) {
    $fav_query = new WP_Query( [
        'post_type'      => 'shop',
        'posts_per_page' => -1,
        'post__in'       => $fav_ids,
        'orderby'        => 'post__in',
    ] );
}
?>

<div class="container">
  <div class="u-mt-md" style="max-width:800px;margin:0 auto;">

    <!-- マイページヘッダー -->
    <div class="mypage-header">
      <div class="mypage-avatar" aria-hidden="true">
        <?php echo mb_substr( $user->display_name, 0, 1 ); ?>
      </div>
      <div>
        <p class="mypage-name"><?php echo esc_html( $user->display_name ); ?>さん</p>
        <p class="mypage-email"><?php echo esc_html( $user->user_email ); ?></p>
      </div>
    </div>

    <!-- お気に入り一覧 -->
    <section aria-labelledby="fav-list-title">
      <h2 class="section-title" id="fav-list-title">
        ❤️ お気に入りのお店 (<?php echo count( $fav_ids ); ?>件)
      </h2>

      <?php if ( $fav_query && $fav_query->have_posts() ) : ?>
        <div class="shop-grid" id="mypage-fav-grid">
          <?php while ( $fav_query->have_posts() ) : $fav_query->the_post(); ?>
            <?php get_template_part( 'template-parts/shop', 'card' ); ?>
          <?php endwhile; wp_reset_postdata(); ?>
        </div>
      <?php else : ?>
        <div class="empty-state">
          <div class="empty-state__icon">🤍</div>
          <p class="empty-state__text">まだお気に入りのお店がありません</p>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'shop' ) ); ?>"
             class="shop-link-btn shop-link-btn--primary" style="display:inline-flex;margin-top:16px;" id="find-shops-btn">
            お店を探す →
          </a>
        </div>
      <?php endif; ?>
    </section>

    <!-- ログアウトリンク -->
    <div class="u-text-center u-mt-lg">
      <a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"
         class="shop-link-btn shop-link-btn--outline" id="logout-btn"
         style="display:inline-flex;">
        ログアウト
      </a>
    </div>
  </div>
</div>

<?php get_footer(); ?>
