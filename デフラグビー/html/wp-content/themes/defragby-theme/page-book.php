<?php
/**
 * Template Name: Merchandise Pre-Order Form
 */
get_header();
?>

<style>
/* ── Book Page Styles ───────────────────────────────────────── */
.book-hero {
  background: linear-gradient(135deg, rgba(17, 34, 41, 0.75) 0%, rgba(30, 127, 149, 0.85) 100%),
    url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/page-team-bg.jpg') no-repeat center/cover;
  padding: 64px 20px 72px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.book-hero::before {
  content: '';
  position: absolute;
  top: -40px; right: -60px;
  width: 320px; height: 320px;
  border-radius: 50%;
  background: rgba(235,97,122,0.12);
  pointer-events: none;
}
.book-hero::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0;
  width: 100%; height: 48px;
  background: var(--color-bg);
  clip-path: polygon(0 100%, 100% 100%, 100% 0);
}
.book-hero-eyebrow {
  display: inline-block;
  background: var(--color-primary);
  color: #fff;
  font-family: var(--font-en);
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 3px;
  padding: 5px 16px;
  border-radius: 3px;
  margin-bottom: 16px;
  position: relative; z-index: 1;
}
.book-hero h1 {
  color: #fff;
  font-size: clamp(1.8rem, 4vw, 2.8rem);
  letter-spacing: 1.5px;
  margin-bottom: 8px;
  position: relative; z-index: 1;
}
.book-hero-sub {
  color: rgba(255,255,255,0.7);
  font-size: 0.95rem;
  position: relative; z-index: 1;
}

/* ── Layout: two-column on wide screens ── */
.book-layout {
  max-width: 1000px;
  margin: 60px auto 90px;
  padding: 0 20px;
  display: grid;
  grid-template-columns: 340px 1fr;
  gap: 36px;
  align-items: start;
}
@media (max-width: 760px) {
  .book-layout { grid-template-columns: 1fr; }
}

/* ── Cart Preview Panel ── */
.cart-panel {
  background: var(--color-surface);
  border: 1.5px solid var(--color-border);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  position: sticky;
  top: calc(var(--header-height) + 16px);
}
.cart-panel-header {
  background: linear-gradient(90deg, var(--color-navy-dark), var(--color-navy));
  color: #fff;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.cart-panel-header i { color: var(--color-gold); font-size: 1.1rem; }
.cart-panel-header h2 {
  font-family: var(--font-en);
  font-size: 0.88rem;
  font-weight: 700;
  letter-spacing: 2px;
  color: #fff;
  margin: 0;
}
#cart-preview { padding: 16px 20px 20px; }

/* Cart preview item list */
.cart-preview-list { list-style: none; display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px; }
.cart-preview-item {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  background: var(--color-navy-light);
  border-radius: 8px;
  border: 1px solid var(--color-border);
}
.cpi-name { flex: 1; font-size: 0.88rem; font-weight: 700; color: var(--color-navy); }
.cpi-qty  { font-family: var(--font-en); font-size: 0.82rem; color: var(--color-text-muted); white-space: nowrap; }
.cpi-price { font-family: var(--font-en); font-size: 0.88rem; font-weight: 700; color: var(--color-primary); white-space: nowrap; }
.cpi-remove {
  background: none; border: none; cursor: pointer;
  color: #9ca3af; font-size: 0.9rem;
  padding: 2px 4px; border-radius: 4px;
  transition: color 0.2s, background 0.2s;
  flex-shrink: 0;
}
.cpi-remove:hover { color: var(--color-primary); background: var(--color-primary-light); }

.cart-preview-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 0 0;
  border-top: 2px solid var(--color-border);
  font-size: 0.9rem;
  color: var(--color-text-muted);
}
.cart-preview-total strong {
  font-family: var(--font-en);
  font-size: 1.2rem;
  font-weight: 700;
  color: var(--color-navy);
}
.cart-preview-note { font-size: 0.72rem; color: var(--color-text-light); margin-top: 6px; }

