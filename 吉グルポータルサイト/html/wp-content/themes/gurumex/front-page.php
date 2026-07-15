<?php
/**
 * front-page.php — トップページ
 */
get_header();

// タクソノミーデータ取得
$areas  = get_terms( [ 'taxonomy' => 'area',  'hide_empty' => false, 'parent' => 0 ] );
$genres = get_terms( [ 'taxonomy' => 'genre', 'hide_empty' => false, 'parent' => 0 ] );
$tags   = get_terms( [ 'taxonomy' => 'shop_tag', 'hide_empty' => false ] );

// 新着店舗
$new_shops = new WP_Query( [
    'post_type'      => 'shop',
    'posts_per_page' => 8,
    'orderby'        => 'date',
    'order'          => 'DESC',
] );

// 人気ランキング（レビュー数が多い順 ※Site Reviewsプラグイン利用想定）
$popular_shops = new WP_Query( [
    'post_type'      => 'shop',
    'posts_per_page' => 5,
    'orderby'        => 'comment_count',
    'order'          => 'DESC',
] );
?>

<!-- =============================================
     HERO SECTION
     ============================================= -->
<section class="hero" aria-labelledby="hero-title">
  <div class="hero__badge" aria-label="Instagramと連動">
    <span aria-hidden="true">📸</span> Instagram連動グルメ情報
  </div>
  <h1 class="hero__title" id="hero-title">
    吉祥寺の<span>美味しい</span>を<br>まるごと探せる
  </h1>
  <p class="hero__sub">エリア・ジャンル・シーンで絞り込んで、<br>あなたにぴったりのお店を見つけよう</p>

  <!-- 検索ボックス -->
  <div class="search-box" role="search" aria-label="店舗検索">
    <form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="GET" id="hero-search-form">
      <input type="hidden" name="post_type" value="shop">
      <div class="search-box__row">
        <!-- エリア -->
        <select name="area" id="hero-area" class="search-box__select" aria-label="エリアを選ぶ">
          <option value="">📍 エリアを選ぶ</option>
          <?php if ( ! is_wp_error( $areas ) ) : foreach ( $areas as $area ) : ?>
            <option value="<?php echo esc_attr( $area->slug ); ?>"><?php echo esc_html( $area->name ); ?></option>
          <?php endforeach; endif; ?>
        </select>

        <!-- ジャンル -->
        <select name="genre" id="hero-genre" class="search-box__select" aria-label="ジャンルを選ぶ">
          <option value="">🍽 ジャンルを選ぶ</option>
          <?php if ( ! is_wp_error( $genres ) ) : foreach ( $genres as $genre ) : ?>
            <option value="<?php echo esc_attr( $genre->slug ); ?>"><?php echo esc_html( $genre->name ); ?></option>
          <?php endforeach; endif; ?>
        </select>
      </div>

      <!-- こだわりタグ -->
      <?php if ( ! is_wp_error( $tags ) && ! empty( $tags ) ) : ?>
      <div class="search-box__tags" role="group" aria-label="こだわり条件">
        <?php foreach ( $tags as $tag ) : ?>
          <input type="checkbox" name="shop_tag[]" value="<?php echo esc_attr( $tag->slug ); ?>"
                 id="tag-<?php echo esc_attr( $tag->slug ); ?>" class="tag-check">
          <label for="tag-<?php echo esc_attr( $tag->slug ); ?>"><?php echo esc_html( $tag->name ); ?></label>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <button type="submit" class="search-box__btn" id="hero-search-btn">
        <span aria-hidden="true">🔍</span> お店を探す
      </button>
    </form>
  </div>

  <!-- 統計 -->
  <div class="hero__stats" aria-label="サイト統計">
    <?php
      $shop_count = wp_count_posts( 'shop' )->publish;
    ?>
    <div class="hero__stat">
      <strong><?php echo esc_html( $shop_count ); ?>+</strong>
      <span>掲載店舗</span>
    </div>
    <div class="hero__stat">
      <strong>吉祥寺</strong>
      <span>エリア特化</span>
    </div>
    <div class="hero__stat">
      <strong>📸</strong>
      <span>Instagram連動</span>
    </div>
  </div>
