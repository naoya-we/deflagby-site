<?php
/**
 * Template Name: How to Enjoy - Watch
 */
get_header();
?>

<main id="primary" class="site-main container">
  <header class="page-header page-header--watch" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('観戦する', 'Spectate') ); ?></h1>
  </header>

  <div class="content-container" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 40px;">
    
    <section>
      <h2 style="color: var(--color-primary-dark); font-size: 1.5rem; margin-bottom: 15px; border-left: 5px solid var(--color-secondary); padding-left: 15px;">
        <?php echo esc_html( get_multilang_text('招待案内', 'Invitation Program') ); ?>
      </h2>
      <p style="line-height: 1.7;">
        <?php echo esc_html( get_multilang_text(
          '教育機関や地域社会の皆様を対象とした招待プログラムを実施します。事前登録により、特別席での観戦をご案内いたします。',
          'We offer special school and community group invitation programs. Pre-registration guarantees priority seating.'
        ) ); ?>
      </p>
    </section>

    <section>
      <h2 style="color: var(--color-primary-dark); font-size: 1.5rem; margin-bottom: 15px; border-left: 5px solid var(--color-secondary); padding-left: 15px;">
        <?php echo esc_html( get_multilang_text('来場案内', 'Visitor Information') ); ?>
      </h2>
      <p style="line-height: 1.7;">
        <?php echo esc_html( get_multilang_text(
          '各スタジアムの開場時間、フードエリア、障がい者等優先観戦エリアについて詳しくご案内いたします。',
          'Details on gates opening times, food trucks, and accessibility-first viewing zones.'
        ) ); ?>
      </p>
    </section>
  </div>
</main>

<?php
get_footer();
