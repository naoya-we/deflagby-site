<?php
/**
 * archive-shop.php — 検索結果・店舗一覧ページ
 */
get_header();

// タクソノミーデータ
$areas  = get_terms( [ 'taxonomy' => 'area',  'hide_empty' => false, 'parent' => 0 ] );
$genres = get_terms( [ 'taxonomy' => 'genre', 'hide_empty' => false, 'parent' => 0 ] );
$tags   = get_terms( [ 'taxonomy' => 'shop_tag', 'hide_empty' => false ] );

// 現在の絞り込み状態
$current_area  = get_query_var( 'area'  ) ?: ( $_GET['area']  ?? '' );
$current_genre = get_query_var( 'genre' ) ?: ( $_GET['genre'] ?? '' );
$current_tags  = $_GET['shop_tag'] ?? [];
?>

<div class="container">
  <div class="search-results-header">
    <div>
      <h1 class="section-title" style="margin-bottom:4px">
        <?php
        if ( is_tax( 'area' ) ) {
            echo 'エリア：' . single_term_title( '', false );
        } elseif ( is_tax( 'genre' ) ) {
            echo 'ジャンル：' . single_term_title( '', false );
        } elseif ( is_tax( 'shop_tag' ) ) {
            echo 'タグ：' . single_term_title( '', false );
        } else {
            echo 'お店を探す';
        }
        ?>
      </h1>
      <p class="search-results-header__count">
        <strong><?php echo esc_html( $wp_query->found_posts ); ?></strong> 件のお店が見つかりました
      </p>
    </div>
  </div>

  <div class="search-layout">
    <!-- ===== フィルターパネル ===== -->
    <aside class="filter-panel" role="complementary" aria-label="絞り込み条件">
      <div class="filter-panel__title">🔍 絞り込む</div>
      <form id="filter-form" method="GET" action="<?php echo esc_url( get_post_type_archive_link( 'shop' ) ); ?>">
        <input type="hidden" name="post_type" value="shop">
        <div class="filter-row">
          <!-- エリア -->
          <div>
            <label for="filter-area" class="form-label">📍 エリア</label>
            <select name="area" id="filter-area" class="filter-select">
              <option value="">すべてのエリア</option>
              <?php if ( ! is_wp_error( $areas ) ) : foreach ( $areas as $area ) : ?>
                <option value="<?php echo esc_attr( $area->slug ); ?>"
                  <?php selected( $current_area, $area->slug ); ?>>
                  <?php echo esc_html( $area->name ); ?> (<?php echo esc_html( $area->count ); ?>)
                </option>
              <?php endforeach; endif; ?>
            </select>
          </div>

          <!-- ジャンル -->
          <div>
            <label for="filter-genre" class="form-label">🍽 ジャンル</label>
            <select name="genre" id="filter-genre" class="filter-select">
              <option value="">すべてのジャンル</option>
              <?php if ( ! is_wp_error( $genres ) ) : foreach ( $genres as $genre ) : ?>
                <option value="<?php echo esc_attr( $genre->slug ); ?>"
                  <?php selected( $current_genre, $genre->slug ); ?>>
                  <?php echo esc_html( $genre->name ); ?> (<?php echo esc_html( $genre->count ); ?>)
                </option>
              <?php endforeach; endif; ?>
            </select>
          </div>

          <!-- こだわりタグ -->
          <?php if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) : ?>
          <div>
            <span class="form-label">🏷 こだわり条件</span>
            <div class="filter-tags" role="group" aria-label="こだわり条件で絞り込む">
              <?php foreach ( $tags as $tag ) : ?>
                <label class="filter-tag <?php echo in_array( $tag->slug, (array) $current_tags ) ? 'is-active' : ''; ?>">
                  <input type="checkbox" name="shop_tag[]"
                         value="<?php echo esc_attr( $tag->slug ); ?>"
                         class="u-visually-hidden"
                         <?php checked( in_array( $tag->slug, (array) $current_tags ) ); ?>>
                  <?php echo esc_html( $tag->name ); ?>
                </label>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>

          <button type="submit" class="filter-submit" id="filter-submit-btn">絞り込む</button>
        </div>
      </form>
    </aside>

    <!-- ===== 検索結果グリッド ===== -->
    <div>
      <!-- ローディング -->
      <div class="loading-spinner" id="search-loading" aria-live="polite" aria-busy="false">
        <div class="spinner" role="status" aria-label="読み込み中"></div>
        <span>検索中...</span>
      </div>

      <!-- 結果グリッド -->
      <div class="shop-grid" id="search-results-grid">
        <?php if ( have_posts() ) : ?>
          <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template-parts/shop', 'card' ); ?>
          <?php endwhile; ?>
        <?php else : ?>
          <div class="empty-state" style="grid-column: 1/-1;">
            <div class="empty-state__icon">😔</div>
            <p class="empty-state__text">条件に合うお店が見つかりませんでした</p>
          </div>
        <?php endif; ?>
      </div>

      <!-- ページネーション -->
      <?php if ( have_posts() ) : ?>
        <div class="u-mt-md">
          <?php
            the_posts_pagination( [
              'mid_size'           => 2,
              'prev_text'          => '← 前へ',
              'next_text'          => '次へ →',
              'before_page_number' => '<span class="u-visually-hidden">ページ </span>',
            ] );
          ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php get_footer(); ?>
