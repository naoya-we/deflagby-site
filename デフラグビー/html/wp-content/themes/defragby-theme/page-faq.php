<?php
/**
 * Template Name: FAQ
 */
get_header();
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
$t = $current_lang === 'en';

$faq_list = function_exists('defragby_get_faq_data') ? defragby_get_faq_data() : array();
?>

<main id="primary" class="site-main container" style="padding-top: 20px;">
  <header class="page-header page-header--faq" style="margin-bottom: 40px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('よくある質問（FAQ）', 'Frequently Asked Questions') ); ?></h1>
  </header>

  <div class="faq-container" style="max-width: 800px; margin: 0 auto 60px; display: flex; flex-direction: column; gap: 20px;">
    <?php if ( ! empty( $faq_list ) ) : ?>
      <?php foreach ( $faq_list as $faq ) :
        $q_text = $t ? ( !empty($faq['q_en']) ? $faq['q_en'] : $faq['q_ja'] ) : $faq['q_ja'];
        $a_text = $t ? ( !empty($faq['a_en']) ? $faq['a_en'] : $faq['a_ja'] ) : $faq['a_ja'];
      ?>
      <div style="background: var(--color-surface); padding: 25px; border-radius: 8px; border: 1px solid var(--color-border); box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <h3 style="font-size: 1.15rem; color: var(--color-primary); display: flex; gap: 10px; margin-bottom: 12px; align-items: flex-start;">
          <span style="font-family: var(--font-en); font-weight: 700; color: #B80815;">Q.</span>
          <span><?php echo esc_html( $q_text ); ?></span>
        </h3>
        <p style="font-size: 0.95rem; line-height: 1.7; padding-left: 25px; color: var(--color-text-muted); margin: 0; white-space: pre-line;">
          <?php echo esc_html( $a_text ); ?>
        </p>
      </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p style="text-align: center; color: var(--color-text-muted);"><?php echo esc_html( get_multilang_text('現在FAQ項目は登録されていません。', 'No FAQ items found.') ); ?></p>
    <?php endif; ?>
  </div>
</main>

<?php
get_footer();
