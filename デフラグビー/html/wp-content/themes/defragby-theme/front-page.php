<?php
/**
 * The template for displaying the front page
 */
get_header();
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
$t = $current_lang === 'en';
?>

<!-- ═══════════════════════════════════════════════════════════
  1. HERO SECTION — Dynamic countdown + CTA
═══════════════════════════════════════════════════════════ -->
<section class="fp-hero" aria-label="<?php echo $t ? 'Hero' : 'メインビジュアル'; ?>">
  <!-- Decorative orbs -->
  <div class="fp-hero__orb fp-hero__orb--1" aria-hidden="true"></div>
  <div class="fp-hero__orb fp-hero__orb--2" aria-hidden="true"></div>
  <!-- 巨大背景タイポグラフィ (FIFA風) -->
  <div class="fp-hero__bg-text" aria-hidden="true">TOKYO 2026</div>

  <div class="fp-hero__inner container">
    <div class="fp-hero__content-wrap">
      <!-- 1. 大会名 (最上部) -->
      <span class="fp-hero__eyebrow">
        <i class="fa-solid fa-rugby-ball"></i>
        <?php echo $t ? '3rd WORLD DEAF RUGBY SEVENS' : '第3回 7人制デフラグビー世界大会'; ?>
      </span>

      <!-- 2. カウントダウンタイマー (2番目) -->
      <div class="fp-countdown" aria-label="<?php echo $t ? 'Countdown timer' : 'カウントダウン'; ?>">
        <p class="fp-countdown__label">
          <?php echo $t ? 'COUNTDOWN TO KICK-OFF' : 'キックオフまであと'; ?>
        </p>
        <div class="fp-countdown__units">
          <div class="fp-countdown__unit">
            <span id="days" class="fp-countdown__num">—</span>
            <span class="fp-countdown__unit-label"><?php echo $t ? 'DAYS' : '日'; ?></span>
          </div>
          <span class="fp-countdown__sep" aria-hidden="true">:</span>
          <div class="fp-countdown__unit">
            <span id="hours" class="fp-countdown__num">—</span>
            <span class="fp-countdown__unit-label"><?php echo $t ? 'HRS' : '時'; ?></span>
          </div>
          <span class="fp-countdown__sep" aria-hidden="true">:</span>
          <div class="fp-countdown__unit">
            <span id="minutes" class="fp-countdown__num">—</span>
            <span class="fp-countdown__unit-label"><?php echo $t ? 'MIN' : '分'; ?></span>
          </div>
          <span class="fp-countdown__sep" aria-hidden="true">:</span>
          <div class="fp-countdown__unit">
            <span id="seconds" class="fp-countdown__num">—</span>
            <span class="fp-countdown__unit-label"><?php echo $t ? 'SEC' : '秒'; ?></span>
          </div>
        </div>
        <div class="fp-countdown__progress" aria-hidden="true">
          <div class="fp-countdown__progress-bar" id="js-progress-bar"></div>
        </div>
      </div>

      <!-- 3. スローガン ＆ 開催日時 (3番目) -->
      <h1 class="fp-hero__title">
        <?php if ($t) : ?>
          Challenges Drive Coexistence.<br><span>From Tokyo to the World.</span>
        <?php else : ?>
          Challenges Drive Coexistence<br>
          <span class="fp-hero__sub--ja">
            <span class="fp-hero__tilde" aria-hidden="true">〜</span>
            <span class="fp-hero__sub-text">
              <span class="fp-hero__line">挑戦が、</span>
              <span class="fp-hero__line">共生を動かす</span>
            </span>
            <span class="fp-hero__tilde" aria-hidden="true">〜</span>
          </span>
        <?php endif; ?>
      </h1>
      <p class="fp-hero__date">
        <?php echo $t ? 'Oct 31 – Nov 3, 2026 · Tokyo, Japan' : '2026年10月31日（土）〜11月3日（火・祝）東京開催'; ?>
      </p>

      <!-- 4. CTAボタン (最下部) -->
      <div class="fp-hero__ctas">
        <a href="<?php echo esc_url(home_url('/information/')); ?>" class="fp-btn fp-btn--primary">
          <?php echo $t ? 'Competition Info' : '競技・会場情報'; ?>
          <i class="fa-solid fa-arrow-right"></i>
        </a>
        <a href="<?php echo esc_url(home_url('/how-to-enjoy/watch/#schedule')); ?>" class="fp-btn fp-btn--ghost">
          <?php echo $t ? 'How to Watch' : '大会日程'; ?>
        </a>
      </div>
    </div>
  </div>