</section>


<!-- =============================================
     新着店舗
     ============================================= -->
<section class="section" aria-labelledby="new-shops-title">
  <div class="container">
    <h2 class="section-title" id="new-shops-title">新着のお店</h2>

    <?php if ( $new_shops->have_posts() ) : ?>
      <div class="shop-grid" id="new-shops-grid">
        <?php while ( $new_shops->have_posts() ) : $new_shops->the_post(); ?>
          <?php get_template_part( 'template-parts/shop', 'card' ); ?>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
      <div class="u-text-center u-mt-md">
        <a href="<?php echo esc_url( get_post_type_archive_link( 'shop' ) ); ?>"
           class="shop-link-btn shop-link-btn--outline" id="view-all-shops-btn">
          すべてのお店を見る →
        </a>
      </div>
    <?php else : ?>
      <div class="empty-state">
        <div class="empty-state__icon">🏪</div>
        <p class="empty-state__text">まだ店舗が登録されていません</p>
      </div>
    <?php endif; ?>
  </div>
</section>


<!-- =============================================
     人気ランキング
     ============================================= -->
<section class="section section--gray" aria-labelledby="ranking-title">
  <div class="container">
    <h2 class="section-title" id="ranking-title">人気ランキング</h2>

    <?php if ( $popular_shops->have_posts() ) : ?>
      <ol class="ranking-list">
        <?php $rank = 1; while ( $popular_shops->have_posts() ) : $popular_shops->the_post(); ?>
          <?php
            $genre_terms = get_the_terms( get_the_ID(), 'genre' );
            $genre_name  = ( $genre_terms && ! is_wp_error( $genre_terms ) ) ? $genre_terms[0]->name : '';
            $rating      = get_post_meta( get_the_ID(), 'shop_rating_owner', true );
            $rank_class  = $rank <= 3 ? "ranking-item__rank--{$rank}" : 'ranking-item__rank--other';
          ?>
          <li class="ranking-item">
            <div class="ranking-item__rank <?php echo esc_attr( $rank_class ); ?>" aria-label="<?php echo $rank; ?>位">
              <?php echo $rank; ?>
            </div>
            <?php if ( has_post_thumbnail() ) : ?>
              <img src="<?php the_post_thumbnail_url( 'thumbnail' ); ?>"
                   alt="<?php the_title(); ?>"
                   class="ranking-item__img"
                   loading="lazy">
            <?php endif; ?>
            <div class="ranking-item__info">
              <div class="ranking-item__name">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </div>
              <div class="ranking-item__genre"><?php echo esc_html( $genre_name ); ?></div>
            </div>
            <?php if ( $rating ) : ?>
              <div class="ranking-item__score" aria-label="評価<?php echo esc_attr( $rating ); ?>">
                <?php echo esc_html( number_format( $rating, 1 ) ); ?>
              </div>
            <?php endif; ?>
          </li>
          <?php $rank++; endwhile; wp_reset_postdata(); ?>
      </ol>
    <?php else : ?>
      <div class="empty-state">
        <div class="empty-state__icon">🏆</div>
        <p class="empty-state__text">まだランキングデータがありません</p>
      </div>
    <?php endif; ?>
  </div>
</section>


<!-- =============================================
     全体マップ（Leaflet.js）
     ============================================= -->
<section class="section" aria-labelledby="map-title">
  <div class="container">
    <h2 class="section-title" id="map-title">マップで探す</h2>
    <div class="map-container">
      <div id="shop-map" role="application" aria-label="吉祥寺グルメマップ"></div>
    </div>
  </div>
</section>

<?php get_footer(); ?>
