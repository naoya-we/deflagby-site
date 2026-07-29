<?php
/**
 * Template Name: Team Detail
 */
get_header();
?>

<style>
/* ── Nations Section ── */
.nations-section {
  padding: 70px 20px;
  background: var(--color-bg);
}
.nations-inner {
  max-width: 1100px;
  margin: 0 auto;
}
.section-header-row {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 44px;
}
.section-header-row h2 {
  font-family: var(--font-en);
  color: var(--color-primary-dark);
  font-size: 1.5rem;
  letter-spacing: 2px;
  white-space: nowrap;
}
.section-header-row .sec-line {
  flex: 1;
  height: 2px;
  background: linear-gradient(to right, var(--color-primary), transparent);
}

/* Nations card grid */
.nations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
  gap: 24px;
}
.nation-card {
  background: var(--color-surface);
  border-radius: 12px;
  overflow: hidden;
  border: 1px solid var(--color-border);
  box-shadow: 0 2px 10px rgba(0,48,135,0.06);
  transition: transform 0.28s ease, box-shadow 0.28s ease;
  cursor: default;
}
.nation-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 32px rgba(0,48,135,0.13);
}
.nation-card:hover .nation-ph-icon {
  opacity: 0.45;
  transform: scale(1.06);
}

/* Placeholder for flag */
.nation-flag-ph {
  position: relative;
  width: 100%;
  padding-top: 60%;
  overflow: hidden;
}
.nation-flag-ph-inner {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
}
.nation-ph-icon {
  opacity: 0.3;
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.nation-ph-text {
  font-size: 0.68rem;
  color: var(--color-text-muted);
  font-family: var(--font-ja);
  text-align: center;
  line-height: 1.5;
  padding: 0 12px;
}
.nation-body {
  padding: 14px 16px 18px;
  border-top: 1px solid var(--color-border);
}
.nation-rank {
  font-family: var(--font-en);
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 2px;
  color: var(--color-secondary);
  text-transform: uppercase;
  margin-bottom: 4px;
}
.nation-name-ja {
  font-family: var(--font-ja);
  font-size: 1.05rem;
  font-weight: 700;
  color: var(--color-text);
  margin-bottom: 2px;
}
.nation-name-en {
  font-family: var(--font-en);
  font-size: 0.72rem;
  color: var(--color-text-muted);
  letter-spacing: 1px;
  text-transform: uppercase;
  margin-bottom: 8px;
}
.nation-tag {
  display: inline-block;
  background: var(--color-primary);
  color: #fff;
  font-family: var(--font-en);
  font-size: 0.6rem;
  font-weight: 700;
  letter-spacing: 1px;
  padding: 3px 8px;
  border-radius: 3px;
}
.nation-tag.host { background: var(--color-secondary); }
.nation-tag.champ { background: var(--color-gold); color: var(--color-primary-dark); }

/* ── Spotlight Players Section ── */
.players-section {
  padding: 70px 20px 90px;
  background: var(--color-surface);
}
.players-inner {
  max-width: 1100px;
  margin: 0 auto;
}
.player-cards {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
  gap: 32px;
}
.player-card {
  background: var(--color-bg);
  border-radius: 14px;
  overflow: hidden;
  border: 1px solid var(--color-border);
  box-shadow: 0 2px 10px rgba(0,48,135,0.05);
  display: flex;
  flex-direction: column;
  transition: transform 0.28s ease, box-shadow 0.28s ease;
}
.player-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 16px 40px rgba(0,48,135,0.12);
}
.player-card:hover .player-ph-icon {
  opacity: 0.4;
  transform: scale(1.06);
}

