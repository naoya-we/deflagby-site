<?php
/**
 * Template Name: How to Enjoy - Support
 */
get_header();
?>

<main id="primary" class="site-main container">
  <header class="page-header page-header--support" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('応援する（手話応援コンテンツ）', 'Support the Teams') ); ?></h1>
  </header>

  <div class="content-container" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border); display: flex; flex-direction: column; gap: 40px;">
    
    <section>
      <h2 style="color: var(--color-primary-dark); font-size: 1.5rem; margin-bottom: 15px; border-left: 5px solid var(--color-accent); padding-left: 15px;">
        <?php echo esc_html( get_multilang_text('手話応援コンテンツについて', 'Sign Language Cheer Guide') ); ?>
      </h2>
      <p style="line-height: 1.7;">
        <?php echo esc_html( get_multilang_text(
          '手話を使って、耳の聞こえないラグビー選手たちにピッチへ声援（視覚的声援）を届けましょう！基本的な「トライ！」「がんばれ！」などの応援手話を解説しています。',
          'Learn basic signs to show visual support! "Try!", "Go!", and other phrases can be expressed with simple hand movements.'
        ) ); ?>
      </p>
    </section>

    <section style="background-color: var(--color-bg); padding: 25px; border-radius: 6px;">
      <h3 style="font-weight: 700; margin-bottom: 15px;"><?php echo esc_html( get_multilang_text('応援用手話動画解説 (プレースホルダー)', 'Sign Language Video Tutorial') ); ?></h3>
      <div style="background-color: #E2E8F0; width: 100%; height: 350px; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: 2px dashed #CBD5E1;">
        <span style="font-family: var(--font-en); font-weight: 700; color: var(--color-text-muted); font-size: 1.1rem;"><?php echo esc_html( get_multilang_text('手話動画解説 - VIDEO AREA', 'Cheering Tutorial Video Block') ); ?></span>
      </div>
    </section>
  </div>
</main>

<?php
get_footer();
