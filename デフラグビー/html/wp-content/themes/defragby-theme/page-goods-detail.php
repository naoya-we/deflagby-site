<?php
/**
 * Template Name: Merchandise Detail
 */
get_header();
?>

<style>
/* ── Goods Page Styles ──────────────────────────────────────── */
.goods-hero {
  background: linear-gradient(135deg, rgba(17, 34, 41, 0.75) 0%, rgba(40, 67, 105, 0.85) 100%),
    url('<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/assets/images/page-goods-bg.jpg') no-repeat center/cover;
  padding: 70px 20px 80px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
/* PC のみ：背景画像を上寄りに（顔が見えるように） */
@media (min-width: 768px) {
  .goods-hero {
    background-position: center 20% !important;
  }
}
.goods-hero::before {
  content: '';
  position: absolute;
  top: -40px; right: -80px;
  width: 400px; height: 400px;
  border-radius: 50%;
  background: rgba(235,97,122,0.13);
  pointer-events: none;
}
.goods-hero::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0;
  width: 100%; height: 48px;
  background: var(--color-bg);
  clip-path: polygon(0 100%, 100% 100%, 100% 0);
}
.goods-hero-eyebrow {
  display: inline-block;
  background: var(--color-primary);
  color: #fff;
  font-family: var(--font-en);
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 3px;
  text-transform: uppercase;
  padding: 5px 16px;
  border-radius: 3px;
  margin-bottom: 18px;
  position: relative; z-index: 1;
}
.goods-hero h1 {
  color: #fff;
  font-size: clamp(2rem, 5vw, 3.2rem);
  letter-spacing: 2px;
  margin-bottom: 10px;
  position: relative; z-index: 1;
}
.goods-hero-sub {
  color: rgba(255,255,255,0.7);
  font-size: 1rem;
  font-family: var(--font-ja);
  position: relative; z-index: 1;
}

/* Grid */
.goods-section { padding: 70px 20px 90px; background: var(--color-bg); }
.goods-section-inner { max-width: 1100px; margin: 0 auto; }
.goods-section-header { display: flex; align-items: center; gap: 16px; margin-bottom: 44px; }
.goods-section-header .section-line { flex: 1; height: 2px; background: linear-gradient(to right, var(--color-primary), transparent); }
.goods-section-header h2 { font-family: var(--font-en); color: var(--color-navy); font-size: 1.5rem; letter-spacing: 2px; white-space: nowrap; }

.goods-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 32px;
}

/* Card */
.goods-card {
  background: var(--color-surface);
  border-radius: 14px;
  overflow: hidden;
  box-shadow: var(--shadow-md);
  border: 1px solid var(--color-border);
  display: flex;
  flex-direction: column;
  transition: transform 0.28s ease, box-shadow 0.28s ease;
}
.goods-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); }