.player-photo-ph {
  position: relative;
  width: 100%;
  padding-top: 72%;
  background: linear-gradient(145deg, #dde3f0 0%, #c8d2e8 100%);
  overflow: hidden;
}
.player-photo-ph-inner {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
}
.player-ph-icon {
  opacity: 0.28;
  transition: opacity 0.3s ease, transform 0.3s ease;
}
.player-photo-ph-label {
  font-size: 0.7rem;
  color: var(--color-text-muted);
  font-family: var(--font-ja);
  text-align: center;
  padding: 0 16px;
  line-height: 1.5;
}
.player-number {
  position: absolute;
  bottom: 12px;
  right: 14px;
  font-family: var(--font-en);
  font-size: 3rem;
  font-weight: 700;
  color: rgba(255,255,255,0.18);
  line-height: 1;
  user-select: none;
}
.player-nation-chip {
  position: absolute;
  top: 12px;
  left: 12px;
  background: rgba(0,34,96,0.72);
  backdrop-filter: blur(6px);
  color: #fff;
  font-family: var(--font-en);
  font-size: 0.62rem;
  font-weight: 700;
  letter-spacing: 1.5px;
  padding: 4px 10px;
  border-radius: 3px;
}

.player-body {
  padding: 18px 20px 22px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.player-position {
  font-family: var(--font-en);
  font-size: 0.65rem;
  font-weight: 700;
  letter-spacing: 2.5px;
  color: var(--color-secondary);
  text-transform: uppercase;
  margin-bottom: 6px;
}
.player-name {
  font-family: var(--font-ja);
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-primary-dark);
  margin-bottom: 2px;
}
.player-name-en {
  font-family: var(--font-en);
  font-size: 0.8rem;
  color: var(--color-text-muted);
  letter-spacing: 1px;
  margin-bottom: 12px;
}
.player-stats {
  display: flex;
  gap: 20px;
  padding: 12px 0;
  border-top: 1px solid var(--color-border);
  border-bottom: 1px solid var(--color-border);
  margin-bottom: 12px;
}
.player-stat-item {
  text-align: center;
}
.player-stat-val {
  font-family: var(--font-en);
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-primary-dark);
}
.player-stat-key {
  font-size: 0.65rem;
  color: var(--color-text-muted);
  font-family: var(--font-en);
  letter-spacing: 1px;
}
.player-bio {
  font-size: 0.82rem;
  color: var(--color-text-muted);
  line-height: 1.65;
  flex: 1;
}

@media (max-width: 640px) {
  .nations-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
  .player-cards { grid-template-columns: 1fr; }
  .teams-stats-bar { gap: 28px; }
}
</style>

<?php
// Helper: SVG flag placeholder icon
function flag_placeholder_svg($w = 56, $h = 38, $color = '#a0aec0') {
  echo '<svg class="nation-ph-icon" width="' . $w . '" height="' . $h . '" viewBox="0 0 56 38" fill="none" xmlns="http://www.w3.org/2000/svg">'
    . '<rect x="2" y="2" width="52" height="34" rx="3" stroke="' . $color . '" stroke-width="2" fill="none"/>'
    . '<line x1="2" y1="14.5" x2="54" y2="14.5" stroke="' . $color . '" stroke-width="1.5"/>'
    . '<line x1="2" y1="23.5" x2="54" y2="23.5" stroke="' . $color . '" stroke-width="1.5"/>'
    . '</svg>';
}

// Helper: SVG person placeholder icon
function player_placeholder_svg($size = 60, $color = '#8fa3c8') {
  echo '<svg class="player-ph-icon" width="' . $size . '" height="' . $size . '" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">'
    . '<circle cx="32" cy="20" r="12" stroke="' . $color . '" stroke-width="2.5" fill="none"/>'
    . '<path d="M8 58c0-13.25 10.745-24 24-24s24 10.75 24 24" stroke="' . $color . '" stroke-width="2.5" stroke-linecap="round" fill="none"/>'
    . '</svg>';
}

$nations = get_option( 'defragby_teams_data', array() );
if ( empty( $nations ) ) {
  $nations = [
    ['name_ja' => '日本', 'name_en' => 'Japan', 'image_url' => '', 'info_url' => 'https://www.youtube.com/channel/UCehQeAXq6JyRzAgi4XLv7gQ'],
    ['name_ja' => 'ニュージーランド', 'name_en' => 'New Zealand', 'image_url' => '', 'info_url' => ''],
    ['name_ja' => 'オーストラリア', 'name_en' => 'Australia', 'image_url' => '', 'info_url' => ''],
    ['name_ja' => '南アフリカ', 'name_en' => 'South Africa', 'image_url' => '', 'info_url' => ''],
    ['name_ja' => 'ウェールズ', 'name_en' => 'Wales', 'image_url' => '', 'info_url' => ''],
    ['name_ja' => 'イングランド', 'name_en' => 'England', 'image_url' => '', 'info_url' => ''],
    ['name_ja' => 'フランス', 'name_en' => 'France', 'image_url' => '', 'info_url' => ''],
    ['name_ja' => 'フィジー', 'name_en' => 'Fiji', 'image_url' => '', 'info_url' => '']
  ];
}

$players = [
  [
    'nation' => 'JAPAN', 'number' => '7',
    'position' => get_multilang_text('スクラムハーフ', 'Scrum-Half'),
    'name_ja' => get_multilang_text('AAA aaa', 'AAA aaa'),
    'name_en' => 'AAA aaa',
    'caps' => '42', 'tries' => '18',
    'bio' => get_multilang_text(
      'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
      'texttexttexttexttexttexttexttexttexttexttexttexttexttexttexttext.'
    ),
  ],
  [
    'nation' => 'NEW ZEALAND', 'number' => '1',
    'position' => get_multilang_text('プロップ', 'Prop'),
    'name_ja' => get_multilang_text('BBB bbb', 'BBB bbb'),
    'name_en' => 'BBB bbb',
    'caps' => '61', 'tries' => '12',
    'bio' => get_multilang_text(
      'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
      'texttexttexttexttexttexttexttexttexttexttexttexttexttexttexttext.'
    ),
  ],
  [
    'nation' => 'AUSTRALIA', 'number' => '11',
    'position' => get_multilang_text('ウィング', 'Wing'),
    'name_ja' => get_multilang_text('CCC ccc', 'CCC ccc'),
    'name_en' => 'CCC ccc',
    'caps' => '35', 'tries' => '27',
    'bio' => get_multilang_text(
      'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
      'texttexttexttexttexttexttexttexttexttexttexttexttexttexttexttext'
    ),
  ],
];
?>

