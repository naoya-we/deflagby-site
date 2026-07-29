<?php
/**
 * Template Name: Sponsors & Partners
 * URL: /how-to-enjoy/sponsor/
 */
get_header();

// 全スポンサーデータ取得
$all_sponsors = get_option( 'defragby_sponsors_data', array() );
if ( ! is_array( $all_sponsors ) ) {
  $all_sponsors = array();
}

// ランク別に分類（登録順を維持）
$ranks = array(
  'gold'      => array(),
  'silver'    => array(),
  'partner_a' => array(),
  'partner_b' => array(),
  'co_host'   => array(),
);

foreach ( $all_sponsors as $s ) {
  $r = isset( $s['rank'] ) ? $s['rank'] : '';
  if ( isset( $ranks[ $r ] ) ) {
    $ranks[ $r ][] = $s;
  }
}

// クラファン支援者テキスト
$crowdfunding_raw   = get_option( 'defragby_crowdfunding_names', '' );
$crowdfunding_names = array();
if ( ! empty( $crowdfunding_raw ) ) {
  $crowdfunding_names = array_filter( array_map( 'trim', explode( "\n", $crowdfunding_raw ) ) );
}
?>

<main id="primary" class="site-main container">
  <header class="page-header page-header--sponsor" style="margin-bottom: 50px; text-align: center;">
    <h1 class="page-title section-title"><?php echo esc_html( get_multilang_text( 'スポンサー・パートナー', 'Sponsors & Partners' ) ); ?></h1>
  </header>

  <div class="sponsor-page-content">

      <!-- 1. GOLD PARTNER -->
      <?php if ( ! empty( $ranks['gold'] ) ) : ?>
      <section class="sponsor-section sponsor-section--gold">
        <div class="sponsor-section__heading">
          <span class="sponsor-section__en">GOLD PARTNER</span>
          <span class="sponsor-section__ja">ゴールドパートナー</span>
        </div>
        <div class="sponsor-cards sponsor-cards--gold">
          <?php foreach ( $ranks['gold'] as $s ) :
            $has_url  = ! empty( $s['url'] );
            $has_logo = ! empty( $s['logo_url'] );
            if ( $has_url ) {
              echo '<a href="' . esc_url( $s['url'] ) . '" class="sponsor-card sponsor-card--gold" target="_blank" rel="noopener noreferrer">';
            } else {
              echo '<div class="sponsor-card sponsor-card--gold">';
            }
          ?>
            <div class="sponsor-ribbon--gold" aria-hidden="true"></div>
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
          <?php echo $has_url ? '</a>' : '</div>'; ?>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- 2. SILVER PARTNER -->
      <?php if ( ! empty( $ranks['silver'] ) ) : ?>
      <section class="sponsor-section sponsor-section--silver">
        <div class="sponsor-section__heading">
          <span class="sponsor-section__en">SILVER PARTNER</span>
          <span class="sponsor-section__ja">シルバーパートナー</span>
        </div>
        <div class="sponsor-cards sponsor-cards--silver">
          <?php foreach ( $ranks['silver'] as $s ) :
            $has_url  = ! empty( $s['url'] );
            $has_logo = ! empty( $s['logo_url'] );
            if ( $has_url ) {
              echo '<a href="' . esc_url( $s['url'] ) . '" class="sponsor-card sponsor-card--silver" target="_blank" rel="noopener noreferrer">';
            } else {
              echo '<div class="sponsor-card sponsor-card--silver">';
            }
          ?>
            <div class="sponsor-ribbon--silver" aria-hidden="true"></div>
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
          <?php echo $has_url ? '</a>' : '</div>'; ?>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- 3. PARTNER A -->
      <?php if ( ! empty( $ranks['partner_a'] ) ) : ?>
      <section class="sponsor-section sponsor-section--partner-a">
        <div class="sponsor-section__heading">
          <span class="sponsor-section__en">PARTNER A</span>
          <span class="sponsor-section__ja">パートナーA</span>
        </div>
        <div class="sponsor-cards sponsor-cards--partner">
          <?php foreach ( $ranks['partner_a'] as $s ) :
            $has_url  = ! empty( $s['url'] );
            $has_logo = ! empty( $s['logo_url'] );
            if ( $has_url ) {
              echo '<a href="' . esc_url( $s['url'] ) . '" class="sponsor-card sponsor-card--partner-a" target="_blank" rel="noopener noreferrer">';
            } else {
              echo '<div class="sponsor-card sponsor-card--partner-a">';
            }
          ?>
            <div class="sponsor-ribbon--partner-a" aria-hidden="true"></div>
            <div class="sponsor-card__inner">
              <div class="sponsor-card__top-space sponsor-card__top-space--sm"></div>
              <div class="sponsor-card__logo-wrap sponsor-card__logo-wrap--sm">
                <?php if ( $has_logo ) : ?>
                  <img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php echo esc_attr( $s['name'] ); ?>" class="sponsor-card__logo-img">
                <?php else : ?>
                  <div class="sponsor-card__logo-placeholder"></div>
                <?php endif; ?>
              </div>
              <div class="sponsor-card__name-wrap">
                <p class="sponsor-card__name sponsor-card__name--sm"><?php echo esc_html( $s['name'] ); ?></p>
              </div>
            </div>
          <?php echo $has_url ? '</a>' : '</div>'; ?>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- 4. PARTNER B -->
      <?php if ( ! empty( $ranks['partner_b'] ) ) : ?>
      <section class="sponsor-section sponsor-section--partner-b">
        <div class="sponsor-section__heading">
          <span class="sponsor-section__en">PARTNER B</span>
          <span class="sponsor-section__ja">パートナーB</span>
        </div>
        <div class="sponsor-cards sponsor-cards--partner">
          <?php foreach ( $ranks['partner_b'] as $s ) :
            $has_url  = ! empty( $s['url'] );
            $has_logo = ! empty( $s['logo_url'] );
            if ( $has_url ) {
              echo '<a href="' . esc_url( $s['url'] ) . '" class="sponsor-card sponsor-card--partner-b" target="_blank" rel="noopener noreferrer">';
            } else {
              echo '<div class="sponsor-card sponsor-card--partner-b">';
            }
          ?>
            <div class="sponsor-ribbon--partner-b" aria-hidden="true"></div>
            <div class="sponsor-card__inner">
              <div class="sponsor-card__top-space sponsor-card__top-space--sm"></div>
              <div class="sponsor-card__logo-wrap sponsor-card__logo-wrap--sm">
                <?php if ( $has_logo ) : ?>
                  <img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php echo esc_attr( $s['name'] ); ?>" class="sponsor-card__logo-img">
                <?php else : ?>
                  <div class="sponsor-card__logo-placeholder"></div>
                <?php endif; ?>
              </div>
              <div class="sponsor-card__name-wrap">
                <p class="sponsor-card__name sponsor-card__name--sm"><?php echo esc_html( $s['name'] ); ?></p>
              </div>
            </div>
          <?php echo $has_url ? '</a>' : '</div>'; ?>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- 5. 共催 / 後援 / 提携 -->
      <?php if ( ! empty( $ranks['co_host'] ) ) : ?>
      <section class="sponsor-section sponsor-section--co-host">
        <div class="sponsor-section__heading">
          <span class="sponsor-section__en">CO-HOST / SUPPORTER</span>
          <span class="sponsor-section__ja">共催・後援・提携</span>
        </div>
        <div class="sponsor-cards sponsor-cards--co-host">
          <?php foreach ( $ranks['co_host'] as $s ) :
            $has_url  = ! empty( $s['url'] );
            $has_logo = ! empty( $s['logo_url'] );
            if ( $has_url ) {
              echo '<a href="' . esc_url( $s['url'] ) . '" class="sponsor-card sponsor-card--co-host" target="_blank" rel="noopener noreferrer">';
            } else {
              echo '<div class="sponsor-card sponsor-card--co-host">';
            }
          ?>
            <div class="sponsor-card__inner sponsor-card__inner--mini">
              <div class="sponsor-card__logo-wrap sponsor-card__logo-wrap--mini">
                <?php if ( $has_logo ) : ?>
                  <img src="<?php echo esc_url( $s['logo_url'] ); ?>" alt="<?php echo esc_attr( $s['name'] ); ?>" class="sponsor-card__logo-img">
                <?php else : ?>
                  <div class="sponsor-card__logo-placeholder"></div>
                <?php endif; ?>
              </div>
              <div class="sponsor-card__name-wrap">
                <p class="sponsor-card__name sponsor-card__name--mini"><?php echo esc_html( $s['name'] ); ?></p>
              </div>
            </div>
          <?php echo $has_url ? '</a>' : '</div>'; ?>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- 6. クラファン支援者 -->
      <?php if ( ! empty( $crowdfunding_names ) ) : ?>
      <section class="sponsor-section sponsor-section--crowd">
        <div class="sponsor-section__heading">
          <span class="sponsor-section__en">CROWDFUNDING SUPPORTERS</span>
          <span class="sponsor-section__ja">クラウドファンディング支援者の皆様</span>
        </div>
        <div class="sponsor-crowd-grid">
          <?php foreach ( $crowdfunding_names as $cname ) : ?>
            <span class="sponsor-crowd-item"><?php echo esc_html( $cname ); ?></span>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- スポンサー未登録時 -->
      <?php
      $has_any = ! empty( $ranks['gold'] ) || ! empty( $ranks['silver'] ) || ! empty( $ranks['partner_a'] ) || ! empty( $ranks['partner_b'] ) || ! empty( $ranks['co_host'] ) || ! empty( $crowdfunding_names );
      if ( ! $has_any ) : ?>
        <div class="sponsor-empty">
          <p><?php echo esc_html( get_multilang_text( '現在、スポンサー・パートナー情報を準備中です。', 'Sponsor and partner information is being prepared.' ) ); ?></p>
        </div>
      <?php endif; ?>

  </div><!-- /.sponsor-page-content -->
</main><!-- /#primary -->

<?php get_footer(); ?>
