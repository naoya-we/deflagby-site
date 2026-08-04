<?php
/**
 * Template Name: How to Enjoy - Volunteer
 */
wp_redirect( home_url( '/how-to-enjoy/sponsor/' ), 301 );
exit;
?>

<main id="primary" class="site-main container">
  <header class="page-header page-header--volunteer" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('支える（ボランティア）', 'Volunteer') ); ?></h1>
  </header>

  <div class="content-container" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border);">
    <h2 style="color: var(--color-primary-dark); font-size: 1.6rem; margin-bottom: 20px; border-left: 5px solid var(--color-primary); padding-left: 15px;">
      <?php echo esc_html( get_multilang_text('ボランティアスタッフ活動紹介', 'Meet Our Volunteers') ); ?>
    </h2>
    <p style="line-height: 1.8; margin-bottom: 20px;">
      <?php echo esc_html( get_multilang_text(
        '通訳、会場整理、救護、受付など多岐にわたる部門で大会をサポートするボランティアの皆様の活躍をご紹介します。',
        'Volunteers are the backbone of this tournament, assisting in interpretation, venue management, reception, and first aid.'
      ) ); ?>
    </p>
    <div style="background: var(--color-bg); padding: 25px; border-radius: 6px; border-left: 4px solid var(--color-gold);">
      <h4 style="font-weight: 700; margin-bottom: 10px; color: var(--color-primary-dark);"><?php echo esc_html( get_multilang_text('ボランティア募集状況について', 'Recruitment Status') ); ?></h4>
      <p style="font-size: 0.95rem;">
        <?php echo esc_html( get_multilang_text(
          '現在は事前登録のみ受け付けております。募集部門や日程の詳細は決まり次第、別途お知らせいたします。',
          'We currently accept pre-registrations only. Final schedules and specific role assignments will be notified later.'
        ) ); ?>
      </p>
    </div>
  </div>
</main>

<?php
get_footer();
