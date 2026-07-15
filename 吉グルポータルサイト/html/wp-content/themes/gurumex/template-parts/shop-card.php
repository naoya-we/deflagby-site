<?php
/**
 * template-parts/shop-card.php — 店舗カードコンポーネント
 */
$shop_id     = get_the_ID();
$genre_terms = get_the_terms( $shop_id, 'genre' );
$tag_terms   = get_the_terms( $shop_id, 'shop_tag' );
$budget      = get_post_meta( $shop_id, 'shop_budget', true );
$rating      = get_post_meta( $shop_id, 'shop_rating_owner', true );
$genre_name  = ( $genre_terms && ! is_wp_error( $genre_terms ) ) ? $genre_terms[0]->name : '';
$is_fav      = is_user_logged_in() ? gurumex_is_favorite( $shop_id ) : false;
?>
<article class="shop-card" itemscope itemtype="https://schema.org/Restaurant">
  <a href="<?php the_permalink(); ?>" class="shop-card__link-wrap" aria-label="<?php the_title(); ?>の詳細を見る">
    <div class="shop-card__img-wrap">
      <?php if ( has_post_thumbnail() ) : ?>
        <img src="<?php the_post_thumbnail_url( 'medium' ); ?>"
             alt="<?php the_title(); ?>"
             class="shop-card__img"
             loading="lazy"
             itemprop="image">
      <?php else : ?>
        <div class="shop-card__img shop-card__img--placeholder" aria-hidden="true"
             style="display:flex;align-items:center;justify-content:center;font-size:2.5rem;background:#F3F2EE;">🍴</div>
      <?php endif; ?>

      <?php if ( $genre_name ) : ?>
        <span class="shop-card__genre"><?php echo esc_html( $genre_name ); ?></span>
      <?php endif; ?>
    </div>
  </a>

  <!-- お気に入りボタン -->
  <button class="shop-card__fav js-fav-btn <?php echo $is_fav ? 'is-fav' : ''; ?>"
          data-shop-id="<?php echo esc_attr( $shop_id ); ?>"
          aria-label="<?php the_title(); ?>をお気に入りに<?php echo $is_fav ? '登録済み' : '追加'; ?>"
          aria-pressed="<?php echo $is_fav ? 'true' : 'false'; ?>">
    <?php echo $is_fav ? '❤️' : '🤍'; ?>
  </button>

  <div class="shop-card__body">
    <h3 class="shop-card__name" itemprop="name">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h3>

    <div class="shop-card__meta">
      <?php if ( $rating ) : ?>
        <div class="shop-card__rating" aria-label="評価<?php echo esc_attr( $rating ); ?>">
          <span aria-hidden="true">★</span>
          <?php echo esc_html( number_format( $rating, 1 ) ); ?>
        </div>
      <?php endif; ?>
      <?php if ( $budget ) : ?>
        <span class="shop-card__budget" itemprop="priceRange">
          <?php echo esc_html( gurumex_budget_label( $budget ) ); ?>
        </span>
      <?php endif; ?>
    </div>

    <?php if ( $tag_terms && ! is_wp_error( $tag_terms ) ) : ?>
      <ul class="shop-card__tags" aria-label="こだわりタグ">
        <?php foreach ( array_slice( $tag_terms, 0, 3 ) as $tag ) : ?>
          <li class="shop-card__tag"><?php echo esc_html( $tag->name ); ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</article>
