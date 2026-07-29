<?php
get_header();
?>

<main id="primary" class="site-main container">
  <header class="page-header">
    <h1 class="page-title"><?php echo esc_html( get_multilang_text('ニュース一覧', 'News & Announcements') ); ?></h1>
  </header>

  <div class="archive-layout">
    <div class="posts-grid">
      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class('card post-card'); ?>>
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="post-thumbnail-wrapper">
                <a href="<?php the_permalink(); ?>">
                  <?php the_post_thumbnail('medium_large'); ?>
                </a>
              </div>
            <?php endif; ?>

            <div class="post-content">
              <span class="post-date"><?php echo get_the_date(); ?></span>
              <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
              <div class="post-excerpt">
                <?php the_excerpt(); ?>
              </div>
              <a href="<?php the_permalink(); ?>" class="read-more-btn"><?php echo esc_html( get_multilang_text('詳細を見る', 'Read More') ); ?> <i class="fa-solid fa-arrow-right"></i></a>
            </div>
          </article>
        <?php endwhile; ?>

        <?php the_posts_navigation(); ?>
      <?php else : ?>
        <p><?php echo esc_html( get_multilang_text('お知らせはまだありません。', 'No news published yet.') ); ?></p>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php
get_footer();