<!-- Hero (Aligned with Global Subpage Headers) -->
<header class="page-header page-header--team">
  <h1 class="page-title"><?php echo esc_html( get_multilang_text('各国・選手紹介', 'Nations & Players') ); ?></h1>
  <div class="entry-meta"><?php echo esc_html( get_multilang_text('第3回7人制デフラグビー世界大会 出場8カ国', '8 Nations Competing — 3rd World Deaf Rugby Sevens') ); ?></div>
  
  <div class="teams-stats-bar" style="display: flex; justify-content: center; gap: 36px; margin-top: 24px; flex-wrap: wrap;">
    <div class="teams-stat" style="display: inline-flex; align-items: center; gap: 6px;">
      <span style="font-family: var(--font-en); font-size: 1.8rem; font-weight: 900; color: var(--color-gold); line-height: 1;">8</span>
      <span style="font-size: 0.72rem; color: rgba(255,255,255,0.7); letter-spacing: 1px; font-family: var(--font-en);">NATIONS</span>
    </div>
    <div class="teams-stat" style="display: inline-flex; align-items: center; gap: 6px;">
      <span style="font-family: var(--font-en); font-size: 1.8rem; font-weight: 900; color: var(--color-gold); line-height: 1;">112</span>
      <span style="font-size: 0.72rem; color: rgba(255,255,255,0.7); letter-spacing: 1px; font-family: var(--font-en);">PLAYERS</span>
    </div>
    <div class="teams-stat" style="display: inline-flex; align-items: center; gap: 6px;">
      <span style="font-family: var(--font-en); font-size: 1.8rem; font-weight: 900; color: var(--color-gold); line-height: 1;">3</span>
      <span style="font-size: 0.72rem; color: rgba(255,255,255,0.7); letter-spacing: 1px; font-family: var(--font-en);">DAYS</span>
    </div>
    <div class="teams-stat" style="display: inline-flex; align-items: center; gap: 6px;">
      <span style="font-family: var(--font-en); font-weight: 900; font-size: 1.8rem; color: var(--color-gold); line-height: 1;">28</span>
      <span style="font-size: 0.72rem; color: rgba(255,255,255,0.7); letter-spacing: 1px; font-family: var(--font-en);">MATCHES</span>
    </div>
  </div>
</header>

