<?php
/**
 * Template Name: Contact Form
 */
get_header();
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
$t = $current_lang === 'en';
?>

<main id="primary" class="site-main container" style="padding-top: 40px; padding-bottom: 60px;">
  <header class="page-header page-header--contact" style="margin-bottom: 40px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text('お問い合わせ', 'Contact Us') ); ?></h1>
  </header>

  <div class="form-container" style="max-width: 720px; margin: 0 auto 60px;">
    <p style="text-align: center; margin-bottom: 36px; color: var(--color-text-muted); font-size: 0.95rem; line-height: 1.8;">
      <?php echo esc_html( get_multilang_text(
        '大会に関するご質問、取材のお申し込み、協賛・サポーター加盟などについてのお問い合わせはこちらから受け付けております。',
        'For general questions, media requests, or sponsorship inquiries, please use the contact form below.'
      ) ); ?>
    </p>

    <?php
    // FormNest ショートコードの実行 (仮ID: 1)
    $formnest_output = do_shortcode( '[wp_formnest id="1"]' );

    // FormNest フォームが正常に出力された場合はそれを表示し、未作成の場合はプレビュー表示
    $is_valid_formnest = ! empty( trim( str_replace( array('[wp_formnest id="1"]', 'Form not found', 'フォームが見つかりません'), '', $formnest_output ) ) ) && strpos( $formnest_output, 'wp_formnest' ) === false;

    if ( $is_valid_formnest ) :
      echo $formnest_output;
    else :
    ?>
      <!-- FormNest フォーム (ID:1) 未登録時のプレビュー/フォールバック表示 -->
      <form class="contact-form" onsubmit="event.preventDefault(); alert('<?php echo esc_js( get_multilang_text('※現在WordPress管理画面でFormNestフォーム(ID:1)が登録されていません。FormNestメニューよりフォームを作成してください。', 'FormNest form ID:1 is not yet created in WP Admin.') ); ?>');">
        <div class="form-group">
          <label class="form-label"><?php echo esc_html( get_multilang_text('お問い合わせ種類', 'Inquiry Type') ); ?> <span class="badge-required"><?php echo $t ? 'Required' : '必須'; ?></span></label>
          <select class="form-control" required>
            <option value="general"><?php echo esc_html( get_multilang_text('大会についてのご質問', 'General Questions') ); ?></option>
            <option value="press"><?php echo esc_html( get_multilang_text('プレス・取材のご依頼', 'Press & Media Inquiry') ); ?></option>
            <option value="sponsor"><?php echo esc_html( get_multilang_text('協賛・サポーターについて', 'Sponsorship Inquiries') ); ?></option>
            <option value="other"><?php echo esc_html( get_multilang_text('その他のお問い合わせ', 'Other Inquiries') ); ?></option>
          </select>
        </div>

        <div class="form-row-grid">
          <div class="form-group">
            <label class="form-label"><?php echo esc_html( get_multilang_text('お名前', 'Full Name') ); ?> <span class="badge-required"><?php echo $t ? 'Required' : '必須'; ?></span></label>
            <input type="text" class="form-control" placeholder="山田 太郎" required>
          </div>
          <div class="form-group">
            <label class="form-label"><?php echo esc_html( get_multilang_text('会社名・団体名', 'Company / Organization') ); ?> <span class="badge-optional"><?php echo $t ? 'Optional' : '任意'; ?></span></label>
            <input type="text" class="form-control" placeholder="<?php echo esc_attr( get_multilang_text('株式会社〇〇', 'Example Inc.') ); ?>">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label"><?php echo esc_html( get_multilang_text('メールアドレス', 'Email Address') ); ?> <span class="badge-required"><?php echo $t ? 'Required' : '必須'; ?></span></label>
          <input type="email" class="form-control" placeholder="yamada@example.com" required>
        </div>

        <div class="form-group">
          <label class="form-label"><?php echo esc_html( get_multilang_text('お問い合わせ内容', 'Message') ); ?> <span class="badge-required"><?php echo $t ? 'Required' : '必須'; ?></span></label>
          <textarea class="form-control" rows="6" placeholder="<?php echo esc_attr( get_multilang_text('お問い合わせ内容を詳しくご記入ください。', 'Enter your inquiry details...') ); ?>" required></textarea>
        </div>

        <div style="text-align: center; margin-top: 36px;">
          <button type="submit" class="fp-btn fp-btn--primary" style="padding: 14px 48px; font-size: 1rem; border-radius: 6px; cursor: pointer;">
            <i class="fa-solid fa-paper-plane"></i> <?php echo esc_html( get_multilang_text('送信する', 'Send Message') ); ?>
          </button>
        </div>
      </form>
    <?php endif; ?>
  </div>
</main>

<?php
get_footer();