</section>

<main id="primary" class="site-main">

<!-- ═══════════════════════════════════════════════════════════
  CF. CROWDFUNDING BANNER
═══════════════════════════════════════════════════════════ -->
<section class="fp-cf-banner" aria-label="<?php echo $t ? 'Crowdfunding' : 'クラウドファンディング'; ?>">
  <div class="container">
    <a href="https://readyfor.jp/" target="_blank" rel="noopener" class="fp-cf-btn" id="cf-banner-link">
      <span class="fp-cf-btn__text">
        <span class="fp-cf-btn__label"><?php echo $t ? 'NOW ON CROWDFUNDING' : 'クラウドファンディング 実施中'; ?></span>
        <span class="fp-cf-btn__sub"><?php echo $t ? 'Support the 3rd World Deaf Rugby Sevens in Tokyo →' : 'ページをみる・応援する'; ?></span>
      </span>
      <span class="fp-cf-btn__arrow" aria-hidden="true">
        <i class="fa-solid fa-arrow-up-right-from-square"></i>
      </span>
    </a>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
  2. MATCH SCHEDULE & RESULTS
═══════════════════════════════════════════════════════════ -->
<section class="fp-section fp-matches" id="matches" aria-label="<?php echo $t ? 'Match schedule' : '試合日程・結果'; ?>">
  <div class="container">
    <div class="fp-section-head">
      <h2 class="fp-section-title"><?php echo $t ? 'Upcoming Matches' : '直近の試合日程'; ?></h2>
    </div>

    <!-- Horizontal scroll wrapper -->
    <div class="fp-matches__scroll" role="list">
      <?php
      $db_matches = get_option( 'defragby_matches_data', array() );
      $yt_channel = get_option( 'defragby_youtube_channel_url', 'https://www.youtube.com/' );
      $venue_map  = function_exists('defragby_get_venue_map') ? defragby_get_venue_map() : array();

      if ( empty( $db_matches ) ) {
        // デフォルトデータ（フォールバック）
        $db_matches = [
          [ 'venue_date' => '1031', 'time' => '10:00', 'team_a_ja' => '日本', 'team_a_en' => 'JAPAN', 'team_b_ja' => 'ニュージーランド', 'team_b_en' => 'NEW ZEALAND', 'score_a' => '', 'score_b' => '', 'status' => 'upcoming', 'round' => 'group' ],
          [ 'venue_date' => '1031', 'time' => '11:30', 'team_a_ja' => 'オーストラリア', 'team_a_en' => 'AUSTRALIA', 'team_b_ja' => '南アフリカ', 'team_b_en' => 'SOUTH AFRICA', 'score_a' => '', 'score_b' => '', 'status' => 'upcoming', 'round' => 'group' ],
          [ 'venue_date' => '1103', 'time' => '14:00', 'team_a_ja' => '日本', 'team_a_en' => 'JAPAN', 'team_b_ja' => 'フランス', 'team_b_en' => 'FRANCE', 'score_a' => '24', 'score_b' => '19', 'status' => 'finished', 'round' => 'final' ],
        ];
      }

      // 表示優先度: 開催予定 → 試合終了（最近順）
      // 最大3件に絞って表示
      $upcoming_matches = array();
      $finished_matches = array();
      foreach ( $db_matches as $m ) {
        $has_score = ( isset($m['score_a']) && $m['score_a'] !== '' );
        if ( $has_score ) $finished_matches[] = $m;
        else $upcoming_matches[] = $m;
      }
      // 未終了試合優先＋終了試合を合わせて最大3件
      $finished_latest = array_slice( array_reverse($finished_matches), 0, 3 );
      $display_matches = array_merge( $upcoming_matches, $finished_latest );
      $display_matches = array_slice( $display_matches, 0, 3 );

      foreach ($display_matches as $match):
        $team_a_ja   = isset($match['team_a_ja']) ? $match['team_a_ja'] : '';
        $team_a_en   = isset($match['team_a_en']) ? $match['team_a_en'] : '';
        $team_b_ja   = isset($match['team_b_ja']) ? $match['team_b_ja'] : '';
        $team_b_en   = isset($match['team_b_en']) ? $match['team_b_en'] : '';
        $team_a_name = $t ? $team_a_en : $team_a_ja;
        $team_b_name = $t ? $team_b_en : $team_b_ja;
        $team_a_flag = function_exists('defragby_get_team_icon_html') ? defragby_get_team_icon_html($team_a_ja, $team_a_en) : '🏳️';
        $team_b_flag = function_exists('defragby_get_team_icon_html') ? defragby_get_team_icon_html($team_b_ja, $team_b_en) : '🏳️';

        $score_a_val = (isset($match['score_a']) && $match['score_a'] !== '') ? (int)$match['score_a'] : null;
        $score_b_val = (isset($match['score_b']) && $match['score_b'] !== '') ? (int)$match['score_b'] : null;

        // スコア入力の有無で自動判定
        $is_finished  = ($score_a_val !== null && $score_b_val !== null);
        $status_label = $is_finished ? ($t ? 'Full Time' : '試合終了') : ($t ? 'Scheduled' : '開催予定');
        $status_class = $is_finished ? 'result' : 'upcoming';

        // 点数が低い方のスコア数字の色を自動で青(--loser)にする
        $score_a_class = '';
        $score_b_class = '';
        if ($is_finished) {
          if ($score_a_val < $score_b_val) {
            $score_a_class = ' fp-match-score--loser';
          } elseif ($score_b_val < $score_a_val) {
            $score_b_class = ' fp-match-score--loser';
          } else {
            // 引き分け（同点）の場合は両チームとも黒色(#0d121c)
            $score_a_class = ' fp-match-score--draw';
            $score_b_class = ' fp-match-score--draw';
          }
        }

        // 日程・会場の解決
        $vd_key   = isset($match['venue_date']) ? $match['venue_date'] : '';
        $vd_info  = isset($venue_map[$vd_key]) ? $venue_map[$vd_key] : null;
        $date_str = $vd_info ? ($t ? $vd_info['date_en'] : $vd_info['date_ja']) : ( isset($match['date']) ? $match['date'] : '' );
        $venue_str = $vd_info ? ($t ? $vd_info['venue_en'] : $vd_info['venue_ja']) : ($t ? 'Tokyo Venues' : '東京都内競技場');
        $time_str  = isset($match['time']) ? $match['time'] : '';

        // 区分ラベル
        $round_key = isset($match['round']) ? $match['round'] : 'group';
        $round_labels = [ 'group' => ['グループ戦','Group Stage'], 'sf' => ['準決勝','Semi-Final'], 'final' => ['決勝','Final'] ];
        $round_label = isset($round_labels[$round_key]) ? $round_labels[$round_key][$t ? 1 : 0] : ($t ? 'Tournament Match' : 'トーナメントマッチ');
      ?>
      <a href="<?php echo esc_url( home_url('/information/') ); ?>"
         class="fp-match-card"
         role="listitem">
        <div class="fp-match-card__header">
          <span class="fp-match-date"><i class="fa-regular fa-calendar"></i> <?php echo esc_html($date_str); ?></span>
          <?php if ($time_str) : ?><span class="fp-match-time"><i class="fa-regular fa-clock"></i> <?php echo esc_html($time_str); ?></span><?php endif; ?>
          <span class="fp-match-status fp-match-status--<?php echo $status_class; ?>"><?php echo esc_html($status_label); ?></span>
        </div>
        <div class="fp-match-card__stage"><?php echo esc_html($round_label); ?></div>
        <div class="fp-match-card__teams">
          <div class="fp-match-team fp-match-team--a">
            <span class="fp-match-team-name"><?php echo esc_html($team_a_name); ?></span>
            <span class="fp-match-flag"><?php echo $team_a_flag; ?></span>
            <?php if ($score_a_val !== null): ?>
            <span class="fp-match-score<?php echo $score_a_class; ?>"><?php echo esc_html($score_a_val); ?></span>
            <?php endif; ?>
          </div>
          <div class="fp-match-vs">VS</div>
          <div class="fp-match-team fp-match-team--b">
            <span class="fp-match-team-name"><?php echo esc_html($team_b_name); ?></span>
            <span class="fp-match-flag"><?php echo $team_b_flag; ?></span>
            <?php if ($score_b_val !== null): ?>
            <span class="fp-match-score<?php echo $score_b_class; ?>"><?php echo esc_html($score_b_val); ?></span>
            <?php endif; ?>
          </div>
        </div>
        <div class="fp-match-card__venue">
          <i class="fa-solid fa-location-dot"></i>
          <?php echo esc_html($venue_str); ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div><!-- /.fp-matches__scroll -->

    <!-- All schedule CTA -->
    <div class="fp-matches__cta">
      <a href="<?php echo esc_url(home_url('/information/')); ?>" class="fp-btn fp-btn--outline-navy">
        <i class="fa-solid fa-calendar-days"></i>
        <?php echo $t ? 'View Full Schedule & Results' : 'すべての日程・結果を見る'; ?>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
  3. NATIONS INFINITE CAROUSEL
