<?php
/**
 * Template Name: About Tournament
 */
get_header();
$current_lang = isset($_GET['lang']) && $_GET['lang'] === 'en' ? 'en' : 'ja';
?>

<main id="primary" class="site-main container">
  <!-- Subpage Header slant & photo blend is applied globally via .page-header -->
  <header class="page-header page-header--about">
    <h1 class="page-title"><?php echo esc_html( get_multilang_text('大会について', 'About the Tournament') ); ?></h1>
    <div class="entry-meta">3rd WORLD DEAF RUGBY SEVENS CHAMPIONSHIP</div>
  </header>

  <div class="about-container" style="max-width: 1000px; margin: 0 auto; display: flex; flex-direction: column; gap: 80px;">
    
    <!-- 1. 大会概要 -->
    <section class="about-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('大会概要', 'Tournament Overview') ); ?></h2>
      <p style="margin-bottom: 24px; font-size: 1.05rem; font-weight: 500;">
        <?php echo esc_html( get_multilang_text(
          '「挑戦が、共生を動かす」を掲げ、世界最高峰の聴覚障がい者ラグビー選手が東京に集結します。本大会は、アスリートたちの卓越したパフォーマンスを通じて、デフラグビーの普及と国際的な相互理解を深めることを目的としています。',
          'Under the slogan "Equal through Rugby," elite deaf rugby athletes from across the globe gather in Tokyo. This championship aims to promote deaf rugby development and foster international mutual understanding through top-tier sports performance.'
        ) ); ?>
      </p>

      <table>
        <tbody>
          <tr>
            <th style="width: 25%;"><?php echo esc_html( get_multilang_text('大会名称', 'Event Name') ); ?></th>
            <td><?php echo esc_html( get_multilang_text('第3回 7人制デフラグビー世界大会', '3rd World Deaf Rugby Sevens Championship') ); ?></td>
          </tr>
          <tr>
            <th><?php echo esc_html( get_multilang_text('開催日程', 'Dates') ); ?></th>
            <td><?php echo esc_html( get_multilang_text('2026年10月31日（土）〜11月3日（火・祝）', 'October 31 (Sat) – November 3 (Tue), 2026') ); ?></td>
          </tr>
          <tr>
            <th><?php echo esc_html( get_multilang_text('開催地', 'Location') ); ?></th>
            <td>
              <a href="https://maps.app.goo.gl/2CuChSizBoh2A2Es9" target="_blank" rel="noopener" class="venue-map-link">
                <?php echo esc_html( get_multilang_text('夢の島競技場', 'Yumenoshima Stadium') ); ?>
                <i class="fa-solid fa-location-dot" style="font-size:0.8em; margin-left:4px;"></i>
              </a><br>
              <a href="https://maps.app.goo.gl/JGxVW2owkhThFioA9" target="_blank" rel="noopener" class="venue-map-link">
                <?php echo esc_html( get_multilang_text('江戸川区陸上競技場', 'Edogawa Athletic Stadium') ); ?>
                <i class="fa-solid fa-location-dot" style="font-size:0.8em; margin-left:4px;"></i>
              </a><br>
              <a href="https://maps.app.goo.gl/bFpSrqX5wWcwK93H8" target="_blank" rel="noopener" class="venue-map-link">
                <?php echo esc_html( get_multilang_text('秩父宮ラグビー場', 'Chichibunomiya Rugby Stadium') ); ?>
                <i class="fa-solid fa-location-dot" style="font-size:0.8em; margin-left:4px;"></i>
              </a>
            </td>
          </tr>
          <tr>
            <th><?php echo esc_html( get_multilang_text('競技種目', 'Format') ); ?></th>
            <td><?php echo esc_html( get_multilang_text('7人制ラグビー（セブンズ）男子・女子', 'Rugby Sevens (Men & Women)') ); ?></td>
          </tr>
          <tr>
            <th><?php echo esc_html( get_multilang_text('参加チーム', 'Teams') ); ?></th>
            <td>
              <a href="<?php echo esc_url( home_url('/teams/') ); ?>" class="venue-map-link">
                <?php echo esc_html( get_multilang_text('チームについてはこちら', 'View participating teams') ); ?>
                <i class="fa-solid fa-arrow-right" style="font-size:0.8em; margin-left:4px;"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- 2. 開催日程（タイムライン） -->
    <section id="schedule" class="about-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('開催日程・スケジュール', 'Match Schedule') ); ?></h2>
      <p style="margin-bottom: 24px;">
        <?php echo esc_html( get_multilang_text(
          '3日間にわたる熱戦のタイムスケジュールです。開催日によって会場が異なります。ご注意ください。',
          'Over four days, the tournament advances from pool stage qualifiers to the high-stakes final knockout brackets.'
        ) ); ?>
      </p>

      <div class="timeline-container" style="display: flex; flex-direction: column; gap: 20px;">
        <div style="border-left: 4px solid #D23838; padding-left: 20px; position: relative;">
          <h4 style="margin: 0 0 6px; font-family: var(--font-en); font-weight: 900; color: var(--color-navy); font-size: 1.15rem;">DAY 1 : 10.31 [土]</h4>
          <p style="margin: 0; font-size: 0.95rem; line-height: 1.7;">
            <span style="display: block; font-weight: 600;"><?php echo esc_html( get_multilang_text('リーグ戦1日目', 'Tournament Round 1') ); ?></span>
            <a href="https://maps.app.goo.gl/2CuChSizBoh2A2Es9" target="_blank" rel="noopener" class="venue-map-link">
              <?php echo esc_html( get_multilang_text('夢の島競技場', 'Yumenoshima Stadium') ); ?>
              <i class="fa-solid fa-location-dot" style="font-size:0.8em; margin-left:3px;"></i>
            </a>
          </p>
        </div>
        <div style="border-left: 4px solid #D23838; padding-left: 20px; position: relative;">
          <h4 style="margin: 0 0 6px; font-family: var(--font-en); font-weight: 900; color: var(--color-navy); font-size: 1.15rem;">DAY 2 : 11.02 [月]</h4>
          <p style="margin: 0; font-size: 0.95rem; line-height: 1.7;">
            <span style="display: block; font-weight: 600;"><?php echo esc_html( get_multilang_text('リーグ戦2日目', 'Tournament Round 2') ); ?></span>
            <a href="https://maps.app.goo.gl/JGxVW2owkhThFioA9" target="_blank" rel="noopener" class="venue-map-link">
              <?php echo esc_html( get_multilang_text('江戸川区陸上競技場', 'Edogawa Athletic Stadium') ); ?>
              <i class="fa-solid fa-location-dot" style="font-size:0.8em; margin-left:3px;"></i>
            </a>
          </p>
        </div>
        <div style="border-left: 4px solid #D23838; padding-left: 20px; position: relative;">
          <h4 style="margin: 0 0 6px; font-family: var(--font-en); font-weight: 900; color: var(--color-navy); font-size: 1.15rem;">DAY 3 : 11.03 [火・祝]</h4>
          <p style="margin: 0; font-size: 0.95rem; line-height: 1.7;">
            <span style="display: block; font-weight: 600;"><?php echo esc_html( get_multilang_text('決勝戦', 'Final') ); ?></span>
            <a href="https://maps.app.goo.gl/bFpSrqX5wWcwK93H8" target="_blank" rel="noopener" class="venue-map-link">
              <?php echo esc_html( get_multilang_text('秩父宮ラグビー場', 'Chichibunomiya Rugby Stadium') ); ?>
              <i class="fa-solid fa-location-dot" style="font-size:0.8em; margin-left:3px;"></i>
            </a>
          </p>
        </div>
      </div>
    </section>

    <!-- 5. 主催・後援 -->
    <section class="about-section entry-content">
      <h2><?php echo esc_html( get_multilang_text('主催・主管・後援', 'Organizers & Patrons') ); ?></h2>
      <table style="margin-top: 15px;">
        <tbody>
          <tr>
            <th style="width: 25%;"><?php echo esc_html( get_multilang_text('主催', 'Organizer') ); ?></th>
            <td><?php echo esc_html( get_multilang_text('一般社団法人 日本聴覚障がい者ラグビーフットボール連盟', 'Japan Deaf Rugby Football Union (JDRFU)') ); ?></td>
          </tr>
          <tr>
            <th><?php echo esc_html( get_multilang_text('主管', 'Host Union') ); ?></th>
            <td><?php echo esc_html( get_multilang_text('第3回7人制デフラグビー世界大会実行委員会', '3rd World Deaf Rugby Sevens Executive Committee') ); ?></td>
          </tr>
          <tr>
            <th><?php echo esc_html( get_multilang_text('後援、協賛', 'Sponsors') ); ?></th>
            <td>SANKI CORP. / MICAREA / TAISEI / KIYOKAWA / YOSHIOKA</td>
          </tr>
        </tbody>
      </table>
    </section>

  </div>
</main>

<?php
get_footer();