.cart-preview-empty {
  text-align: center;
  padding: 20px 0;
  color: var(--color-text-muted);
}
.cart-preview-empty i { font-size: 2rem; color: var(--color-border-strong); margin-bottom: 10px; display: block; }
.cart-preview-empty a { color: var(--color-primary); }

.cart-panel-goto {
  display: block;
  text-align: center;
  padding: 11px;
  margin: 0 20px 18px;
  background: var(--color-primary-light);
  color: var(--color-primary);
  font-family: var(--font-en);
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 1px;
  border-radius: 8px;
  text-decoration: none;
  transition: background 0.2s, color 0.2s;
}
.cart-panel-goto:hover { background: var(--color-primary); color: #fff; }

/* ── Form Panel ── */
.form-panel {
  background: var(--color-surface);
  border: 1px solid var(--color-border);
  border-radius: 14px;
  padding: 36px 40px;
  box-shadow: var(--shadow-md);
}
@media (max-width: 480px) { .form-panel { padding: 24px 18px; } }

.form-panel-title {
  font-family: var(--font-en);
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 2px;
  color: var(--color-navy);
  margin-bottom: 28px;
  padding-bottom: 12px;
  border-bottom: 2px solid var(--color-primary-light);
}

/* Required mark */
.req { color: var(--color-primary); font-size: 0.78rem; margin-left: 4px; }

.form-group { margin-bottom: 22px; }
.form-group label { display: block; font-weight: 700; font-size: 0.9rem; margin-bottom: 7px; color: var(--color-navy); }
.form-control {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid var(--color-border);
  border-radius: 8px;
  font-family: var(--font-ja);
  font-size: 0.95rem;
  color: var(--color-text);
  transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px rgba(235,97,122,0.14);
}
.form-row { display: flex; gap: 18px; }
.form-row .form-group { flex: 1; }
@media (max-width: 480px) { .form-row { flex-direction: column; gap: 0; } }

/* Submit button */
.book-submit-btn {
  display: flex; align-items: center; justify-content: center; gap: 10px;
  width: 100%;
  padding: 15px 24px;
  background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
  color: #fff;
  font-family: var(--font-en);
  font-size: 1rem;
  font-weight: 700;
  letter-spacing: 1.5px;
  border: none;
  border-radius: 32px;
  cursor: pointer;
  box-shadow: var(--shadow-rose);
  transition: all 0.28s ease;
  margin-top: 8px;
}
.book-submit-btn:hover {
  background: linear-gradient(135deg, var(--color-primary-dark) 0%, #b83d56 100%);
  transform: translateY(-2px);
  box-shadow: 0 10px 32px rgba(235,97,122,0.45);
}

.form-demo-note {
  text-align: center;
  font-size: 0.75rem;
  color: var(--color-text-light);
  margin-top: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
}
</style>

<!-- Hero -->
<section class="book-hero">
  <span class="book-hero-eyebrow">PRE-ORDER FORM</span>
  <h1><?php echo esc_html( get_multilang_text('グッズ予約フォーム', 'Goods Pre-Order Form') ); ?></h1>
  <p class="book-hero-sub"><?php echo esc_html( get_multilang_text('ご希望の商品をカートに入れてからご予約ください', 'Add items to cart on the goods page, then complete your order here') ); ?></p>
</section>

<!-- Two-column layout -->
<div class="book-layout">

  <!-- LEFT: Cart Preview Panel -->
  <aside class="cart-panel" aria-label="<?php echo esc_attr( get_multilang_text('カートの中身', 'Cart Contents') ); ?>">
    <div class="cart-panel-header">
      <i class="fa-solid fa-cart-shopping"></i>
      <h2><?php echo esc_html( get_multilang_text('選択中のアイテム', 'SELECTED ITEMS') ); ?></h2>
    </div>
    <!-- JS will populate this -->
    <div id="cart-preview">
      <div class="cart-preview-empty">
        <i class="fa-solid fa-cart-shopping"></i>
        <p><?php echo esc_html( get_multilang_text('カートに商品がありません', 'No items in cart yet') ); ?></p>
      </div>
    </div>
    <a href="<?php echo esc_url( home_url('/goods/') ); ?>" class="cart-panel-goto">
      <i class="fa-solid fa-plus"></i>
      <?php echo esc_html( get_multilang_text('グッズページで商品を追加', 'Add items from Goods page') ); ?>
    </a>
  </aside>

  <!-- RIGHT: Order Form -->
  <div class="form-panel">
    <p class="form-panel-title"><?php echo esc_html( get_multilang_text('予約者情報の入力', 'YOUR DETAILS') ); ?></p>

    <form id="book-form" novalidate>

      <div class="form-row">
        <div class="form-group">
          <label for="book-name"><?php echo esc_html( get_multilang_text('お名前', 'Full Name') ); ?><span class="req">*</span></label>
          <input type="text" id="book-name" name="name" class="form-control"
            placeholder="<?php echo esc_attr( get_multilang_text('山田 太郎', 'Taro Yamada') ); ?>" required>
        </div>
        <div class="form-group">
          <label for="book-name-kana"><?php echo esc_html( get_multilang_text('フリガナ', 'Reading (Kana)') ); ?></label>
          <input type="text" id="book-name-kana" name="name_kana" class="form-control"
            placeholder="<?php echo esc_attr( get_multilang_text('ヤマダ タロウ', 'ヤマダ タロウ') ); ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="book-email"><?php echo esc_html( get_multilang_text('メールアドレス', 'Email Address') ); ?><span class="req">*</span></label>
        <input type="email" id="book-email" name="email" class="form-control"
          placeholder="yamada@example.com" required>
      </div>

      <div class="form-group">
        <label for="book-phone"><?php echo esc_html( get_multilang_text('電話番号', 'Phone Number') ); ?></label>
        <input type="tel" id="book-phone" name="phone" class="form-control"
          placeholder="090-0000-0000">
      </div>

      <div class="form-group">
        <label for="book-pickup"><?php echo esc_html( get_multilang_text('お受取予定日', 'Preferred Pickup Date') ); ?><span class="req">*</span></label>
        <select id="book-pickup" name="pickup_date" class="form-control" required>
          <option value=""><?php echo esc_html( get_multilang_text('日付を選択してください', 'Select a date') ); ?></option>
          <option value="oct31">10/31 (Sat) — 夢の島競技場 / Yumenoshima</option>
          <option value="nov01">11/01 (Sun) — 江戸川区陸上競技場 / Edogawa</option>
          <option value="nov02">11/02 (Mon) — 秩父宮ラグビー場 / Chichibunomiya</option>
          <option value="nov03">11/03 (Tue) — 秩父宮ラグビー場 / Chichibunomiya</option>
        </select>
      </div>

      <div class="form-group">
        <label for="book-note"><?php echo esc_html( get_multilang_text('備考・サイズ希望など', 'Notes / Size Preferences') ); ?></label>
        <textarea id="book-note" name="note" class="form-control" rows="3"
          placeholder="<?php echo esc_attr( get_multilang_text('Tシャツ Lサイズ希望、など', 'e.g. T-shirt size L') ); ?>"></textarea>
      </div>

      <button type="submit" class="book-submit-btn" id="book-submit">
        <i class="fa-solid fa-paper-plane"></i>
        <?php echo esc_html( get_multilang_text('予約を確定する（デモ）', 'Confirm Pre-Order (Demo)') ); ?>
      </button>
      <p class="form-demo-note">
        <i class="fa-solid fa-lock"></i>
        <?php echo esc_html( get_multilang_text('これはデモフォームです。実際の通信・決済は行われません。', 'Demo form only — no data is transmitted or stored.') ); ?>
      </p>
    </form>
  </div>
</div>

<?php get_footer(); ?>
