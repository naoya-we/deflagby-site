<?php
/**
 * Template Name: Competition & Venue Info
 */
get_header();
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
$t = ( $current_lang === 'en' );
?>

<main id="primary" class="site-main container">
  <header class="page-header">
    <h1 class="page-title"><?php echo esc_html( get_multilang_text('競技・会場情報', 'Competition & Venue Info') ); ?></h1>
    <div class="entry-meta">COMPETITION BRACKETS, MATCH SCHEDULE & STADIUMS</div>
  </header>

  <!-- ⚠️ 会場注意バナー -->
  <div class="venue-notice-banner">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <span><?php echo esc_html( get_multilang_text(
      '開催日によって会場が異なりますので、ご注意ください。',
      'Please note that the venue differs depending on the match day.'
    ) ); ?></span>
  </div>

  <div class="info-container" style="width: 100%; margin: 0 auto; display: flex; flex-direction: column; gap: 80px;">

    <!-- ═══════════════════════════════════════════════════
         1. グループステージ / Group Stage Standings
         ═══════════════════════════════════════════════════ -->
    <section class="info-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('グループステージ', 'Group Stage') ); ?></h2>
      <p style="margin-bottom: 32px;">
        <?php echo esc_html( get_multilang_text(
          '男子2プール・女子1プールのグループ戦を行い、各プールの上位チームが決勝トーナメントに進出します。',
          'Three pools in total — Men\'s Pool A, Men\'s Pool B, and Women\'s Pool A. Top teams from each pool advance to the knockout bracket.'
        ) ); ?>
      </p>

      <?php
      /* ── プール設定（ラベル・カラー） ───────────────────── */
      $pool_config = [
        'men-a'   => ['label' => $t ? "MEN'S POOL A"   : '男子 プールA', 'color' => '#1E4D8C'],
        'men-b'   => ['label' => $t ? "MEN'S POOL B"   : '男子 プールB', 'color' => '#1E4D8C'],
        'women-a' => ['label' => $t ? "WOMEN'S POOL A" : '女子 プールA', 'color' => '#8C1E4D'],
      ];

      /* ── スタンディングデータ取得 ── */
      $pool_standings = function_exists('defragby_get_pool_standings') ? defragby_get_pool_standings() : get_option( 'defragby_pool_standings', array() );

      $col_labels = [
        'rank'   => $t ? '#'    : '順位',
        'team'   => $t ? 'TEAM' : 'チーム',
        'played' => $t ? 'P'    : '試合',
        'won'    => $t ? 'W'    : '勝',
        'drawn'  => $t ? 'D'    : '分',
        'lost'   => $t ? 'L'    : '負',
        'gf'     => $t ? 'GF'   : '得点',
        'ga'     => $t ? 'GA'   : '失点',
        'gd'     => $t ? 'GD'   : '得失差',
        'pts'    => $t ? 'PTS'  : 'ポイント',
      ];
      ?>

      <div class="group-tables-grid">
        <?php foreach ( $pool_config as $pool_id => $cfg ) :
          if ( ! isset( $pool_standings[ $pool_id ] ) ) continue;
          $pool_teams = $pool_standings[ $pool_id ];
          $rank = 0;
        ?>
        <div class="group-table-wrap">
          <div class="group-table-header" style="background:<?php echo esc_attr($cfg['color']); ?>;">
            <span class="group-table-label"><?php echo esc_html($cfg['label']); ?></span>
          </div>
          <div class="group-table-scroll">
            <table class="standings-table">
              <thead>
                <tr>
                  <th class="col-rank"><?php echo esc_html($col_labels['rank']); ?></th>
                  <th class="col-team"><?php echo esc_html($col_labels['team']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['played']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['won']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['drawn']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['lost']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['gf']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['ga']); ?></th>
                  <th class="col-num"><?php echo esc_html($col_labels['gd']); ?></th>
                  <th class="col-pts"><?php echo esc_html($col_labels['pts']); ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ( $pool_teams as $team_name => $team ) :
                  $gd      = (int)$team['gf'] - (int)$team['ga'];
                  $display = $t ? esc_html($team['name_en']) : esc_html($team_name);
                  $advance = ($rank < 2);
                  $rank++;
                ?>
                <tr class="<?php echo $advance ? 'row-advance' : ''; ?>">
                  <td class="col-rank"><?php echo $rank; ?></td>
                  <td class="col-team">
                    <?php if ($advance) : ?><span class="advance-dot" title="<?php echo esc_attr($t ? 'Advances' : '突破圏内'); ?>"></span><?php endif; ?>
                    <?php echo $display; ?>
                  </td>
                  <td class="col-num"><?php echo (int)$team['p']; ?></td>
                  <td class="col-num"><?php echo (int)$team['w']; ?></td>
                  <td class="col-num"><?php echo (int)$team['d']; ?></td>
                  <td class="col-num"><?php echo (int)$team['l']; ?></td>
                  <td class="col-num"><?php echo (int)$team['gf']; ?></td>
                  <td class="col-num"><?php echo (int)$team['ga']; ?></td>
                  <td class="col-num"><?php echo ($gd >= 0 ? '+' : '') . $gd; ?></td>
                  <td class="col-pts"><strong><?php echo (int)$team['pts']; ?></strong></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <div class="group-table-note">
            <span class="advance-dot"></span>
            <?php echo esc_html( $t ? 'Advances to knockout round' : '決勝トーナメント進出' ); ?>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         2. トーナメント表 / Knockout Bracket
         ═══════════════════════════════════════════════════ -->
    <section class="info-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('決勝トーナメント表', 'Knockout Bracket') ); ?></h2>
      <p style="margin-bottom: 32px;">
        <?php echo esc_html( get_multilang_text(
          'グループステージを勝ち抜いたチームによるトーナメント方式の決勝戦です。男子・女子それぞれで実施します。',
          'Knockout rounds for both Men\'s and Women\'s competitions, featuring teams that advance from the group stage.'
        ) ); ?>
      </p>

      <?php
      $brackets = [
        [
          'label' => $t ? "MEN'S BRACKET" : '男子 トーナメント',
          'color' => '#1E4D8C',
          'icon'  => 'fa-mars',
          'sf' => [
            ['label' => $t ? 'SEMI-FINAL 1' : '準決勝1', 'team_a' => $t ? 'Pool A 1st' : 'プールA 1位', 'team_b' => $t ? 'Pool B 2nd' : 'プールB 2位'],
            ['label' => $t ? 'SEMI-FINAL 2' : '準決勝2', 'team_a' => $t ? 'Pool B 1st' : 'プールB 1位', 'team_b' => $t ? 'Pool A 2nd' : 'プールA 2位'],
          ],
          'final_label' => $t ? 'CUP FINAL' : '決勝',
          'final_venue' => $t ? 'Chichibunomiya Rugby Stadium' : '秩父宮ラグビー場',
          'final_date'  => '11.03 [火・祝]',
          'map_url'     => 'https://maps.app.goo.gl/bFpSrqX5wWcwK93H8',
        ],
        [
          'label' => $t ? "WOMEN'S BRACKET" : '女子 トーナメント',
          'color' => '#8C1E4D',
          'icon'  => 'fa-venus',
          'sf' => [
            ['label' => $t ? 'SEMI-FINAL 1' : '準決勝1', 'team_a' => $t ? 'Pool A 1st' : 'プールA 1位', 'team_b' => $t ? 'Pool A 3rd' : 'プールA 3位'],
            ['label' => $t ? 'SEMI-FINAL 2' : '準決勝2', 'team_a' => $t ? 'Pool A 2nd' : 'プールA 2位', 'team_b' => $t ? 'Pool A 4th' : 'プールA 4位'],
          ],
          'final_label' => $t ? 'CUP FINAL' : '決勝',
          'final_venue' => $t ? 'Chichibunomiya Rugby Stadium' : '秩父宮ラグビー場',
          'final_date'  => '11.03 [火・祝]',
          'map_url'     => 'https://maps.app.goo.gl/bFpSrqX5wWcwK93H8',
        ],
      ];
      ?>

      <div class="brackets-stack">
        <?php foreach ($brackets as $bracket) : ?>
        <div class="bracket-block">
          <!-- ブラケットタイトル -->
          <div class="bracket-block-title" style="border-color:<?php echo esc_attr($bracket['color']); ?>;">
            <i class="fa-solid <?php echo esc_attr($bracket['icon']); ?>"></i>
            <?php echo esc_html($bracket['label']); ?>
          </div>

          <div class="bracket-layout">
            <!-- 準決勝 -->
            <div class="bracket-col bracket-sf-col">
              <div class="bracket-col-label"><?php echo esc_html($t ? 'SEMI-FINALS' : '準決勝'); ?></div>
              <?php foreach ($bracket['sf'] as $sf) : ?>
              <div class="bracket-match">
                <div class="bracket-match-label"><?php echo esc_html($sf['label']); ?></div>
                <div class="bracket-team"><?php echo esc_html($sf['team_a']); ?><span class="bracket-score">-</span></div>
                <div class="bracket-team"><?php echo esc_html($sf['team_b']); ?><span class="bracket-score">-</span></div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- 矢印コネクタ -->
            <div class="bracket-connector" aria-hidden="true">
              <svg viewBox="0 0 60 160" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                <path d="M0,40 H30 V120 H0" fill="none" stroke="#CBD5E1" stroke-width="2"/>
                <path d="M30,80 H60" fill="none" stroke="#CBD5E1" stroke-width="2"/>
              </svg>
            </div>

            <!-- 決勝 -->
            <div class="bracket-col bracket-final-col">
              <div class="bracket-col-label"><?php echo esc_html($t ? 'FINAL' : '決勝'); ?></div>
              <div class="bracket-match bracket-match--final" style="border-color:<?php echo esc_attr($bracket['color']); ?>;">
                <div class="bracket-match-label" style="color:<?php echo esc_attr($bracket['color']); ?>;">
                  <?php echo esc_html($bracket['final_label']); ?>
                </div>
                <div class="bracket-team bracket-team--tbd"><?php echo esc_html($t ? 'Winner SF1' : '準決勝1 勝者'); ?><span class="bracket-score">-</span></div>
                <div class="bracket-team bracket-team--tbd"><?php echo esc_html($t ? 'Winner SF2' : '準決勝2 勝者'); ?><span class="bracket-score">-</span></div>
                <div class="bracket-venue-tag">
                  <a href="<?php echo esc_url($bracket['map_url']); ?>" target="_blank" rel="noopener" class="venue-map-link">
                    <i class="fa-solid fa-location-dot"></i>
                    <?php echo esc_html($bracket['final_venue']); ?>
                  </a>
                  <span class="bracket-venue-date"><?php echo esc_html($bracket['final_date']); ?></span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         3. 試合日程 / Match Schedule
         ═══════════════════════════════════════════════════ -->
    <section class="info-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('試合日程', 'Match Schedule') ); ?></h2>
      <p style="margin-bottom: 24px;">
        <?php echo esc_html( get_multilang_text(
          '本大会は、予選はリーグ戦、決勝はトーナメント戦です。全試合公式YouTubeチャンネルにて配信予定です。',
          'Pool stage is round-robin; knockout stage is single-elimination. All matches will be streamed live on our official YouTube channel.'
        ) ); ?>
      </p>

      <?php
      $db_matches = get_option( 'defragby_matches_data', array() );
      $venue_map  = function_exists('defragby_get_venue_map') ? defragby_get_venue_map() : array();
      $yt_channel = get_option( 'defragby_youtube_channel_url', 'https://www.youtube.com/' );

      if ( empty( $db_matches ) ) {
        $db_matches = [
          ['venue_date'=>'1031','time'=>'10:00','team_a_ja'=>'日本','team_a_en'=>'JAPAN','team_b_ja'=>'ニュージーランド','team_b_en'=>'NEW ZEALAND','score_a'=>'','score_b'=>'','round'=>'group'],
        ];
      }
      ?>

      <div class="info-matches-grid">
        <?php foreach ($db_matches as $match) :
          $team_a_ja   = isset($match['team_a_ja']) ? $match['team_a_ja'] : '';
          $team_a_en   = isset($match['team_a_en']) ? $match['team_a_en'] : '';
          $team_b_ja   = isset($match['team_b_ja']) ? $match['team_b_ja'] : '';
          $team_b_en   = isset($match['team_b_en']) ? $match['team_b_en'] : '';
          $team_a_name = $t ? $team_a_en : $team_a_ja;
          $team_b_name = $t ? $team_b_en : $team_b_ja;

          $team_a_icon = function_exists('defragby_get_team_icon_html') ? defragby_get_team_icon_html($team_a_ja, $team_a_en) : '🏳️';
          $team_b_icon = function_exists('defragby_get_team_icon_html') ? defragby_get_team_icon_html($team_b_ja, $team_b_en) : '🏳️';
          
          $vd_key    = isset($match['venue_date']) ? $match['venue_date'] : '';
          $vd_info   = isset($venue_map[$vd_key]) ? $venue_map[$vd_key] : null;
          $date_str  = $vd_info ? ($t ? $vd_info['date_en'] : $vd_info['date_ja']) : ( isset($match['date']) ? $match['date'] : '' );
          $venue_str = $vd_info ? ($t ? $vd_info['venue_en'] : $vd_info['venue_ja']) : ($t ? 'Tokyo Venues' : '東京都内競技場');
          $map_url   = function_exists('defragby_get_venue_map_url') ? defragby_get_venue_map_url($match) : '';
          $time_str  = isset($match['time']) ? $match['time'] : '';

          $score_a_val = (isset($match['score_a']) && $match['score_a'] !== '') ? (int)$match['score_a'] : null;
          $score_b_val = (isset($match['score_b']) && $match['score_b'] !== '') ? (int)$match['score_b'] : null;
          $is_finished = ($score_a_val !== null && $score_b_val !== null);

          // 点数が低い方のスコア数字の色を自動で青(--loser)にする
          $score_a_class = '';
          $score_b_class = '';
          if ($is_finished) {
            if ($score_a_val < $score_b_val) {
              $score_a_class = ' info-match-score--loser';
            } elseif ($score_b_val < $score_a_val) {
              $score_b_class = ' info-match-score--loser';
            } else {
              // 引き分け（同点）の場合は両チームとも黒色(#0d121c)
              $score_a_class = ' info-match-score--draw';
              $score_b_class = ' info-match-score--draw';
            }
          }

          $status_label = $is_finished ? ($t ? 'Full Time' : '試合終了') : ($t ? 'Scheduled' : '開催予定');

          // グループ／区分ラベルの生成（例：男子 プールA）
          $pool_code = isset($match['team_a_pool']) ? $match['team_a_pool'] : '';
          $round_key = isset($match['round']) ? $match['round'] : 'group';

          if ($round_key === 'sf') {
            $group_display_name = $t ? 'SEMI-FINAL' : '準決勝';
          } elseif ($round_key === 'final') {
            $group_display_name = $t ? 'FINAL' : '決勝';
          } else {
            $pool_names_map = [
              'men-a'   => ['男子 プールA', "MEN'S POOL A"],
              'men-b'   => ['男子 プールB', "MEN'S POOL B"],
              'women-a' => ['女子 プールA', "WOMEN'S POOL A"],
            ];
            if ( isset($pool_names_map[$pool_code]) ) {
              $group_display_name = $pool_names_map[$pool_code][$t ? 1 : 0];
            } else {
              $group_display_name = $t ? 'Group Stage' : 'グループ戦';
            }
          }
        ?>
        <div class="info-match-card">
          <!-- カードヘッダー行 -->
          <div class="info-match-card__header">
            <span class="info-match-card__date"><i class="fa-regular fa-calendar"></i> <?php echo esc_html($date_str); ?> <?php if ($time_str) echo esc_html($time_str . ' K.O.'); ?></span>
            <span class="info-match-card__status info-match-card__status--<?php echo $is_finished ? 'finished' : 'upcoming'; ?>"><?php echo esc_html($status_label); ?></span>
          </div>

          <!-- カードメインボディ -->
          <div class="info-match-card__body">
            <div class="info-match-card__group"><?php echo esc_html($group_display_name); ?></div>
            
            <div class="info-match-card__matchup">
              <!-- チームA -->
              <div class="info-match-team">
                <div class="info-match-team-name"><?php echo esc_html($team_a_name); ?></div>
                <div class="info-match-flag"><?php echo $team_a_icon; ?></div>
                <?php if ($is_finished) : ?>
                <div class="info-match-score<?php echo $score_a_class; ?>"><?php echo esc_html($score_a_val); ?></div>
                <?php endif; ?>
              </div>

              <!-- VSバッジ -->
              <div class="info-match-vs">VS</div>

              <!-- チームB -->
              <div class="info-match-team">
                <div class="info-match-team-name"><?php echo esc_html($team_b_name); ?></div>
                <div class="info-match-flag"><?php echo $team_b_icon; ?></div>
                <?php if ($is_finished) : ?>
                <div class="info-match-score<?php echo $score_b_class; ?>"><?php echo esc_html($score_b_val); ?></div>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- カードフッター行 -->
          <div class="info-match-card__footer">
            <div class="info-match-card__venue">
              <?php if ( ! $is_finished && ! empty( $map_url ) ) : ?>
                <a href="<?php echo esc_url( $map_url ); ?>" target="_blank" rel="noopener" class="venue-map-link">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo esc_html( $venue_str ); ?>
                  <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:0.75em;"></i>
                </a>
              <?php else : ?>
                <span class="venue-text-disabled">
                  <i class="fa-solid fa-location-dot"></i>
                  <?php echo esc_html( $venue_str ); ?>
                </span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- ═══════════════════════════════════════════════════
         4. 会場案内 & アクセス
         ═══════════════════════════════════════════════════ -->
    <section class="info-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('会場案内 ＆ アクセス', 'Venues & Transportation') ); ?></h2>
      <p style="margin-bottom: 24px;">
        <?php echo esc_html( get_multilang_text(
          '各競技スタジアム名をクリックすると、詳細な住所やアクセス情報が表示されます。',
          'Click on each stadium name below to toggle detailed transit route maps.'
        ) ); ?>
      </p>

      <!-- Venue 1 Accordion -->
      <details style="border: 2px solid var(--color-border); margin-bottom: 12px; background: #fff;">
        <summary style="padding: 18px 20px; font-weight: 900; color: var(--color-navy); font-size: 1.1rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; list-style: none;">
          <span>10/31[火・祝] 夢の島競技場 (Yumenoshima Stadium)</span>
          <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--color-text-light);"></i>
        </summary>
        <div style="padding: 20px; border-top: 1px solid var(--color-border); background: #fafafa; font-size: 0.95rem; line-height: 1.7;">
          <p style="margin: 0 0 10px;"><strong><?php echo esc_html( get_multilang_text('住所：', 'Address: ') ); ?></strong>東京都江東区夢の島1-1-2</p>
          <ul style="margin: 0; padding-left: 20px;">
            <li>🚇 <?php echo esc_html( get_multilang_text('JR京葉線・東京メトロ有楽町線・りんかい線「新木場駅」より徒歩約7分', '7-minute walk from Shinkiba Station (JR Keiyo Line / Tokyo Metro Yurakucho Line / Rinkai Line)') ); ?></li>
          </ul>
        </div>
      </details>

      <!-- Venue 2 Accordion -->
      <details style="border: 2px solid var(--color-border); margin-bottom: 12px; background: #fff;">
        <summary style="padding: 18px 20px; font-weight: 900; color: var(--color-navy); font-size: 1.1rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; list-style: none;">
          <span>11/02[月] 江戸川区陸上競技場 (Edogawa Stadium)</span>
          <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--color-text-light);"></i>
        </summary>
        <div style="padding: 20px; border-top: 1px solid var(--color-border); background: #fafafa; font-size: 0.95rem; line-height: 1.7;">
          <p style="margin: 0 0 10px;"><strong><?php echo esc_html( get_multilang_text('住所：', 'Address: ') ); ?></strong>東京都江戸川区清新町2-1-1</p>
          <ul style="margin: 0 0 12px; padding-left: 20px;">
            <li>🚇 <?php echo esc_html( get_multilang_text('東京メトロ東西線「西葛西駅」南口より徒歩約15分', '15-minute walk from Nishi-Kasai Station (Tokyo Metro Tozai Line), South Exit') ); ?></li>
            <li>🚌 <?php echo esc_html( get_multilang_text('西葛西駅より都営バス「臨海町二丁目団地前」行に乗車、「清新ふたば小学校前」下車徒歩2分', 'Take Toei Bus from Nishi-Kasai Station, get off at "Seishinfutaba Elementary School", 2-minute walk') ); ?></li>
          </ul>
        </div>
      </details>

      <!-- Venue 3 Accordion -->
       <details style="border: 2px solid var(--color-border); margin-bottom: 12px; background: #fff;">
        <summary style="padding: 18px 20px; font-weight: 900; color: var(--color-navy); font-size: 1.1rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; list-style: none;">
          <span>11/03[火・祝] 秩父宮ラグビー場 (Chichibunomiya Rugby Stadium)</span>
          <i class="fa-solid fa-chevron-down" style="font-size: 0.8rem; color: var(--color-text-light);"></i>
        </summary>
        <div style="padding: 20px; border-top: 1px solid var(--color-border); background: #fafafa; font-size: 0.95rem; line-height: 1.7;">
          <p style="margin: 0 0 10px;"><strong><?php echo esc_html( get_multilang_text('住所：', 'Address: ') ); ?></strong>東京都港区北青山2-8-35</p>
          <ul style="margin: 0; padding-left: 20px;">
            <li>🚇 <?php echo esc_html( get_multilang_text('東京メトロ銀座線「外苑前駅」3番出口より徒歩5分', '5-minute walk from Gaiemmae Station (Tokyo Metro Ginza Line), Exit 3') ); ?></li>
            <li>🚇 <?php echo esc_html( get_multilang_text('都営大江戸線「青山一丁目駅」1番出口より徒歩10分', '10-minute walk from Aoyama-itchome Station (Toei Oedo Line), Exit 1') ); ?></li>
          </ul>
        </div>
      </details>
    </section>

  </div>
</main>

<?php get_footer(); ?>