═══════════════════════════════════════════════════════════ -->
<section class="fp-section fp-nations" id="nations" aria-label="<?php echo $t ? 'Participating nations' : '参加国一覧'; ?>">
  <div class="container">
    <div class="fp-section-head">
      <h2 class="fp-section-title"><?php echo $t ? 'Teams from Around the World' : '世界各国のデフラグビー代表チーム'; ?></h2>
    </div>
  </div>

  <!-- Infinite scroll track — overflow hidden container -->
  <div class="fp-nations__track-wrap" aria-label="<?php echo $t ? 'Auto-scrolling nations' : '国旗自動スクロール'; ?>">
    <div class="fp-nations__track" id="nations-track">
      <?php
      // 登録されたチームデータを取得
      $db_teams = get_option( 'defragby_teams_data', array() );
      if ( empty( $db_teams ) ) {
        // フォールバック用のダミーデータ
        $db_teams = [
          ['name_ja' => '日本', 'name_en' => 'Japan', 'image_url' => ''],
          ['name_ja' => 'ニュージーランド', 'name_en' => 'New Zealand', 'image_url' => ''],
          ['name_ja' => 'オーストラリア', 'name_en' => 'Australia', 'image_url' => ''],
          ['name_ja' => '南アフリカ', 'name_en' => 'South Africa', 'image_url' => ''],
          ['name_ja' => 'ウェールズ', 'name_en' => 'Wales', 'image_url' => ''],
          ['name_ja' => 'イングランド', 'name_en' => 'England', 'image_url' => ''],
          ['name_ja' => 'フランス', 'name_en' => 'France', 'image_url' => ''],
          ['name_ja' => 'フィジー', 'name_en' => 'Fiji', 'image_url' => '']
        ];
      }
      
      // シームレスループのために3倍に複製
      $looped_teams = array_merge( $db_teams, $db_teams, $db_teams );
      foreach ($looped_teams as $team):
        $t_ja = isset($team['name_ja']) ? $team['name_ja'] : '';
        $t_en = isset($team['name_en']) ? $team['name_en'] : '';
        $t_name  = $t ? $t_en : $t_ja;
        $img_url = !empty($team['image_url']) ? esc_url($team['image_url']) : '';
        $fallback_flag = function_exists('defragby_get_team_icon_html') ? defragby_get_team_icon_html($t_ja, $t_en) : '🏳️';
      ?>
      <a href="<?php echo esc_url(home_url('/teams/')); ?>"
         class="fp-nation-card"
         aria-label="<?php echo esc_attr($t_name); ?>">
        <?php if (!empty($img_url)) : ?>
          <span class="fp-nation-image" style="display:inline-block; width:80px; height:50px; background:url('<?php echo $img_url; ?>') no-repeat center/cover; border-radius:4px; margin-bottom:12px; box-shadow:0 2px 8px rgba(0,0,0,0.15);"></span>
        <?php else : ?>
          <span class="fp-nation-flag"><?php echo $fallback_flag; ?></span>
        <?php endif; ?>
        <span class="fp-nation-name"><?php echo esc_html($t_name); ?></span>
      </a>
      <?php endforeach; ?>
    </div>
    <!-- Fade edges -->
    <div class="fp-nations__fade fp-nations__fade--left"  aria-hidden="true"></div>
    <div class="fp-nations__fade fp-nations__fade--right" aria-hidden="true"></div>
  </div>

  <div style="text-align:center; margin-top:32px;">
    <a href="<?php echo esc_url(home_url('/teams/')); ?>" class="fp-btn fp-btn--outline">
      <?php echo $t ? 'View All Teams' : '全チームを見る'; ?>
      <i class="fa-solid fa-arrow-right"></i>
    </a>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
  4. YOUTUBE LIVE & ARCHIVE
