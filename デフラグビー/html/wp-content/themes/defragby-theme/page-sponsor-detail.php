<?php
/**
 * Template Name: Sponsor Detail
 */
get_header();
?>

<main id="primary" class="site-main container">
  <header class="page-header" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('スポンサー詳細', 'Sponsor Profile') ); ?></h1>
  </header>

  <div class="content-container" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border);">
    <h2 style="color: var(--color-primary-dark); font-size: 1.6rem; margin-bottom: 20px;">
      <?php echo esc_html( get_multilang_text('三機工業株式会社 (ゴールドパートナー)', 'SANKI ENGINEERING CO., LTD. (Gold Partner)') ); ?>
    </h2>
    <p style="line-height: 1.8; margin-bottom: 25px;">
      <?php echo esc_html( get_multilang_text(
        '三機工業株式会社様は、本大会のゴールドパートナーとしてデフラグビー日本代表、および本大会の開催運営を強力にサポートしてくださっています。',
        'SANKI ENGINEERING CO., LTD. supports the 3rd World Cup as our core Gold Sponsor, empowering the national team and development programs.'
      ) ); ?>
    </p>
    <div style="background-color: var(--color-bg); padding: 25px; border-radius: 6px; border-left: 4px solid var(--color-gold);">
      <h4 style="font-weight: 700; margin-bottom: 10px; color: var(--color-primary-dark);"><?php echo esc_html( get_multilang_text('企業からのコメント', 'Sponsor Message') ); ?></h4>
      <p style="font-style: italic; font-size: 0.95rem; line-height: 1.7;">
        <?php echo esc_html( get_multilang_text(
          '「ラグビーを通じた平等の実現という大会趣旨に賛同し、選手のみなさんが全力を尽くせる環境作りを全力で応援いたします。」',
          '"We are proud to stand behind the World Cup vision of equal rugby, assisting the players to compete with their full energy on the grand stage."'
        ) ); ?>
      </p>
    </div>
  </div>
</main>

<?php
get_footer();