<!-- Nations Grid - Men's -->
<section class="nations-section" id="nations-men">
  <div class="nations-inner">
    <div class="section-header-row">
      <h2><?php echo esc_html( get_multilang_text('出場国一覧（男子）', 'PARTICIPATING NATIONS (MEN\'S)') ); ?></h2>
      <div class="sec-line"></div>
    </div>

    <div class="nations-grid" id="nations-grid-men">
      <?php
      $nations_men = array_filter( $nations, function($n) {
        $g = isset($n['gender']) ? $n['gender'] : 'men';
        return $g === 'men';
      });
      foreach ($nations_men as $nation) :
        $name_ja   = isset($nation['name_ja'])   ? esc_html($nation['name_ja'])   : '';
        $name_en   = isset($nation['name_en'])   ? esc_html($nation['name_en'])   : '';
        $image_url = isset($nation['image_url']) ? esc_url($nation['image_url'])  : '';
        $detail_post_id     = isset($nation['detail_post_id']) ? intval($nation['detail_post_id']) : 0;
        $detail_post_status = ( $detail_post_id > 0 ) ? get_post_status( $detail_post_id ) : false;
        $detail_url = '';
        if ( $detail_post_status === 'publish' ) {
          $detail_url = get_permalink( $detail_post_id );
        } elseif ( $detail_post_status === 'draft' && current_user_can( 'edit_post', $detail_post_id ) ) {
          $detail_url = get_preview_post_link( $detail_post_id );
        }
        $has_image       = !empty($image_url);
        $has_detail_link = !empty($detail_url);
      ?>
      <?php if ($has_detail_link) : ?>
      <a href="<?php echo esc_url($detail_url); ?>" style="text-decoration: none; color: inherit; display: block;">
      <?php endif; ?>
      <article class="nation-card" style="transition: transform 0.3s; <?php echo $has_detail_link ? 'cursor: pointer;' : ''; ?>">
        <div class="nation-flag-ph" style="background: <?php echo $has_image ? "url('{$image_url}') no-repeat center/cover" : "linear-gradient(145deg, #f2f8f9 0%, #e2e8f0 100%)"; ?>; height: 160px; position: relative;">
          <?php if (!$has_image) : ?>
          <div class="nation-flag-ph-inner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <?php flag_placeholder_svg(52, 34, '#9ca3af'); ?>
            <span class="nation-ph-text" style="display: block; font-size: 0.72rem; color: var(--color-text-muted); margin-top: 8px;"><?php echo esc_html( get_multilang_text('国旗・チーム画像未登録', 'No Photo Selected') ); ?></span>
          </div>
          <?php endif; ?>
        </div>
        <div class="nation-body" style="padding: 16px;">
          <div class="nation-name-ja" style="font-family: 'Noto Serif JP', serif; font-size: 1.15rem; font-weight: 900; color: var(--color-navy);"><?php echo $name_ja; ?></div>
          <div class="nation-name-en" style="font-family: var(--font-en); font-size: 0.8rem; color: var(--color-text-muted); text-transform: uppercase; margin-top: 4px;"><?php echo $name_en; ?></div>
        </div>
      </article>
      <?php if ($has_detail_link) : ?>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Nations Grid - Women's -->
<section class="nations-section" id="nations-women">
  <div class="nations-inner">
    <div class="section-header-row">
      <h2><?php echo esc_html( get_multilang_text('出場国一覧（女子）', 'PARTICIPATING NATIONS (WOMEN\'S)') ); ?></h2>
      <div class="sec-line"></div>
    </div>

    <div class="nations-grid" id="nations-grid-women">
      <?php
      $nations_women = array_filter( $nations, function($n) {
        $g = isset($n['gender']) ? $n['gender'] : '';
        return $g === 'women';
      });
      if ( empty($nations_women) ) : ?>
      <p style="color: var(--color-text-muted); padding: 24px 0;"><?php echo esc_html( get_multilang_text('女子チームは現在登録なし。', 'No women\'s teams registered yet.') ); ?></p>
      <?php else : ?>
      <?php foreach ($nations_women as $nation) :
        $name_ja   = isset($nation['name_ja'])   ? esc_html($nation['name_ja'])   : '';
        $name_en   = isset($nation['name_en'])   ? esc_html($nation['name_en'])   : '';
        $image_url = isset($nation['image_url']) ? esc_url($nation['image_url'])  : '';
        $detail_post_id     = isset($nation['detail_post_id']) ? intval($nation['detail_post_id']) : 0;
        $detail_post_status = ( $detail_post_id > 0 ) ? get_post_status( $detail_post_id ) : false;
        $detail_url = '';
        if ( $detail_post_status === 'publish' ) {
          $detail_url = get_permalink( $detail_post_id );
        } elseif ( $detail_post_status === 'draft' && current_user_can( 'edit_post', $detail_post_id ) ) {
          $detail_url = get_preview_post_link( $detail_post_id );
        }
        $has_image       = !empty($image_url);
        $has_detail_link = !empty($detail_url);
      ?>
      <?php if ($has_detail_link) : ?>
      <a href="<?php echo esc_url($detail_url); ?>" style="text-decoration: none; color: inherit; display: block;">
      <?php endif; ?>
      <article class="nation-card" style="transition: transform 0.3s; <?php echo $has_detail_link ? 'cursor: pointer;' : ''; ?>">
        <div class="nation-flag-ph" style="background: <?php echo $has_image ? "url('{$image_url}') no-repeat center/cover" : "linear-gradient(145deg, #f2f8f9 0%, #e2e8f0 100%)"; ?>; height: 160px; position: relative;">
          <?php if (!$has_image) : ?>
          <div class="nation-flag-ph-inner" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
            <?php flag_placeholder_svg(52, 34, '#9ca3af'); ?>
            <span class="nation-ph-text" style="display: block; font-size: 0.72rem; color: var(--color-text-muted); margin-top: 8px;"><?php echo esc_html( get_multilang_text('国旗・チーム画像未登録', 'No Photo Selected') ); ?></span>
          </div>
          <?php endif; ?>
        </div>
        <div class="nation-body" style="padding: 16px;">
          <div class="nation-name-ja" style="font-family: 'Noto Serif JP', serif; font-size: 1.15rem; font-weight: 900; color: var(--color-navy);"><?php echo $name_ja; ?></div>
          <div class="nation-name-en" style="font-family: var(--font-en); font-size: 0.8rem; color: var(--color-text-muted); text-transform: uppercase; margin-top: 4px;"><?php echo $name_en; ?></div>
        </div>
      </article>
      <?php if ($has_detail_link) : ?>
      </a>
      <?php endif; ?>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
get_footer();