═══════════════════════════════════════════════════════════ -->
<section class="fp-section fp-youtube" id="live" aria-label="<?php echo $t ? 'Live & Archive' : 'ライブ配信・アーカイブ'; ?>">
  <div class="fp-youtube__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="fp-section-head fp-section-head--light">
      <span class="fp-eyebrow fp-eyebrow--gold"><?php echo $t ? 'LIVE & ARCHIVE' : 'ライブ配信・アーカイブ'; ?></span>
      <h2 class="fp-section-title fp-section-title--light"><?php echo $t ? 'Watch the Action' : '試合を観る・振り返る'; ?></h2>
    </div>

    <?php
    // 将来的にWordPress管理画面（カスタムフィールド等）から入力されるデータを想定した変数設計
    // ACF(Advanced Custom Fields)等で実装する場合は、get_field()等に置き換え可能です。
    $main_video_id = 'f1IKVDfQBHg'; // 大会公式PV
    $archive_videos = [
      [
        'id'    => 'udls0GxvWgM',
        'tag'   => $t ? 'WARM-UP' : '強化試合',
        'title' => $t ? '2023:08:27 QT vs Tokyo Gaijin Club' : '2023:08:27 QT vs 東京外人クラブ',
        'dur'   => '1:44',
      ],
      [
        'id'    => 'TC6nhTqGP1g',
        'tag'   => $t ? 'EXHIBITION' : 'エキシビション',
        'title' => $t ? '2019 ANZ International Touch Championship vs Hong Kong' : '2019 ANZ International Touch Championship vs 香港チーム（エキシビションマッチ）',
        'dur'   => '5:41',
      ],
      [
        'id'    => '13Ty-TiXBaY',
        'tag'   => $t ? 'FRIENDLY' : '親善試合',
        'title' => $t ? 'International Friendly Match vs Hong Kong Match 3' : '国際親善試合 vs香港 Match 3',
        'dur'   => '4:32',
      ],
    ];
    ?>

    <div class="fp-youtube__layout">
      <!-- Main player -->
      <div class="fp-youtube__main">
        <?php
        $db_live_active = get_option( 'defragby_live_active', '' );
        $db_live_video_id = get_option( 'defragby_live_video_id', '' );
        
        $is_currently_live = ($db_live_active === 'on');
        
        if ($is_currently_live) {
          // If live is active, load live video ID or fallback to live channel streaming
          if (!empty($db_live_video_id)) {
            $embed_src = "https://www.youtube.com/embed/" . esc_attr($db_live_video_id) . "?rel=0&modestbranding=1&autoplay=1";
          } else {
            $embed_src = "https://www.youtube.com/embed/live_stream?channel=UCehQeAXq6JyRzAgi4XLv7gQ&autoplay=1";
          }
        } else {
          // Normal fallback video
          $embed_src = "https://www.youtube.com/embed/" . esc_attr($main_video_id) . "?rel=0&modestbranding=1";
        }
        ?>
        <div class="fp-youtube__player-wrap" id="yt-main-player">
          <iframe
            src="<?php echo $embed_src; ?>"
            title="Japan Deaf Rugby — Official Video"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
            loading="lazy"
            referrerpolicy="strict-origin-when-cross-origin">
          </iframe>
        </div>
        <!-- LIVE badge (shown by database options config) -->
        <div class="fp-youtube__live-badge" id="yt-live-badge" aria-live="polite" style="display: <?php echo $is_currently_live ? 'flex' : 'none'; ?> !important;">
          <span class="fp-youtube__live-dot"></span>
          <?php echo $t ? 'LIVE' : 'ライブ配信中'; ?>
        </div>
      </div>

      <!-- Archive sidebar with vertical auto-scroll carousel -->
      <div class="fp-youtube__sidebar">
        <h3 class="fp-youtube__sidebar-title">
          <i class="fa-solid fa-film"></i>
          <?php echo $t ? 'Featured Clips' : '注目動画・アーカイブ'; ?>
        </h3>
        <!-- Vertical carousel viewport -->
        <div class="fp-vc__viewport" id="yt-vcarousel" aria-label="<?php echo $t ? 'Auto-scrolling video list' : '動画自動スクロール'; ?>">
          <div class="fp-vc__track" id="yt-vcarousel-track">
            <?php
            // シームレスなループのため、配列を2倍にして出力します
            $looped_videos = array_merge($archive_videos, $archive_videos);
            foreach ($looped_videos as $vid):
              $video_url = 'https://www.youtube.com/watch?v=' . $vid['id'];
              $thumb_url = 'https://img.youtube.com/vi/' . $vid['id'] . '/mqdefault.jpg';
            ?>
            <a href="<?php echo esc_url($video_url); ?>" target="_blank" rel="noopener"
               class="fp-youtube__archive-item fp-vc__item">
              <div class="fp-youtube__thumb">
                <img src="<?php echo esc_url($thumb_url); ?>" alt="<?php echo esc_attr($vid['title']); ?>" loading="lazy" class="fp-youtube__thumb-img">
                <span class="fp-youtube__play-icon"><i class="fa-solid fa-play"></i></span>
                <span class="fp-youtube__duration"><?php echo esc_html($vid['dur']); ?></span>
              </div>
              <div class="fp-youtube__meta">
                <span class="fp-youtube__video-tag"><?php echo esc_html($vid['tag']); ?></span>
                <p class="fp-youtube__video-title"><?php echo esc_html($vid['title']); ?></p>
                <span class="fp-youtube__channel"><i class="fa-brands fa-youtube" style="color:#FF0000;font-size:0.7rem;"></i> Japan Deaf Rugby</span>
              </div>
            </a>
            <?php endforeach; ?>
          </div><!-- /.fp-vc__track -->
        </div><!-- /.fp-vc__viewport -->

        <a href="https://www.youtube.com/channel/UCehQeAXq6JyRzAgi4XLv7gQ"
           target="_blank" rel="noopener" class="fp-youtube__channel-link">
          <i class="fa-brands fa-youtube"></i>
          <?php echo $t ? 'View All Videos on YouTube' : 'チャンネルの動画をもっと見る'; ?>
          <i class="fa-solid fa-arrow-up-right-from-square"></i>
        </a>
      </div><!-- /.fp-youtube__sidebar -->
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
  5. FEATURED GOODS
