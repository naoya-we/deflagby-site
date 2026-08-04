<?php
/**
 * The template for displaying all pages
 */
get_header();
?>

<main id="primary" class="site-main container">
  <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="page-header" style="margin-bottom: 40px; text-align: center;">
        <h1 class="page-title section-title" style="padding-bottom: 15px; margin-bottom: 0;">
          <?php the_title(); ?>
        </h1>
      </header>

      <div class="entry-content" style="background-color: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border); max-width: 900px; margin: 0 auto;">
        <?php
        the_content();
        
        wp_link_pages( array(
          'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'defragby-theme' ),
          'after'  => '</div>',
        ) );
        ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>

<?php
get_footer();
