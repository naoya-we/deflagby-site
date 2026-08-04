<?php
/**
 * The template for displaying all single posts
 */
get_header();
?>

<main id="primary" class="site-main container">
  <div style="max-width: 800px; margin: 0 auto;">
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header" style="margin-bottom: 30px;">
          <div class="entry-meta" style="color: var(--color-text-muted); font-size: 0.9rem; margin-bottom: 10px;">
            <?php echo get_the_date(); ?>
          </div>
          <h1 class="entry-title" style="color: var(--color-primary-dark); font-size: 2.2rem; line-height: 1.3;">
            <?php the_title(); ?>
          </h1>
        </header>

        <?php if ( has_post_thumbnail() ) : ?>
          <div class="post-thumbnail" style="margin-bottom: 30px; border-radius: 8px; overflow: hidden; max-height: 400px;">
            <?php the_post_thumbnail('large', array('style' => 'width:100%; height:auto; object-fit:cover;')); ?>
          </div>
        <?php endif; ?>

        <div class="entry-content" style="background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border); line-height: 1.8; font-size: 1.05rem;">
          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
</main>

<?php
get_footer();
