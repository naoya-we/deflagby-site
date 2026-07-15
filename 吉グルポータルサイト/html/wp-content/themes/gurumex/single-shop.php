<?php
/**
 * single-shop.php — 店舗詳細ページ
 */
get_header();
the_post();

$shop_id       = get_the_ID();
$address       = get_post_meta( $shop_id, 'shop_address', true );
$lat           = get_post_meta( $shop_id, 'shop_lat', true );
$lng           = get_post_meta( $shop_id, 'shop_lng', true );
$budget        = get_post_meta( $shop_id, 'shop_budget', true );
$hours         = get_post_meta( $shop_id, 'shop_hours', true );
$closed        = get_post_meta( $shop_id, 'shop_closed', true );
$instagram_url = get_post_meta( $shop_id, 'shop_instagram_url', true );
$official_url  = get_post_meta( $shop_id, 'shop_official_url', true );
$tabelog_url   = get_post_meta( $shop_id, 'shop_tabelog_url', true );
$rating        = get_post_meta( $shop_id, 'shop_rating_owner', true );

$genre_terms   = get_the_terms( $shop_id, 'genre' );
$tag_terms     = get_the_terms( $shop_id, 'shop_tag' );
$genre_name    = ( $genre_terms && ! is_wp_error( $genre_terms ) ) ? $genre_terms[0]->name : '';
$is_fav        = is_user_logged_in() ? gurumex_is_favorite( $shop_id ) : false;
?>

