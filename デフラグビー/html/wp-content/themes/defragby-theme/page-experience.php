<?php
/**
 * Template Name: How to Enjoy - Experience
 */
get_header();
?>

<main id="primary" class="site-main container">
  <header class="page-header page-header--experience" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('体験する', 'Experience the Game') ); ?></h1>
  </header>

  <div class="content-container" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border);">
    <h2 style="color: var(--color-primary-dark); font-size: 1.6rem; margin-bottom: 20px; border-left: 5px solid var(--color-accent); padding-left: 15px;">
      <?php echo esc_html( get_multilang_text('聞こえない観戦体験', 'Experience the Game Through Deaf Eyes') ); ?>
    </h2>
    <p style="line-height: 1.8; margin-bottom: 20px;">
      <?php echo esc_html( get_multilang_text(
        '耳が聞こえない、または聞こえにくい選手たちがどのようにピッチ上で意思疎通を図り、ラグビーをプレーしているか、音のない世界での観戦を体験できるブースを用意しています。',
        'We invite you to experience spectator sports through the perspective of deaf players. Visual signals, vibrational tools, and other communications options will be demonstrated.'
      ) ); ?>
    </p>
    <div style="background-color: var(--color-bg); padding: 20px; border-radius: 6px; margin-top: 30px;">
      <h4 style="font-weight: 700; margin-bottom: 10px;"><?php echo esc_html( get_multilang_text('体験ブース詳細', 'Experience Booth Schedule') ); ?></h4>
      <p style="font-size: 0.95rem; color: var(--color-text-muted);">
        <?php echo esc_html( get_multilang_text(
          '大会期間中、各会場の特設テントにて終日開催。事前予約は不要ですので、お気軽にお立ち寄りください。',
          'Open daily at all match venues. No reservation required. Everyone is welcome to visit the booth!'
        ) ); ?>
      </p>
    </div>
  </div>
</main>

<?php
get_footer();