═══════════════════════════════════════════════════════════ -->
<section class="fp-section fp-goods" id="goods" aria-label="<?php echo $t ? 'Official merchandise' : '公式グッズ'; ?>">
  <div class="fp-goods__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="fp-section-head">
      <h2 class="fp-section-title"><?php echo $t ? 'Limited Edition Items' : '限定グッズ ピックアップ'; ?></h2>
      <a href="<?php echo esc_url(home_url('/goods/')); ?>" class="fp-section-link">
        <?php echo $t ? 'All Items →' : 'グッズ一覧へ →'; ?>
      </a>
    </div>

    <div class="fp-goods__grid">
      <?php
      $featured = [
        [
          'icon'    => ' ',
          'label'   => $t ? 'PRE-ORDER' : '予約受付中',
          'label_c' => 'primary',
          'name'    => $t ? 'Official T-Shirt' : '大会公式Tシャツ',
          'price'   => '¥3,500',
          'desc'    => $t ? 'Dry-fit with navy/rose "Equal" back-print. Limited run.' : '大会カラーのドライ素材スポーツTシャツ。手話グラフィックをバックプリント。限定品。',
        ],
        [
          'icon'    => ' ',
          'label'   => $t ? 'LIMITED' : '限定品',
          'label_c' => 'gold',
          'name'    => $t ? 'Official Scarf Towel' : '大会公式マフラータオル',
          'price'   => '¥2,000',
          'desc'    => $t ? 'Jacquard-woven championship emblem towel. Perfect for sign-cheer.' : '両面ジャガード織りの応援マフラータオル。手話応援にも最適。',
        ],
        [
          'icon'    => ' ',
          'label'   => $t ? 'PRE-ORDER' : '予約受付中',
          'label_c' => 'primary',
          'name'    => $t ? 'Official Tote Bag' : '大会公式トートバッグ',
          'price'   => '¥1,500',
          'desc'    => $t ? 'Canvas eco-tote silk-screened with the TOKYO 2026 logo.' : '大判キャンバス生地にTOKYO 2026ロゴをシルクプリント。',
        ],
      ];
      foreach ($featured as $g):
      ?>
      <a href="<?php echo esc_url(home_url('/goods/')); ?>" class="fp-goods-card" aria-label="<?php echo esc_attr($g['name']); ?>">
        <div class="fp-goods-card__img">
          <span class="fp-goods-card__icon"><?php echo $g['icon']; ?></span>
          <span class="fp-goods-card__badge fp-goods-card__badge--<?php echo esc_attr($g['label_c']); ?>">
            <?php echo esc_html($g['label']); ?>
          </span>
        </div>
        <div class="fp-goods-card__body">
          <h3 class="fp-goods-card__name"><?php echo esc_html($g['name']); ?></h3>
          <p class="fp-goods-card__desc"><?php echo esc_html($g['desc']); ?></p>
          <div class="fp-goods-card__footer">
            <span class="fp-goods-card__price"><?php echo esc_html($g['price']); ?></span>
            <span class="fp-goods-card__cta">
              <?php echo $t ? 'View Details' : '詳細を見る'; ?>
              <i class="fa-solid fa-arrow-right"></i>
            </span>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>