/* Placeholder image area */
.goods-placeholder {
  position: relative;
  width: 100%;
  padding-top: 80%;
  background: linear-gradient(145deg, var(--color-navy-light) 0%, #dce5f0 100%);
  overflow: hidden;
}
.goods-placeholder-inner {
  position: absolute; inset: 0;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  gap: 10px;
}
.goods-placeholder-icon { opacity: 0.3; transition: opacity 0.3s, transform 0.3s; }
.goods-card:hover .goods-placeholder-icon { opacity: 0.5; transform: scale(1.06); }
.goods-placeholder-label { font-family: var(--font-ja); font-size: 0.72rem; color: var(--color-text-muted); text-align: center; padding: 0 16px; line-height: 1.5; }

.goods-badge {
  position: absolute; top: 12px; left: 12px;
  font-family: var(--font-en); font-size: 0.62rem; font-weight: 700;
  letter-spacing: 1.5px; padding: 4px 10px; border-radius: 4px; color: #fff;
}
.goods-badge.preorder { background: var(--color-primary); }
.goods-badge.limited  { background: var(--color-gold); color: var(--color-navy); }
.goods-badge.sold-out { background: #9ca3af; }

/* Card body */
.goods-card-body { padding: 20px 20px 22px; display: flex; flex-direction: column; flex: 1; gap: 6px; }
.goods-category { font-family: var(--font-en); font-size: 0.65rem; font-weight: 700; letter-spacing: 2px; color: var(--color-primary); text-transform: uppercase; }
.goods-name { font-family: var(--font-ja); font-size: 1rem; font-weight: 700; color: var(--color-text); line-height: 1.45; }
.goods-desc { font-size: 0.82rem; color: var(--color-text-muted); line-height: 1.65; flex: 1; margin: 4px 0 8px; }

/* Qty + add row */
.goods-cart-row { display: flex; align-items: center; gap: 10px; padding: 10px 0 4px; border-top: 1px solid var(--color-border); }
.goods-price { font-family: var(--font-en); font-size: 1.35rem; font-weight: 700; color: var(--color-navy); }
.goods-price-tax { font-size: 0.72rem; color: var(--color-text-muted); }
.goods-qty-input {
  width: 58px; padding: 6px 8px; border: 1.5px solid var(--color-border);
  border-radius: 6px; font-size: 0.9rem; text-align: center;
  font-family: var(--font-ja); color: var(--color-navy);
  transition: border-color 0.2s;
}
.goods-qty-input:focus { outline: none; border-color: var(--color-primary); }

.goods-btn {
  flex: 1;
  display: flex; align-items: center; justify-content: center; gap: 6px;
  padding: 10px 12px;
  background: var(--color-navy);
  color: #fff;
  font-family: var(--font-en);
  font-size: 0.78rem;
  font-weight: 700;
  letter-spacing: 1px;
  border-radius: 8px;
  border: none;
  cursor: pointer;
  white-space: nowrap;
  transition: background 0.25s, transform 0.2s, box-shadow 0.2s;
}
.goods-btn:hover:not(:disabled) { background: var(--color-primary); transform: translateY(-1px); box-shadow: var(--shadow-rose); }
.goods-btn--added { background: #22c55e !important; transform: none !important; }
.goods-btn.sold-out-btn { background: #d1d5db; color: #9ca3af; cursor: default; }

/* Info banner */
.goods-info-banner { background: linear-gradient(90deg, var(--color-navy-dark), var(--color-navy)); color: #fff; padding: 48px 20px; }
.goods-info-inner { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 32px; text-align: center; }
.goods-info-item i { font-size: 1.8rem; color: var(--color-gold); margin-bottom: 10px; }
.goods-info-item h3 { font-family: var(--font-en); font-size: 0.8rem; letter-spacing: 2px; color: rgba(255,255,255,0.6); margin-bottom: 6px; }
.goods-info-item p { font-size: 0.9rem; line-height: 1.55; color: rgba(255,255,255,0.88); }

@media (max-width: 600px) {
  .goods-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
  .goods-hero  { padding: 50px 16px 60px; }
}

/* ── Booking CTA ── */
.goods-book-cta {
  text-align: center;
  padding: 44px 20px 10px;
}
.goods-book-btn {
  display: inline-flex;
  align-items: center;
  gap: 12px;
  padding: 16px 44px;
  background: var(--color-navy);
  color: #fff;
  font-family: var(--font-en);
  font-size: 1.05rem;
  font-weight: 700;
  letter-spacing: 1.5px;
  border-radius: 50px;
  text-decoration: none;
  box-shadow: 0 4px 18px rgba(40,67,105,0.22);
  transition: background 0.3s ease, transform 0.25s ease, box-shadow 0.25s ease;
  white-space: nowrap;
}
.goods-book-btn:hover:not(.goods-book-btn--disabled) {
  background: var(--color-primary);
  color: #fff;
  transform: translateY(-3px);
  box-shadow: var(--shadow-rose);
}
/* Disabled state (cart empty) */
.goods-book-btn--disabled {
  background: #d1d5db;
  color: #9ca3af;
  cursor: not-allowed;
  box-shadow: none;
  pointer-events: none;
}
.goods-book-btn i { font-size: 1rem; }
.goods-book-helper {
  margin-top: 10px;
  font-size: 0.82rem;
  color: var(--color-text-muted);
  font-family: var(--font-ja);
}
</style>

<?php
// Helper: SVG placeholder icon
function goods_placeholder_svg($size = 56) {
  echo '<svg class="goods-placeholder-icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">'
    . '<rect x="4" y="10" width="48" height="36" rx="4" stroke="#6b7280" stroke-width="2.5" fill="none"/>'
    . '<circle cx="19" cy="22" r="4" stroke="#6b7280" stroke-width="2" fill="none"/>'
    . '<path d="M4 36 L16 26 L26 34 L36 22 L52 36" stroke="#6b7280" stroke-width="2.2" stroke-linejoin="round" fill="none"/>'
    . '</svg>';
}

$goods_items = [
  [
    'id'       => 'tshirt',
    'category' => get_multilang_text('ウェア', 'APPAREL'),
    'name'     => get_multilang_text('大会公式Tシャツ', 'Official Tournament T-Shirt'),
    'price'    => '3500',
    'price_disp' => '¥3,500',
    'desc'     => get_multilang_text('大会テーマカラー（紺×赤）のドライ素材スポーツTシャツ。手話"がんばれ"グラフィックをバックプリント。', 'Dry-fit sport tee in official navy/red. Limited back-print featuring the "Go for it!" sign language graphic.'),
    'sold_out' => false,
  ],
  [
    'id'       => 'towel',
    'category' => get_multilang_text('タオル', 'TOWEL'),
    'name'     => get_multilang_text('大会公式マフラータオル', 'Official Scarf Towel'),
    'price'    => '2000',
    'price_disp' => '¥2,000',
    'desc'     => get_multilang_text('両面ジャガード織りで大会エンブレムを表現した応援タオル。会場での手話応援にも最適。', 'Double-sided jacquard weave featuring the championship emblem. Perfect for sign-cheer support at the venue.'),
    'sold_out' => false,
  ],
  [
    'id'       => 'sticker',
    'category' => get_multilang_text('ステッカー', 'STICKER'),
    'name'     => get_multilang_text('大会公式ステッカーセット', 'Official Sticker Set'),
    'price'    => '800',
    'price_disp' => '¥800',
    'desc'     => get_multilang_text('各国旗・大会エンブレム・手話イラストをセットにした全10枚入りステッカーパック。', '10-piece sticker pack featuring national flags, championship emblem, and sign language illustrations.'),
    'sold_out' => true,
  ],
];
?>

<!-- Hero -->
<section class="goods-hero">
  <span class="goods-hero-eyebrow">OFFICIAL MERCHANDISE</span>
  <h1><?php echo esc_html( get_multilang_text('大会公式グッズ', 'Official Goods') ); ?></h1>
  <p class="goods-hero-sub"><?php echo esc_html( get_multilang_text('第3回7人制デフラグビー世界大会 限定アイテム', '3rd World Deaf Rugby Sevens — Limited Items') ); ?></p>
</section>

<!-- Grid -->
<section class="goods-section">
  <div class="goods-section-inner">
    <div class="goods-section-header">
      <h2><?php echo esc_html( get_multilang_text('グッズラインナップ', 'LINEUP') ); ?></h2>
      <div class="section-line"></div>
    </div>

    <div class="goods-grid" id="goods-grid">
      <?php foreach ($goods_items as $item) : ?>
      <article
        class="goods-card"
        data-goods-id="<?php echo esc_attr($item['id']); ?>"
        data-goods-name="<?php echo esc_attr($item['name']); ?>"
        data-goods-price="<?php echo esc_attr($item['price']); ?>">

        <div class="goods-placeholder">
          <div class="goods-placeholder-inner">
            <?php goods_placeholder_svg(52); ?>
            <span class="goods-placeholder-label"><?php echo esc_html( get_multilang_text('ここに商品写真が入ります', 'Product photo here') ); ?></span>
          </div>
        </div>

        <div class="goods-card-body">
          <span class="goods-category"><?php echo esc_html($item['category']); ?></span>
          <h3 class="goods-name"><?php echo esc_html($item['name']); ?></h3>
          <p class="goods-desc"><?php echo esc_html($item['desc']); ?></p>

          <div class="goods-cart-row">
            <div class="goods-price-block">
              <span class="goods-price"><?php echo esc_html($item['price_disp']); ?></span>
              <span class="goods-price-tax"><?php echo esc_html( get_multilang_text('税込', 'incl.tax') ); ?></span>
            </div>
          </div>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Booking CTA -->
<div class="goods-book-cta" id="goods-book-cta" style="margin-top: 20px; margin-bottom: 50px;">
  <a
    href="https://forms.gle/Q8KAaZc5k6nuBZwD8"
    class="goods-book-btn"
    id="goods-book-btn"
    target="_blank"
    rel="noopener"
    style="box-shadow: var(--shadow-rose);">
    <i class="fa-solid fa-arrow-up-right-from-square"></i>
    <?php echo esc_html( get_multilang_text('グッズの予約手続きへ進む', 'Proceed to Pre-order') ); ?>
    <i class="fa-solid fa-arrow-right"></i>
  </a>
  <p class="goods-book-helper" id="goods-book-helper" style="margin-top: 14px; font-size: 0.85rem; color: var(--color-text-muted);">
    <?php echo esc_html( get_multilang_text('※クリックすると予約用の外部Googleフォームが開きます。', '*Clicking opens the external Google Forms pre-order sheet.') ); ?>
  </p>
</div>

<!-- Info Banner -->
<section class="goods-info-banner">
  <div class="goods-info-inner">
    <div class="goods-info-item">
      <i class="fa-solid fa-calendar-check"></i>
      <h3><?php echo esc_html( get_multilang_text('予約締切', 'ORDER DEADLINE') ); ?></h3>
      <p><?php echo esc_html( get_multilang_text('2026年8月31日（月）23:59まで', 'Mon. 31 Aug 2026 23:59 JST') ); ?></p>
    </div>
    <div class="goods-info-item">
      <i class="fa-solid fa-truck"></i>
      <h3><?php echo esc_html( get_multilang_text('お渡し方法', 'PICK-UP') ); ?></h3>
      <p><?php echo esc_html( get_multilang_text('大会会場にて引換え（郵送不可）', 'Venue pick-up only (no shipping)') ); ?></p>
    </div>
    <div class="goods-info-item">
      <i class="fa-solid fa-yen-sign"></i>
      <h3><?php echo esc_html( get_multilang_text('お支払い方法', 'PAYMENT') ); ?></h3>
      <p><?php echo esc_html( get_multilang_text('現地にて現金またはQR決済', 'Cash or QR payment at venue') ); ?></p>
    </div>
    <div class="goods-info-item">
      <i class="fa-solid fa-circle-info"></i>
      <h3><?php echo esc_html( get_multilang_text('注意事項', 'NOTICE') ); ?></h3>
      <p><?php echo esc_html( get_multilang_text('数量限定・予約優先での販売', 'Limited quantities; pre-orders prioritized') ); ?></p>
    </div>
  </div>
</section>

<?php get_footer(); ?>