<div class="container">
  <div class="shop-detail" itemscope itemtype="https://schema.org/Restaurant">

    <!-- パンくずリスト -->
    <nav aria-label="パンくず" style="font-size:0.78rem;color:var(--color-text-muted);margin-bottom:16px;">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>
      &rsaquo;
      <a href="<?php echo esc_url( get_post_type_archive_link( 'shop' ) ); ?>">お店一覧</a>
      &rsaquo;
      <span><?php the_title(); ?></span>
    </nav>

    <div class="shop-detail-layout">
      <!-- ===== メインカラム ===== -->
      <div>
        <!-- ヒーロー画像 -->
        <div class="shop-detail__hero">
          <?php if ( has_post_thumbnail() ) : ?>
            <?php if ( $instagram_url ) : ?>
              <a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="shop-detail__hero-link" title="Instagramの紹介投稿を開く（ワンタップで移動）">
                <img src="<?php the_post_thumbnail_url( 'large' ); ?>"
                     alt="<?php the_title(); ?>"
                     class="shop-detail__hero-img shop-detail__hero-img--linkable"
                     itemprop="image">
                <div class="shop-detail__hero-link-badge">
                  📸 Instagram投稿を開く
                </div>
              </a>
            <?php else : ?>
              <img src="<?php the_post_thumbnail_url( 'large' ); ?>"
                   alt="<?php the_title(); ?>"
                   class="shop-detail__hero-img"
                   itemprop="image">
            <?php endif; ?>
          <?php else : ?>
            <!-- アイキャッチ画像がない場合 -->
            <?php if ( $instagram_url ) : ?>
              <a href="<?php echo esc_url( $instagram_url ); ?>" target="_blank" rel="noopener noreferrer" class="shop-detail__hero-link" style="display:flex;align-items:center;justify-content:center;height:100%;text-decoration:none;">
                <div style="text-align:center;color:#777;padding-bottom:60px;">
                  <span style="font-size:2.5rem;display:block;margin-bottom:4px;">📸</span>
                  <span style="font-size:0.85rem;font-weight:700;color:var(--color-primary);">Instagram投稿を見る（ワンタップ移動）</span>
                </div>
              </a>
            <?php else : ?>
              <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#ccc;font-size:3rem;padding-bottom:60px;">🍴</div>
            <?php endif; ?>
          <?php endif; ?>

          <div class="shop-detail__hero-overlay" style="pointer-events:none;">
            <h1 class="shop-detail__name" itemprop="name"><?php the_title(); ?></h1>
            <?php if ( $genre_name ) : ?>
              <span class="shop-detail__genre-tag"><?php echo esc_html( $genre_name ); ?></span>
            <?php endif; ?>
          </div>
        </div>

        <!-- こだわりタグ（集計カウント付き & ユーザー報告対応） -->
        <?php
        $review_tag_counts = get_post_meta( $shop_id, 'gurumex_review_tag_counts', true );
        if ( ! is_array( $review_tag_counts ) ) {
            $review_tag_counts = [];
        }
        $official_slugs = [];
        if ( $tag_terms && ! is_wp_error( $tag_terms ) ) {
            foreach ( $tag_terms as $tag ) {
                $official_slugs[] = $tag->slug;
            }
        }
        ?>
        <?php if ( ! empty( $official_slugs ) || ! empty( $review_tag_counts ) ) : ?>
          <div class="shop-card__tags u-mt-sm" style="margin-bottom:20px;" aria-label="こだわりタグ">
            <!-- 公式タグ -->
            <?php if ( $tag_terms && ! is_wp_error( $tag_terms ) ) : foreach ( $tag_terms as $tag ) : 
              $count = isset( $review_tag_counts[ $tag->slug ] ) ? $review_tag_counts[ $tag->slug ] : 0;
              $count_label = $count > 0 ? " ({$count}回)" : "";
            ?>
              <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="shop-card__tag">
                <?php echo esc_html( $tag->name . $count_label ); ?>
              </a>
            <?php endforeach; endif; ?>

            <!-- ユーザー報告タグ（公式にないもの） -->
            <?php 
            foreach ( $review_tag_counts as $slug => $count ) {
                if ( ! in_array( $slug, $official_slugs ) && $count > 0 ) {
                    $term = get_term_by( 'slug', $slug, 'shop_tag' );
                    if ( $term ) {
                        ?>
                        <a href="<?php echo esc_url( get_term_link( $term ) ); ?>" 
                           class="shop-card__tag shop-card__tag--user-reported" 
                           style="border-style: dashed; background: #FFF9F6; border-color: var(--color-primary);"
                           title="ユーザーのレビューで報告された特徴です">
                          💬 <?php echo esc_html( $term->name . " ({$count}回)" ); ?>
                        </a>
                        <?php
                    }
                }
            }
            ?>
          </div>
        <?php endif; ?>


        <!-- 説明文 -->
        <?php if ( get_the_content() ) : ?>
          <div class="shop-info-card" itemprop="description">
            <?php the_content(); ?>
          </div>
        <?php endif; ?>

        <!-- Instagram 埋め込み -->
        <?php if ( $instagram_url ) : ?>
          <div class="instagram-embed-wrap" aria-label="Instagram投稿">
            <h2 class="section-title" style="font-size:1rem;margin-bottom:12px;">📸 Instagramより</h2>
            <blockquote class="instagram-media" data-instgrm-permalink="<?php echo esc_url( $instagram_url ); ?>"
                        data-instgrm-version="14" loading="lazy"
                        style="background:#FFF;border:0;border-radius:3px;box-shadow:0 0 1px 0 rgba(0,0,0,.5),0 1px 10px 0 rgba(0,0,0,.15);margin:1px;max-width:540px;min-width:326px;padding:0;width:99.375%;">
            </blockquote>
            <script async defer src="//www.instagram.com/embed.js"></script>
          </div>
        <?php endif; ?>

        <!-- 地図 -->
        <?php if ( $lat && $lng ) : ?>
          <div class="u-mt-md" aria-label="地図">
            <h2 class="section-title" style="font-size:1rem;margin-bottom:12px;">📍 地図</h2>
            <div class="map-container">
              <div id="shop-map" role="application" aria-label="<?php the_title(); ?>の地図"></div>
            </div>
          </div>
        <?php endif; ?>

        <!-- レビューセクション -->
        <div class="review-section u-mt-lg" id="reviews" aria-labelledby="reviews-title">
          <h2 class="section-title" id="reviews-title">口コミ・レビュー</h2>

          <!-- レビュー一覧（Site Reviewsプラグインが出力するショートコード） -->
          <?php echo do_shortcode( '[site_reviews assigned_posts="' . get_the_ID() . '" pagination="ajax" count="5"]' ); ?>

          <!-- レビュー投稿フォーム -->
          <div class="review-form" id="review-form-wrap">
            <?php if ( is_user_logged_in() ) : ?>
              <h3 class="review-form__title">レビューを投稿する</h3>
              <?php echo do_shortcode( '[site_reviews_form assigned_posts="' . get_the_ID() . '"]' ); ?>
            <?php else : ?>
              <div class="login-prompt">
                <p class="login-prompt__text">
                  レビューを投稿するにはログインが必要です
                </p>
                <a href="<?php echo esc_url( wp_login_url( get_permalink() . '#reviews' ) ); ?>"
                   class="login-prompt__btn" id="review-login-btn">
                  ログイン / 新規会員登録はこちら
                </a>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- ===== サイドバー ===== -->
      <aside class="shop-detail-sidebar">

        <!-- お気に入りボタン -->
        <button class="fav-btn js-fav-btn <?php echo $is_fav ? 'is-fav' : ''; ?>"
                data-shop-id="<?php echo esc_attr( $shop_id ); ?>"
                id="detail-fav-btn"
                aria-pressed="<?php echo $is_fav ? 'true' : 'false'; ?>"
                aria-label="<?php echo $is_fav ? 'お気に入りから外す' : 'お気に入りに追加'; ?>">
          <span class="fav-btn__icon"><?php echo $is_fav ? '❤️' : '🤍'; ?></span>
          <span class="fav-btn__text"><?php echo $is_fav ? 'お気に入り済み' : 'お気に入りに追加'; ?></span>
        </button>

        <!-- 評価 -->
        <?php if ( $rating ) : ?>
          <div class="rating-chart">
            <h2 class="section-title" style="font-size:0.9rem;margin-bottom:12px;">独自評価</h2>
            <div class="rating-overall">
              <div class="rating-overall__num" aria-label="評価<?php echo esc_attr( $rating ); ?>">
                <?php echo esc_html( number_format( $rating, 1 ) ); ?>
              </div>
              <div>
                <?php echo gurumex_render_stars( $rating ); ?>
                <div class="rating-count">/ 5.0</div>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- 基本情報カード -->
        <div class="shop-info-card">
          <h2 class="section-title" style="font-size:0.9rem;margin-bottom:8px;">基本情報</h2>

          <?php if ( $address ) : ?>
            <div class="shop-info-row">
              <span class="shop-info-row__label">住所</span>
              <address class="shop-info-row__value" itemprop="address" style="font-style:normal;">
                <?php echo esc_html( $address ); ?>
              </address>
            </div>
          <?php endif; ?>

          <?php if ( $hours ) : ?>
            <div class="shop-info-row">
              <span class="shop-info-row__label">営業時間</span>
              <div class="shop-info-row__value" itemprop="openingHours">
                <?php echo nl2br( esc_html( $hours ) ); ?>
              </div>
            </div>
          <?php endif; ?>

          <?php if ( $closed ) : ?>
            <div class="shop-info-row">
              <span class="shop-info-row__label">定休日</span>
              <div class="shop-info-row__value"><?php echo esc_html( $closed ); ?></div>
            </div>
          <?php endif; ?>

          <?php if ( $budget ) : ?>
            <div class="shop-info-row">
              <span class="shop-info-row__label">予算</span>
              <div class="shop-info-row__value" itemprop="priceRange">
                <?php echo esc_html( gurumex_budget_label( $budget ) ); ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <!-- 外部リンク -->
        <div class="shop-links">
          <?php if ( $official_url ) : ?>
            <a href="<?php echo esc_url( $official_url ); ?>"
               target="_blank" rel="noopener noreferrer"
               class="shop-link-btn shop-link-btn--primary" id="official-url-btn">
              🌐 公式サイト
            </a>
          <?php endif; ?>
          <?php if ( $instagram_url ) : ?>
            <a href="<?php echo esc_url( $instagram_url ); ?>"
               target="_blank" rel="noopener noreferrer"
               class="shop-link-btn shop-link-btn--outline" id="instagram-link-btn">
              📸 Instagram
            </a>
          <?php endif; ?>
          <?php if ( $tabelog_url ) : ?>
            <a href="<?php echo esc_url( $tabelog_url ); ?>"
               target="_blank" rel="noopener noreferrer"
               class="shop-link-btn shop-link-btn--outline" id="tabelog-link-btn">
              🍽 食べログ
            </a>
          <?php endif; ?>
          <?php if ( $lat && $lng ) : ?>
            <a href="https://www.google.com/maps?q=<?php echo esc_attr( $lat ); ?>,<?php echo esc_attr( $lng ); ?>"
               target="_blank" rel="noopener noreferrer"
               class="shop-link-btn shop-link-btn--outline" id="google-map-btn">
              🗺 Googleマップ
            </a>
          <?php endif; ?>
        </div>

      </aside>
    </div>
  </div>
</div>

<?php get_footer(); ?>