<!-- ═══════════════════════════════════════════════════════════
  6. NEWS STRIP (retained, refined)
═══════════════════════════════════════════════════════════ -->
<section class="fp-section fp-news" id="news" aria-label="<?php echo $t ? 'Latest news' : '最新ニュース'; ?>">
  <div class="container">
    <div class="fp-section-head">
      <h2 class="fp-section-title"><?php echo $t ? 'Latest Updates' : '最新情報'; ?></h2>
      <a href="<?php echo esc_url(home_url('/news/')); ?>" class="fp-section-link">
        <?php echo $t ? 'All News →' : 'すべてのニュースへ →'; ?>
      </a>
    </div>
    <div class="fp-news__list">
      <?php
      $news_items = [
        ['date' => '2026.06.16', 'tag' => $t ? 'NOTICE' : 'お知らせ', 'title' => $t ? 'Japan Training Camp (June) — Spectator Info' : '【お知らせ】日本代表強化合宿（6月）開催および見学のご案内'],
        ['date' => '2026.05.26', 'tag' => $t ? 'SQUAD' : '選手情報',  'title' => $t ? 'Three New Players Added to National Squad' : '日本代表内定選手3名追加決定のお知らせ'],
        ['date' => '2026.05.22', 'tag' => $t ? 'SCHEDULE' : 'スケジュール', 'title' => $t ? '2026 Domestic Schedule & Training Camps Published' : '【スケジュール】2026年度 国内大会・代表合宿日程公開'],
        ['date' => '2026.04.10', 'tag' => $t ? 'GOODS' : 'グッズ',    'title' => $t ? 'Official Merchandise Pre-Order Now Open' : '公式グッズ予約販売スタートのお知らせ'],
      ];
      foreach ($news_items as $item):
      ?>
      <a href="<?php echo esc_url(home_url('/news/')); ?>" class="fp-news-row">
        <span class="fp-news-date"><?php echo esc_html($item['date']); ?></span>
        <span class="fp-news-title"><?php echo esc_html($item['title']); ?></span>
        <i class="fa-solid fa-arrow-right fp-news-arrow"></i>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ═══════════════════════════════════════════════════════════
  6. SPONSORS & PARTNERS (Top 5 Gold/Silver)
