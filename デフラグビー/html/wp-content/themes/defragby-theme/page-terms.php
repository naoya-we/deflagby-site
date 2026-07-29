<?php
/**
 * Template Name: Legal Notice / Terms
 */
get_header();
?>

<main id="primary" class="site-main container">
  <header class="page-header" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('特定商取引法に基づく表記', 'Legal Notice / Terms of Trade') ); ?></h1>
  </header>

  <div class="content-container" style="max-width: 800px; margin: 0 auto; background: var(--color-surface); padding: 40px; border-radius: 8px; border: 1px solid var(--color-border);">
    <table style="width: 100%; border-collapse: collapse; line-height: 1.8;">
      <tr>
        <th style="padding: 15px 10px; border-bottom: 1px solid var(--color-border); text-align: left; width: 30%; font-weight: 700;"><?php echo esc_html( get_multilang_text('販売業者', 'Distributor') ); ?></th>
        <td style="padding: 15px 10px; border-bottom: 1px solid var(--color-border);"><?php echo esc_html( get_multilang_text('日本聴覚障がい者ラグビーフットボール連盟', 'Japan Deaf Rugby Football Union') ); ?></td>
      </tr>
      <tr>
        <th style="padding: 15px 10px; border-bottom: 1px solid var(--color-border); text-align: left; font-weight: 700;"><?php echo esc_html( get_multilang_text('販売責任者', 'Representative') ); ?></th>
        <td style="padding: 15px 10px; border-bottom: 1px solid var(--color-border);"><?php echo esc_html( get_multilang_text('デフ ラグビー 太郎', 'Taro Deaf Rugby') ); ?></td>
      </tr>
      <tr>
        <th style="padding: 15px 10px; border-bottom: 1px solid var(--color-border); text-align: left; font-weight: 700;"><?php echo esc_html( get_multilang_text('支払方法', 'Payment Methods') ); ?></th>
        <td style="padding: 15px 10px; border-bottom: 1px solid var(--color-border);"><?php echo esc_html( get_multilang_text('現地会場受取時での現金決済・電子マネー決済', 'Cash or Mobile E-Money at match venues') ); ?></td>
      </tr>
    </table>
  </div>
</main>

<?php
get_footer();
