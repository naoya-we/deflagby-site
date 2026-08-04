<?php
/**
 * index.php — フォールバックテンプレート
 */
get_header();
?>
<div class="container u-mt-md">
  <?php if ( have_posts() ) : ?>
    <div class="shop-grid">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php get_template_part( 'template-parts/shop', 'card' ); ?>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else : ?>
    <div class="empty-state">
      <div class="empty-state__icon">📄</div>
      <p class="empty-state__text">コンテンツが見つかりませんでした</p>
    </div>
  <?php endif; ?>
</div>
<?php get_footer(); ?>