═══════════════════════════════════════════════════════════ -->
<section class="fp-section fp-sponsors" id="sponsors" aria-label="<?php echo $t ? 'Sponsors' : 'スポンサー・パートナー'; ?>">
  <div class="container">
    <div class="fp-section-head">
      <h2 class="fp-section-title"><?php echo $t ? 'OFFICIAL SPONSORS' : '大会スポンサー・パートナー'; ?></h2>
      <a href="<?php echo esc_url( home_url( '/how-to-enjoy/sponsor/' ) ); ?><?php echo $current_lang === 'en' ? '?lang=en' : ''; ?>" class="fp-section-link fp-section-link--gold">
        <?php echo $t ? 'All Sponsors →' : 'スポンサー一覧へ →'; ?>
      </a>
    </div>

    <?php
    $all_sponsors = get_option( 'defragby_sponsors_data', array() );
    $top_sponsors = array();

    // ランクの優先順位定義 (数値が小さいほど優先)
    $rank_priority = array(
      'gold'         => 1,
      'silver'       => 2,
      'partner_a'    => 3,
      'partner_b'    => 4,
      'co_host'      => 5,
      'crowdfunding' => 6,
    );

    if ( is_array( $all_sponsors ) ) {
      foreach ( $all_sponsors as $idx => $s ) {
        if ( ! empty( $s['name'] ) ) {
          $rank                = isset( $s['rank'] ) ? $s['rank'] : 'gold';
          $s['_rank_priority'] = isset( $rank_priority[ $rank ] ) ? $rank_priority[ $rank ] : 99;
          $s['_order']         = isset( $s['order'] ) ? intval( $s['order'] ) : $idx;
          $top_sponsors[]      = $s;
        }
      }
    }

    // ソート処理：1. ランク優先順位 昇順 → 2. 同ランク内の表示順 昇順
    usort( $top_sponsors, function( $a, $b ) {
      if ( $a['_rank_priority'] !== $b['_rank_priority'] ) {
        return $a['_rank_priority'] - $b['_rank_priority'];
      }
      return $a['_order'] - $b['_order'];
    } );

    // ランク優先＋ランク内表示順で上位5件を取得
    $top_sponsors = array_slice( $top_sponsors, 0, 5 );
    $sponsor_page_url = esc_url( home_url( '/how-to-enjoy/sponsor/' ) ) . ( $current_lang === 'en' ? '?lang=en' : '' );
    ?>

    <?php if ( ! empty( $top_sponsors ) ) : ?>
      <div class="footer-sponsor-cards">
        <?php foreach ( $top_sponsors as $s ) :
          $has_logo   = ! empty( $s['logo_url'] );
          $rank       = isset( $s['rank'] ) ? $s['rank'] : 'gold';
          $rank_slug  = str_replace( '_', '-', $rank );
          $card_cls   = 'sponsor-card sponsor-card--' . esc_attr( $rank_slug ) . ' footer-sponsor-card';
          $ribbon_cls = 'sponsor-ribbon--' . esc_attr( $rank_slug );
        ?>
          <a href="<?php echo $sponsor_page_url; ?>" class="<?php echo $card_cls; ?>">
            <?php if ( $rank !== 'co_host' && $rank !== 'crowdfunding' ) : ?>
              <div class="<?php echo $ribbon_cls; ?>" aria-hidden="true"></div>
            <?php endif; ?>
            <div class="sponsor-card__inner">
              <div class="sponsor-card__top-space"></div>
              <div class="sponsor-card__logo-wrap">
                <?php if ( $has_logo ) : ?>
                  <img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php echo esc_attr( $s['name'] ); ?>" class="sponsor-card__logo-img">
                <?php else : ?>
                  <div class="sponsor-card__logo-placeholder"></div>
                <?php endif; ?>
              </div>
              <div class="sponsor-card__name-wrap">
                <p class="sponsor-card__name"><?php echo esc_html( $s['name'] ); ?></p>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <p class="footer-sponsor-empty"><?php echo esc_html( get_multilang_text( '準備中', 'Coming Soon' ) ); ?></p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
