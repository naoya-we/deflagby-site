<?php
/**
 * defragby-theme functions and definitions
 */

if ( ! function_exists( 'defragby_setup' ) ) :
  function defragby_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'defragby-theme', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails on posts and pages.
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
      'primary' => esc_html__( 'Primary Menu', 'defragby-theme' ),
      'footer'  => esc_html__( 'Footer Menu', 'defragby-theme' ),
    ) );

    // Switch default core markup for search form, comment form, and comments to output valid HTML5.
    add_theme_support( 'html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption',
      'style',
      'script',
    ) );
  }
endif;
add_action( 'after_setup_theme', 'defragby_setup' );

/* ==========================================================================
   カスタム投稿タイプ: team (チーム詳細ページ用)
   ========================================================================== */
function defragby_register_team_post_type() {
  register_post_type( 'team', array(
    'labels' => array(
      'name'               => 'チーム',
      'singular_name'      => 'チーム',
      'add_new'            => '新規追加',
      'add_new_item'       => '新規チームを追加',
      'edit_item'          => 'チームを編集',
      'view_item'          => 'チームを表示',
      'search_items'       => 'チームを検索',
      'not_found'          => 'チームが見つかりません',
      'not_found_in_trash' => 'ゴミ箱にチームはありません',
    ),
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'menu_icon'           => 'dashicons-shield',
    'menu_position'       => 27,
    'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
    'has_archive'         => false,
    'rewrite'             => array( 'slug' => 'teams', 'with_front' => false ),
    'show_in_rest'        => true,
  ) );

  // リライトルールの自動更新（1度だけ実行）
  if ( get_option( 'defragby_flush_rewrite_teams_v2' ) !== 'yes' ) {
    flush_rewrite_rules();
    update_option( 'defragby_flush_rewrite_teams_v2', 'yes' );
  }
}
add_action( 'init', 'defragby_register_team_post_type' );

/* ==========================================================================
   チームデータ保存時フック: detail_post_id の自動下書き生成
   ========================================================================== */
function defragby_auto_create_team_draft( $old_value, $new_value ) {
  if ( ! is_array( $new_value ) ) {
    return;
  }

  $updated = array();
  foreach ( $new_value as $team ) {
    $detail_post_id = isset( $team['detail_post_id'] ) ? intval( $team['detail_post_id'] ) : 0;
    $name_ja        = isset( $team['name_ja'] ) ? sanitize_text_field( $team['name_ja'] ) : '';
    $name_en        = isset( $team['name_en'] ) ? sanitize_title( $team['name_en'] ) : '';

    // IDが空、またはその投稿が存在しない場合は下書きを自動生成
    if ( empty( $detail_post_id ) || ! get_post( $detail_post_id ) ) {
      $post_id = wp_insert_post( array(
        'post_title'  => $name_ja,
        'post_name'   => $name_en,
        'post_status' => 'draft',
        'post_type'   => 'team',
      ) );
      if ( ! is_wp_error( $post_id ) && $post_id > 0 ) {
        $team['detail_post_id'] = $post_id;
      }
    } else {
      // 既存投稿が存在する場合もスラッグ・タイトルを最新に更新
      if ( ! empty( $name_en ) ) {
        wp_update_post( array(
          'ID'         => $detail_post_id,
          'post_title' => $name_ja,
          'post_name'  => $name_en,
        ) );
      }
    }

    $updated[] = $team;
  }

  // フック内でupdate_optionを呼ぶと無限ループになるため、再帰を防ぐ
  remove_action( 'update_option_defragby_teams_data', 'defragby_auto_create_team_draft', 10 );
  update_option( 'defragby_teams_data', $updated );
  add_action( 'update_option_defragby_teams_data', 'defragby_auto_create_team_draft', 10, 2 );

  // 保存時にリライトルールをflushして確実に新ページがアクセス可能にする
  flush_rewrite_rules();
}
add_action( 'update_option_defragby_teams_data', 'defragby_auto_create_team_draft', 10, 2 );

// 新規追加時（add_optionイベント）にも対応
function defragby_auto_create_team_draft_on_add( $option, $value ) {
  if ( $option === 'defragby_teams_data' ) {
    defragby_auto_create_team_draft( array(), $value );
  }
}
add_action( 'add_option', 'defragby_auto_create_team_draft_on_add', 10, 2 );

/* ==========================================================================
   会場・日程マッピングヘルパー（全テンプレートから参照可能）
   ========================================================================== */
function defragby_get_venue_map() {
  return array(
    '1031' => array(
      'date_ja'  => '10.31 [土]',
      'date_en'  => 'Oct. 31 [Sat]',
      'venue_ja' => '夢の島競技場',
      'venue_en' => 'Yumenoshima Stadium',
      'map_url'  => 'https://maps.app.goo.gl/2CuChSizBoh2A2Es9',
    ),
    '1102' => array(
      'date_ja'  => '11.02 [月]',
      'date_en'  => 'Nov. 2 [Mon]',
      'venue_ja' => '江戸川区陸上競技場',
      'venue_en' => 'Edogawa Athletic Stadium',
      'map_url'  => 'https://maps.app.goo.gl/JGxVW2owkhThFioA9',
    ),
    '1103' => array(
      'date_ja'  => '11.03 [火・祝]',
      'date_en'  => 'Nov. 3 [Tue/Holiday]',
      'venue_ja' => '秩父宮ラグビー場',
      'venue_en' => 'Chichibunomiya Rugby Stadium',
      'map_url'  => 'https://maps.app.goo.gl/bFpSrqX5wWcwK93H8',
    ),
  );
}

/**
 * 試合データから対応する Google Maps URL を取得するヘルパー関数
 */
function defragby_get_venue_map_url( $match ) {
  $venue_map = defragby_get_venue_map();
  $vd_key    = isset( $match['venue_date'] ) ? $match['venue_date'] : '';

  if ( isset( $venue_map[ $vd_key ]['map_url'] ) && ! empty( $venue_map[ $vd_key ]['map_url'] ) ) {
    return $venue_map[ $vd_key ]['map_url'];
  }

  $venue_name = isset( $match['venue_ja'] ) ? $match['venue_ja'] : ( isset( $match['venue'] ) ? $match['venue'] : '' );
  if ( strpos( $venue_name, '夢の島' ) !== false ) return 'https://maps.app.goo.gl/2CuChSizBoh2A2Es9';
  if ( strpos( $venue_name, '江戸川' ) !== false ) return 'https://maps.app.goo.gl/JGxVW2owkhThFioA9';
  if ( strpos( $venue_name, '秩父宮' ) !== false ) return 'https://maps.app.goo.gl/bFpSrqX5wWcwK93H8';

  return '';
}

/* ==========================================================================
   グループ順位自動計算: 試合結果保存時に defragby_pool_standings を更新
   ========================================================================== */
function defragby_calculate_pool_standings( $old_value, $new_value ) {
  if ( ! is_array( $new_value ) ) return;

  $teams_data = get_option( 'defragby_teams_data', array() );

  // チーム日本語名 → プールのマッピングを構築
  $team_pool_map = array();
  $team_en_map   = array();
  foreach ( $teams_data as $team ) {
    $name = isset( $team['name_ja'] ) ? $team['name_ja'] : '';
    $pool = isset( $team['pool'] )    ? $team['pool']    : '';
    $name_en = isset( $team['name_en'] ) ? $team['name_en'] : $name;
    if ( $name && $pool ) {
      $team_pool_map[ $name ] = $pool;
      $team_en_map[ $name ]   = $name_en;
    }
  }

  // 全登録チームのスタンディングを 0 で初期化
  $standings = array();
  foreach ( $teams_data as $team ) {
    $pool    = isset( $team['pool'] )    ? $team['pool']    : '';
    $name    = isset( $team['name_ja'] ) ? $team['name_ja'] : '';
    $name_en = isset( $team['name_en'] ) ? $team['name_en'] : $name;
    if ( $pool && $name && ! isset( $standings[ $pool ][ $name ] ) ) {
      $standings[ $pool ][ $name ] = array(
        'name_en' => $name_en,
        'p' => 0, 'w' => 0, 'd' => 0, 'l' => 0,
        'gf' => 0, 'ga' => 0, 'pts' => 0,
      );
    }
  }

  // グループ戦の試合結果のみ集計
  foreach ( $new_value as $match ) {
    $round = isset( $match['round'] ) ? $match['round'] : 'group';
    if ( $round !== 'group' ) continue;

    $score_a = ( isset( $match['score_a'] ) && $match['score_a'] !== '' ) ? intval( $match['score_a'] ) : null;
    $score_b = ( isset( $match['score_b'] ) && $match['score_b'] !== '' ) ? intval( $match['score_b'] ) : null;
    if ( $score_a === null || $score_b === null ) continue;

    $team_a = isset( $match['team_a_ja'] ) ? $match['team_a_ja'] : '';
    $team_b = isset( $match['team_b_ja'] ) ? $match['team_b_ja'] : '';
    if ( ! $team_a || ! $team_b ) continue;

    $pool_a = ! empty( $match['team_a_pool'] ) ? $match['team_a_pool'] : ( isset( $team_pool_map[ $team_a ] ) ? $team_pool_map[ $team_a ] : null );
    $pool_b = ! empty( $match['team_b_pool'] ) ? $match['team_b_pool'] : ( isset( $team_pool_map[ $team_b ] ) ? $team_pool_map[ $team_b ] : null );
    if ( ! $pool_a || $pool_a !== $pool_b ) continue; // 同プール同士の試合のみ

    $pool = $pool_a;
    if ( ! isset( $standings[ $pool ][ $team_a ] ) ) {
      $standings[ $pool ][ $team_a ] = array('name_en'=>isset($team_en_map[$team_a])?$team_en_map[$team_a]:$team_a,'p'=>0,'w'=>0,'d'=>0,'l'=>0,'gf'=>0,'ga'=>0,'pts'=>0);
    }
    if ( ! isset( $standings[ $pool ][ $team_b ] ) ) {
      $standings[ $pool ][ $team_b ] = array('name_en'=>isset($team_en_map[$team_b])?$team_en_map[$team_b]:$team_b,'p'=>0,'w'=>0,'d'=>0,'l'=>0,'gf'=>0,'ga'=>0,'pts'=>0);
    }

    $standings[$pool][$team_a]['p']  += 1;
    $standings[$pool][$team_b]['p']  += 1;
    $standings[$pool][$team_a]['gf'] += $score_a;
    $standings[$pool][$team_a]['ga'] += $score_b;
    $standings[$pool][$team_b]['gf'] += $score_b;
    $standings[$pool][$team_b]['ga'] += $score_a;

    if ( $score_a > $score_b ) {
      $standings[$pool][$team_a]['w'] += 1; $standings[$pool][$team_a]['pts'] += 3;
      $standings[$pool][$team_b]['l'] += 1;
    } elseif ( $score_a < $score_b ) {
      $standings[$pool][$team_b]['w'] += 1; $standings[$pool][$team_b]['pts'] += 3;
      $standings[$pool][$team_a]['l'] += 1;
    } else {
      $standings[$pool][$team_a]['d'] += 1; $standings[$pool][$team_a]['pts'] += 1;
      $standings[$pool][$team_b]['d'] += 1; $standings[$pool][$team_b]['pts'] += 1;
    }
  }

  // 各プール内を 勝ち点→得失差→得点 でソート
  foreach ( $standings as $pk => &$pd ) {
    uasort( $pd, function( $a, $b ) {
      if ( $b['pts'] !== $a['pts'] ) return $b['pts'] - $a['pts'];
      $gd = ($b['gf'] - $b['ga']) - ($a['gf'] - $a['ga']);
      return $gd !== 0 ? $gd : $b['gf'] - $a['gf'];
    });
  }
  unset( $pd );

  update_option( 'defragby_pool_standings', $standings );
  return $standings;
}
add_action( 'update_option_defragby_matches_data', 'defragby_calculate_pool_standings', 20, 2 );

/* ==========================================================================
   グループ順位取得ヘルパー（データが存在しないか古い場合は再計算）
   ========================================================================== */
function defragby_get_pool_standings() {
  $standings = get_option( 'defragby_pool_standings', array() );
  if ( empty( $standings ) ) {
    $matches   = get_option( 'defragby_matches_data', array() );
    $standings = defragby_calculate_pool_standings( null, $matches );
  }
  return $standings;
}

/* ==========================================================================
   チームアイコン（国旗/画像）生成ヘルパー
   ========================================================================== */
function defragby_get_team_icon_html( $team_name_ja, $team_name_en = '' ) {
  $teams_data = get_option( 'defragby_teams_data', array() );
  $img_url = '';
  foreach ( $teams_data as $team ) {
    $t_ja = isset($team['name_ja']) ? $team['name_ja'] : '';
    $t_en = isset($team['name_en']) ? $team['name_en'] : '';
    if ( ( $t_ja && $t_ja === $team_name_ja ) || ( $t_en && !empty($team_name_en) && strcasecmp($t_en, $team_name_en) === 0 ) ) {
      if ( ! empty( $team['image_url'] ) ) {
        $img_url = esc_url( $team['image_url'] );
        break;
      }
    }
  }

  if ( ! empty( $img_url ) ) {
    return '<img src="' . $img_url . '" alt="' . esc_attr($team_name_ja) . '" class="team-flag-img" style="width: 52px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(0,0,0,0.12); box-shadow: 0 2px 4px rgba(0,0,0,0.08); display: inline-block; vertical-align: middle;">';
  }

  // 画像未設定時のフォールバック国旗
  $name = mb_strtolower( $team_name_ja . ' ' . $team_name_en );
  if ( strpos($name, '日本') !== false || strpos($name, 'japan') !== false ) return '🇯🇵';
  if ( strpos($name, 'ニュージー') !== false || strpos($name, 'new zealand') !== false ) return '🇳🇿';
  if ( strpos($name, 'オーストラリア') !== false || strpos($name, 'australia') !== false ) return '🇦🇺';
  if ( strpos($name, '南アフリカ') !== false || strpos($name, 'south africa') !== false ) return '🇿🇦';
  if ( strpos($name, 'フランス') !== false || strpos($name, 'france') !== false ) return '🇫🇷';
  if ( strpos($name, 'フィジー') !== false || strpos($name, 'fiji') !== false ) return '🇫🇯';
  if ( strpos($name, 'ウェールズ') !== false || strpos($name, 'wales') !== false ) {
    return '<img src="https://flagcdn.com/w80/gb-wls.png" alt="Wales" class="team-flag-img" style="width: 52px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(0,0,0,0.12); display: inline-block; vertical-align: middle;">';
  }
  if ( strpos($name, 'イングランド') !== false || strpos($name, 'england') !== false ) {
    return '<img src="https://flagcdn.com/w80/gb-eng.png" alt="England" class="team-flag-img" style="width: 52px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(0,0,0,0.12); display: inline-block; vertical-align: middle;">';
  }
  if ( strpos($name, 'スコットランド') !== false || strpos($name, 'scotland') !== false ) {
    return '<img src="https://flagcdn.com/w80/gb-sct.png" alt="Scotland" class="team-flag-img" style="width: 52px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid rgba(0,0,0,0.12); display: inline-block; vertical-align: middle;">';
  }
  return '🏳️';
}

/**
 * Enqueue scripts and styles.
 */
function defragby_scripts() {
  // Google Fonts (Noto Sans JP and Oswald)
  wp_enqueue_style( 'defragby-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&family=Oswald:wght@500;600;700&display=swap', array(), null );

  // Main stylesheet
  wp_enqueue_style( 'defragby-style', get_stylesheet_uri(), array(), '8.0.0' );

  // Theme components & layout styles
  wp_enqueue_style( 'defragby-main', get_template_directory_uri() . '/assets/css/main.css', array( 'defragby-style' ), '8.0.0' );

  // FontAwesome for sports/social icons
  wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css', array(), '6.5.1' );

  // Main JavaScript
  wp_enqueue_script( 'defragby-main', get_template_directory_uri() . '/assets/js/main.js', array(), '6.0.0', true );

  // Countdown timer script only on front page
  if ( is_front_page() ) {
    wp_enqueue_script( 'defragby-countdown', get_template_directory_uri() . '/assets/js/countdown.js', array(), '6.0.0', true );
  }
}
add_action( 'wp_enqueue_scripts', 'defragby_scripts' );

/**
 * Add custom fields or settings helper if needed for multi-language
 * Since this is visual-first, we'll implement simple helpers
 */
function get_multilang_text($ja, $en) {
  $lang = isset($_GET['lang']) ? $_GET['lang'] : 'ja';
  return ($lang === 'en') ? $en : $ja;
}

/* ==========================================================================
   20. FAVICON — サイトロゴ画像をファビコンとしても使用
   ========================================================================== */
function defragby_output_favicon() {
  $logo_url = get_option( 'defragby_logo_image_url', '' );
  if ( ! empty( $logo_url ) ) {
    echo '<link rel="icon" href="' . esc_url( $logo_url ) . '" type="image/jpeg">' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url( $logo_url ) . '">' . "\n";
  }
}
add_action( 'wp_head', 'defragby_output_favicon', 1 );

/* ==========================================================================
   21. DASHBOARD MATCH & TEAM SETTINGS PAGES (ADMIN ONLY)
   ========================================================================== */

// Load WordPress media assets in admin pages
function defragby_admin_media_assets( $hook ) {
  wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'defragby_admin_media_assets' );

// Register Logo, Match & Team settings menu
function defragby_match_settings_menu() {
  add_menu_page(
    'デフラグビー設定',
    'デフラグビー設定',
    'manage_options',
    'defragby-logo-settings',
    'defragby_logo_settings_page_html',
    'dashicons-admin-appearance',
    24
  );

  add_menu_page(
    '試合日程・結果設定',
    '試合日程・結果設定',
    'manage_options',
    'defragby-match-settings',
    'defragby_match_settings_page_html',
    'dashicons-clipboard',
    25
  );

  add_menu_page(
    'チーム情報設定',
    'チーム情報設定',
    'manage_options',
    'defragby-team-settings',
    'defragby_team_settings_page_html',
    'dashicons-groups',
    26
  );

  add_menu_page(
    'FAQ（よくある質問）設定',
    'FAQ設定',
    'manage_options',
    'defragby-faq-settings',
    'defragby_faq_settings_page_html',
    'dashicons-editor-help',
    27
  );

  add_menu_page(
    'スポンサー情報設定',
    'スポンサー情報設定',
    'manage_options',
    'defragby-sponsor-settings',
    'defragby_sponsor_settings_page_html',
    'dashicons-star-filled',
    28
  );
}
add_action( 'admin_menu', 'defragby_match_settings_menu' );

// Register logo, matches, teams, live options, and FAQ
function defragby_register_settings() {
  register_setting( 'defragby-match-group', 'defragby_matches_data' );
  register_setting( 'defragby-match-group', 'defragby_live_active' );
  register_setting( 'defragby-match-group', 'defragby_live_video_id' );
  register_setting( 'defragby-match-group', 'defragby_youtube_channel_url' );
  register_setting( 'defragby-match-group', 'defragby_youtube_api_key' );
  register_setting( 'defragby-team-group',   'defragby_teams_data' );
  register_setting( 'defragby-faq-group',    'defragby_faq_data' );
  register_setting( 'defragby-sponsor-group', 'defragby_sponsors_data' );
}
add_action( 'admin_init', 'defragby_register_settings' );

/* ── Logo Settings Screen ── */
function defragby_logo_settings_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  $logo_url = get_option( 'defragby_logo_image_url', '' );
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( 'サイトロゴ設定 — サイトロゴ' ); ?></h1>
    <p>ヘッダーに表示するサイトロゴ画像を設定します。変更するとファビコンも同時に更新されます。</p>

    <form action="options.php" method="post">
      <?php settings_fields( 'defragby-logo-group' ); ?>

      <table class="form-table">
        <tr>
          <th scope="row">サイトロゴ画像</th>
          <td>
            <div style="display:flex; gap:16px; align-items:flex-start; flex-wrap:wrap;">
              <?php if ( ! empty( $logo_url ) ) : ?>
                <div>
                  <img src="<?php echo esc_url( $logo_url ); ?>" alt="現在のロゴ" style="max-height:80px; border:1px solid #ddd; border-radius:4px; background:#fff; padding:4px;">
                  <p style="font-size:12px; color:#666; margin-top:4px;">現在設定中のロゴ</p>
                </div>
              <?php endif; ?>
              <div>
                <input type="text"
                  name="defragby_logo_image_url"
                  id="defragby_logo_image_url"
                  value="<?php echo esc_url( $logo_url ); ?>"
                  placeholder="画像URLを入力、またはメディアから選択"
                  style="width:400px;"
                  readonly />
                <br><br>
                <button type="button" class="button" id="defragby-select-logo-btn">
                  メディアライブラリから選択
                </button>
                <?php if ( ! empty( $logo_url ) ) : ?>
                  <button type="button" class="button button-link-delete" id="defragby-remove-logo-btn" style="margin-left:8px; color:#d63638;">
                    ロゴを削除
                  </button>
                <?php endif; ?>
                <p class="description" style="margin-top:8px;">JPG / PNG / SVG 推奨。高さは最大60pxで表示されます。</p>
              </div>
            </div>
          </td>
        </tr>
      </table>

      <?php submit_button( 'ロゴ設定を保存する' ); ?>
    </form>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var mediaFrame;
    var selectBtn  = document.getElementById('defragby-select-logo-btn');
    var removeBtn  = document.getElementById('defragby-remove-logo-btn');
    var inputField = document.getElementById('defragby_logo_image_url');

    if ( selectBtn ) {
      selectBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if ( mediaFrame ) {
          mediaFrame.open();
          return;
        }
        mediaFrame = wp.media({
          title: 'サイトロゴを選択',
          button: { text: 'この画像を使用する' },
          multiple: false,
          library: { type: 'image' }
        });
        mediaFrame.on('select', function() {
          var attachment = mediaFrame.state().get('selection').first().toJSON();
          inputField.value = attachment.url;
        });
        mediaFrame.open();
      });
    }

    if ( removeBtn ) {
      removeBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if ( confirm('ロゴ設定を削除してもよいですか？') ) {
          inputField.value = '';
          document.querySelector('form').submit();
        }
      });
    }
  });
  </script>
  <?php
}

/* ── Custom TEAM Settings Screen ── */
function defragby_team_settings_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  
  $teams = get_option( 'defragby_teams_data', array() );
  if ( ! is_array( $teams ) ) {
    $teams = array();
  }
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( 'チーム情報設定 (管理者専用)' ); ?></h1>
    <p>出場チームを登録・管理します。「チーム画像（メディアライブラリから選択）」および「紹介URL（外部YouTubeリンク等）」を紐付け可能です。ここで登録したチーム名が、試合日程設定画面でプルダウン選択できるようになります。</p>
    
    <form action="options.php" method="post">
      <?php
      settings_fields( 'defragby-team-group' );
      do_settings_sections( 'defragby-team-group' );
      ?>
      
      <table class="wp-list-table widefat fixed striped table-view-list" id="teams-settings-table" style="margin-top: 20px;">
        <thead>
          <tr>
            <th>チーム名 (日本語・必須)</th>
            <th>チーム名 (英語・必須)</th>
            <th style="width: 90px;">性別 (必須)</th>
            <th style="width: 130px;">プール (必須)</th>
            <th>チーム画像 (必須)</th>
            <th>詳細ページ</th>
            <th style="width: 70px;">操作</th>
          </tr>
        </thead>
        <tbody id="teams-tbody">
          <?php
          $index = 0;
          if ( ! empty( $teams ) ) {
            foreach ( $teams as $team ) {
              defragby_render_team_row( $index, $team );
              $index++;
            }
          } else {
            defragby_render_team_row( 0, array() );
            $index = 1;
          }
          ?>
        </tbody>
      </table>
      
      <div style="margin-top: 15px;">
        <button type="button" class="button button-secondary" id="add-team-row">＋ チームを追加する</button>
      </div>

      <?php submit_button( 'チーム情報を変更保存する' ); ?>
    </form>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var tbody = document.getElementById('teams-tbody');
    var addButton = document.getElementById('add-team-row');
    var rowIndex = <?php echo $index; ?>;

    // Row adder
    addButton.addEventListener('click', function() {
      var rowHtml = `
        <tr class="team-row">
          <td><input type="text" name="defragby_teams_data[${rowIndex}][name_ja]" value="" placeholder="日本" style="width:100%;" required /></td>
          <td><input type="text" name="defragby_teams_data[${rowIndex}][name_en]" value="" placeholder="JAPAN" style="width:100%;" required /></td>
          <td>
            <select name="defragby_teams_data[${rowIndex}][gender]" class="team-gender-select" style="width:100%;" required>
              <option value="men">男子</option>
              <option value="women">女子</option>
            </select>
          </td>
          <td>
            <select name="defragby_teams_data[${rowIndex}][pool]" class="team-pool-select" style="width:100%;" required>
              <option value="men-a">男子プールA</option>
              <option value="men-b">男子プールB</option>
              <option value="women-a" style="display:none;">女子プールA</option>
            </select>
          </td>
          <td>
            <div style="display:flex; gap:8px; align-items:center;">
              <input type="text" name="defragby_teams_data[${rowIndex}][image_url]" id="team_image_${rowIndex}" value="" placeholder="画像URL" style="width:70%;" required readonly />
              <button type="button" class="button choose-image-btn" data-target="team_image_${rowIndex}">画像を選択</button>
            </div>
          </td>
          <td style="font-size:0.85em; color:#999;">保存後に生成</td>
          <td><button type="button" class="button button-link-delete delete-team-row" style="color:#d63638;">削除</button></td>
        </tr>
      `;
      var tempDiv = document.createElement('tbody');
      tempDiv.innerHTML = rowHtml;
      var newRow = tempDiv.firstElementChild;
      tbody.appendChild(newRow);
      rowIndex++;
    });

    // Delete row
    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('delete-team-row')) {
        var row = e.target.closest('tr');
        if (tbody.querySelectorAll('.team-row').length > 1) {
          row.remove();
        } else {
          alert('これ以上削除できません。少なくとも1つのチーム行が必要です。');
        }
      }
    });

    // Gender → Pool フィルタリング
    tbody.addEventListener('change', function(e) {
      if (e.target && e.target.classList.contains('team-gender-select')) {
        var gender = e.target.value;
        var row = e.target.closest('tr');
        var poolSel = row ? row.querySelector('.team-pool-select') : null;
        if (!poolSel) return;
        Array.from(poolSel.options).forEach(function(opt) {
          if (gender === 'men') {
            opt.style.display = opt.value === 'women-a' ? 'none' : '';
          } else {
            opt.style.display = (opt.value !== 'women-a') ? 'none' : '';
          }
        });
        var firstVisible = Array.from(poolSel.options).find(function(o) { return o.style.display !== 'none'; });
        if (firstVisible) poolSel.value = firstVisible.value;
      }
    });

    // Media Modal logic
    var mediaUploader;
    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('choose-image-btn')) {
        e.preventDefault();
        var targetInputId = e.target.getAttribute('data-target');
        var targetInput = document.getElementById(targetInputId);
        
        if (mediaUploader) {
          mediaUploader.off('select');
        }
        
        mediaUploader = wp.media({
          title: 'チーム画像を選択',
          button: { text: '画像をセットする' },
          multiple: false
        });
        
        mediaUploader.on('select', function() {
          var attachment = mediaUploader.state().get('selection').first().toJSON();
          targetInput.value = attachment.url;
        });
        
        mediaUploader.open();
      }
    });
  });
  </script>
  <?php
}

function defragby_render_team_row( $index, $team ) {
  $name_ja        = isset( $team['name_ja'] )        ? esc_attr( $team['name_ja'] )        : '';
  $name_en        = isset( $team['name_en'] )        ? esc_attr( $team['name_en'] )        : '';
  $image_url      = isset( $team['image_url'] )      ? esc_url( $team['image_url'] )       : '';
  $detail_post_id = isset( $team['detail_post_id'] ) ? intval( $team['detail_post_id'] )   : 0;
  $gender         = isset( $team['gender'] )         ? esc_attr( $team['gender'] )         : 'men';
  $pool           = isset( $team['pool'] )           ? esc_attr( $team['pool'] )           : 'men-a';

  // 詳細ページのステータス表示
  $post_status_label = '';
  if ( $detail_post_id > 0 ) {
    $status = get_post_status( $detail_post_id );
    if ( $status === 'publish' ) {
      $post_status_label = '<span style="color:#00a32a; font-weight:bold;">● 公開中</span>';
    } elseif ( $status === 'draft' ) {
      $post_status_label = '<span style="color:#f0a500;">● 下書き</span>';
    } else {
      $post_status_label = '<span style="color:#999;">● ' . esc_html( $status ) . '</span>';
    }
    $edit_url = admin_url( 'post.php?post=' . $detail_post_id . '&action=edit' );
    $post_status_label .= ' <a href="' . esc_url( $edit_url ) . '" target="_blank" style="font-size:0.85em;">(編集)</a>';
  } else {
    $post_status_label = '<span style="color:#999;">未生成</span>';
  }
  ?>
  <tr class="team-row">
    <input type="hidden" name="defragby_teams_data[<?php echo $index; ?>][detail_post_id]" value="<?php echo $detail_post_id; ?>" />
    <td><input type="text" name="defragby_teams_data[<?php echo $index; ?>][name_ja]" value="<?php echo $name_ja; ?>" placeholder="日本" style="width:100%;" required /></td>
    <td><input type="text" name="defragby_teams_data[<?php echo $index; ?>][name_en]" value="<?php echo $name_en; ?>" placeholder="JAPAN" style="width:100%;" required /></td>
    <td>
      <select name="defragby_teams_data[<?php echo $index; ?>][gender]" class="team-gender-select" style="width:100%;" required>
        <option value="men" <?php selected($gender,'men'); ?>>男子</option>
        <option value="women" <?php selected($gender,'women'); ?>>女子</option>
      </select>
    </td>
    <td>
      <select name="defragby_teams_data[<?php echo $index; ?>][pool]" class="team-pool-select" style="width:100%;" required>
        <option value="men-a" <?php selected($pool,'men-a'); echo ($gender==='women'?' style="display:none;"':''); ?>>男子プールA</option>
        <option value="men-b" <?php selected($pool,'men-b'); echo ($gender==='women'?' style="display:none;"':''); ?>>男子プールB</option>
        <option value="women-a" <?php selected($pool,'women-a'); echo ($gender==='men'?' style="display:none;"':''); ?>>女子プールA</option>
      </select>
    </td>
    <td>
      <div style="display:flex; gap:8px; align-items:center;">
        <input type="text" name="defragby_teams_data[<?php echo $index; ?>][image_url]" id="team_image_<?php echo $index; ?>" value="<?php echo $image_url; ?>" placeholder="画像URL" style="width:70%;" required readonly />
        <button type="button" class="button choose-image-btn" data-target="team_image_<?php echo $index; ?>">画像を選択</button>
      </div>
    </td>
    <td style="font-size:0.85em; white-space:nowrap;"><?php echo $post_status_label; ?></td>
    <td><button type="button" class="button button-link-delete delete-team-row" style="color:#d63638;">削除</button></td>
  </tr>
  <?php
}

/* ── Custom MATCH Settings Screen with Selector dropdowns ── */
function defragby_match_settings_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }
  
  $matches = get_option( 'defragby_matches_data', array() );
  if ( ! is_array( $matches ) ) {
    $matches = array();
  }

  // Get registered teams list for selectors
  $teams = get_option( 'defragby_teams_data', array() );
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( '試合日程・結果設定 (管理者専用)' ); ?></h1>
    <p>試合日程、チーム、スコア結果、YouTube動画リンクを設定します。対戦チームは「チーム情報設定」で登録したチームからプルダウン選択できます。</p>
    
    <form action="options.php" method="post">
      <?php
      settings_fields( 'defragby-match-group' );
      do_settings_sections( 'defragby-match-group' );
      
      $live_active = get_option( 'defragby_live_active', '' );
      $live_video_id = get_option( 'defragby_live_video_id', '' );
      ?>
      
      <!-- Live streaming / YouTube channel settings panel -->
      <div class="card" style="margin-top: 20px; padding: 15px 20px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
        <h2 style="margin-top: 0;">📡 YouTube ライブ配信・チャンネル設定</h2>
        <p class="description">YouTubeチャンネルURLとAPI設定を行います。ライブ配信中はサイト全体にライブバナーを表示できます。</p>
        <table class="form-table" role="presentation" style="margin-top: 10px;">
          <tbody>
            <tr>
              <th scope="row" style="width: 240px; padding: 10px 0;"><label for="defragby_youtube_channel_url">YouTubeチャンネルURL</label></th>
              <td style="padding: 10px 0;">
                <input type="url" name="defragby_youtube_channel_url" id="defragby_youtube_channel_url" value="<?php echo esc_attr( get_option('defragby_youtube_channel_url','') ); ?>" placeholder="https://www.youtube.com/@channel" class="large-text" />
                <p class="description">公式YouTubeチャンネルのURL。全ページの「LIVE STREAM」ボタンのリンク先として使用されます。</p>
              </td>
            </tr>
            <tr>
              <th scope="row" style="width: 240px; padding: 10px 0;"><label for="defragby_youtube_api_key">YouTube Data API キー</label></th>
              <td style="padding: 10px 0;">
                <input type="text" name="defragby_youtube_api_key" id="defragby_youtube_api_key" value="<?php echo esc_attr( get_option('defragby_youtube_api_key','') ); ?>" placeholder="AIzaSy..." class="regular-text" />
                <p class="description">Google Cloud Console → YouTube Data API v3 で取得したAPIキー。ライブ状態の自動検知に使用します。<br>未設定の場合は下記の手動チェックでライブ状態を切り替えてください。</p>
              </td>
            </tr>
            <tr>
              <th scope="row" style="width: 240px; padding: 10px 0;"><label for="defragby_live_active">現在ライブ配信中か？（手動）</label></th>
              <td style="padding: 10px 0;">
                <input type="checkbox" name="defragby_live_active" id="defragby_live_active" value="on" <?php checked( $live_active, 'on' ); ?> />
                <span class="description">チェックを入れると、トップページの動画が「ライブ配信中」になり中継動画に切り替わります。</span>
              </td>
            </tr>
            <tr>
              <th scope="row" style="width: 240px; padding: 10px 0;"><label for="defragby_live_video_id">ライブ配信用の動画ID</label></th>
              <td style="padding: 10px 0;">
                <input type="text" name="defragby_live_video_id" id="defragby_live_video_id" value="<?php echo esc_attr( $live_video_id ); ?>" placeholder="udls0GxvWgM" class="regular-text" />
                <p class="description">
                  配信のYouTube動画ID（URL末尾の <code>v=XXXX</code> 部分）を入力してください。<br>
                  空欄の場合は、自動的に公式チャンネルの生配信枠が読み込まれます。
                </p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <table class="wp-list-table widefat fixed striped table-view-list" id="matches-settings-table" style="margin-top: 20px;">
        <thead>
          <tr>
            <th style="width: 185px;">日程・会場 (任意)</th>
            <th style="width: 70px;">時間 (任意)</th>
            <th style="width: 100px;">区分</th>
            <th>対戦チームA (必須)</th>
            <th>対戦チームB (必須)</th>
            <th style="width: 70px;">スコアA</th>
            <th style="width: 70px;">スコアB</th>
            <th style="width: 70px;">操作</th>
          </tr>
        </thead>
        <tbody id="matches-tbody">
          <?php
          $index = 0;
          if ( ! empty( $matches ) ) {
            foreach ( $matches as $match ) {
              defragby_render_match_row_with_dropdown( $index, $match, $teams );
              $index++;
            }
          } else {
            defragby_render_match_row_with_dropdown( 0, array(), $teams );
            $index = 1;
          }
          ?>
        </tbody>
      </table>
      
      <div style="margin-top: 15px;">
        <button type="button" class="button button-secondary" id="add-match-row">＋ 試合を追加する</button>
      </div>

      <?php submit_button( '試合日程・結果を変更保存する' ); ?>
    </form>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var tbody = document.getElementById('matches-tbody');
    var addButton = document.getElementById('add-match-row');
    var rowIndex = <?php echo $index; ?>;

    // Pool label map for helper
    var poolNames = { 'men-a': '男子・プールA', 'men-b': '男子・プールB', 'women-a': '女子・プールA' };

    // Load options markup dynamically
    var teamsSelectOptions = `
      <option value="">-- チームを選択 --</option>
      <?php foreach ($teams as $t_row) : 
        $g_label = (isset($t_row['gender']) && $t_row['gender'] === 'women') ? '女子' : '男子';
        $p_code  = isset($t_row['pool']) ? $t_row['pool'] : '';
        $p_label = ($p_code === 'men-b') ? 'プールB' : 'プールA';
        $prefix  = "【{$g_label}・{$p_label}】";
        $opt_label = $prefix . ' ' . $t_row['name_ja'] . ' / ' . $t_row['name_en'];
      ?>
        <option value="<?php echo esc_attr($t_row['name_ja']); ?>" data-en="<?php echo esc_attr($t_row['name_en']); ?>" data-pool="<?php echo esc_attr($p_code); ?>"><?php echo esc_html($opt_label); ?></option>
      <?php endforeach; ?>
    `;

    addButton.addEventListener('click', function() {
      var rowHtml = `
        <tr class="match-row">
          <td>
            <select name="defragby_matches_data[${rowIndex}][venue_date]" style="width:100%;">
              <option value="">-- 選択 --</option>
              <option value="1031">10.31 [土]　夢の島競技場</option>
              <option value="1102">11.02 [月]　江戸川区陸上競技場</option>
              <option value="1103">11.03 [火・祝]　秩父宮ラグビー場</option>
            </select>
          </td>
          <td><input type="text" name="defragby_matches_data[${rowIndex}][time]" value="" placeholder="10:00" style="width:100%;" /></td>
          <td>
            <select name="defragby_matches_data[${rowIndex}][round]" style="width:100%;">
              <option value="group">グループ戦</option>
              <option value="sf">準決勝</option>
              <option value="final">決勝</option>
            </select>
          </td>
          <td>
            <select class="team-selector" data-target-ja="team_a_ja_${rowIndex}" data-target-en="team_a_en_${rowIndex}" data-target-pool="team_a_pool_${rowIndex}" style="width:100%;" required>
              ${teamsSelectOptions}
            </select>
            <input type="hidden" name="defragby_matches_data[${rowIndex}][team_a_ja]" id="team_a_ja_${rowIndex}" value="" />
            <input type="hidden" name="defragby_matches_data[${rowIndex}][team_a_en]" id="team_a_en_${rowIndex}" value="" />
            <input type="hidden" name="defragby_matches_data[${rowIndex}][team_a_pool]" id="team_a_pool_${rowIndex}" value="" />
          </td>
          <td>
            <select class="team-selector" data-target-ja="team_b_ja_${rowIndex}" data-target-en="team_b_en_${rowIndex}" data-target-pool="team_b_pool_${rowIndex}" style="width:100%;" required>
              ${teamsSelectOptions}
            </select>
            <input type="hidden" name="defragby_matches_data[${rowIndex}][team_b_ja]" id="team_b_ja_${rowIndex}" value="" />
            <input type="hidden" name="defragby_matches_data[${rowIndex}][team_b_en]" id="team_b_en_${rowIndex}" value="" />
            <input type="hidden" name="defragby_matches_data[${rowIndex}][team_b_pool]" id="team_b_pool_${rowIndex}" value="" />
          </td>
          <td><input type="number" name="defragby_matches_data[${rowIndex}][score_a]" value="" style="width:100%;" min="0" /></td>
          <td><input type="number" name="defragby_matches_data[${rowIndex}][score_b]" value="" style="width:100%;" min="0" /></td>
          <td><button type="button" class="button button-link-delete delete-match-row" style="color:#d63638;">削除</button></td>
        </tr>
      `;
      var tempDiv = document.createElement('tbody');
      tempDiv.innerHTML = rowHtml;
      tbody.appendChild(tempDiv.firstElementChild);
      rowIndex++;
    });

    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('delete-match-row')) {
        var row = e.target.closest('tr');
        if (tbody.querySelectorAll('.match-row').length > 1) {
          row.remove();
        } else {
          alert('これ以上削除できません。少なくとも1つの試合行が必要です。');
        }
      }
    });

    // Populate hidden inputs when dropdown changes
    tbody.addEventListener('change', function(e) {
      if (e.target && e.target.classList.contains('team-selector')) {
        var select = e.target;
        var selectedOpt = select.options[select.selectedIndex];
        var targetJa   = document.getElementById(select.getAttribute('data-target-ja'));
        var targetEn   = document.getElementById(select.getAttribute('data-target-en'));
        var targetPool = document.getElementById(select.getAttribute('data-target-pool'));
        
        if (selectedOpt && selectedOpt.value !== '') {
          if (targetJa) targetJa.value = selectedOpt.value;
          if (targetEn) targetEn.value = selectedOpt.getAttribute('data-en');
          if (targetPool) targetPool.value = selectedOpt.getAttribute('data-pool');
        } else {
          if (targetJa) targetJa.value = '';
          if (targetEn) targetEn.value = '';
          if (targetPool) targetPool.value = '';
        }
      }
    });
  });
  </script>
  <?php
}

function defragby_render_match_row_with_dropdown( $index, $match, $teams ) {
  $venue_date  = isset( $match['venue_date'] )  ? esc_attr( $match['venue_date'] )  : '';
  $time        = isset( $match['time'] )        ? esc_attr( $match['time'] )        : '';
  $round       = isset( $match['round'] )       ? esc_attr( $match['round'] )       : 'group';
  $team_a_ja   = isset( $match['team_a_ja'] )   ? esc_attr( $match['team_a_ja'] )   : '';
  $team_a_en   = isset( $match['team_a_en'] )   ? esc_attr( $match['team_a_en'] )   : '';
  $team_a_pool = isset( $match['team_a_pool'] ) ? esc_attr( $match['team_a_pool'] ) : '';
  $team_b_ja   = isset( $match['team_b_ja'] )   ? esc_attr( $match['team_b_ja'] )   : '';
  $team_b_en   = isset( $match['team_b_en'] )   ? esc_attr( $match['team_b_en'] )   : '';
  $team_b_pool = isset( $match['team_b_pool'] ) ? esc_attr( $match['team_b_pool'] ) : '';
  $score_a     = isset( $match['score_a'] )     ? esc_attr( $match['score_a'] )     : '';
  $score_b     = isset( $match['score_b'] )     ? esc_attr( $match['score_b'] )     : '';
  ?>
  <tr class="match-row">
    <td>
      <select name="defragby_matches_data[<?php echo $index; ?>][venue_date]" style="width:100%;">
        <option value="">-- 選択 --</option>
        <option value="1031" <?php selected($venue_date,'1031'); ?>>10.31 [土]　夢の島競技場</option>
        <option value="1102" <?php selected($venue_date,'1102'); ?>>11.02 [月]　江戸川区陸上競技場</option>
        <option value="1103" <?php selected($venue_date,'1103'); ?>>11.03 [火・祝]　秩父宮ラグビー場</option>
      </select>
    </td>
    <td><input type="text" name="defragby_matches_data[<?php echo $index; ?>][time]" value="<?php echo $time; ?>" placeholder="10:00" style="width:100%;" /></td>
    <td>
      <select name="defragby_matches_data[<?php echo $index; ?>][round]" style="width:100%;">
        <option value="group" <?php selected($round,'group'); ?>>グループ戦</option>
        <option value="sf" <?php selected($round,'sf'); ?>>準決勝</option>
        <option value="final" <?php selected($round,'final'); ?>>決勝</option>
      </select>
    </td>
    <td>
      <select class="team-selector" data-target-ja="team_a_ja_<?php echo $index; ?>" data-target-en="team_a_en_<?php echo $index; ?>" data-target-pool="team_a_pool_<?php echo $index; ?>" style="width:100%;" required>
        <option value="">-- チームを選択 --</option>
        <?php foreach ($teams as $t_row) : 
          $g_label = (isset($t_row['gender']) && $t_row['gender'] === 'women') ? '女子' : '男子';
          $p_code  = isset($t_row['pool']) ? $t_row['pool'] : '';
          $p_label = ($p_code === 'men-b') ? 'プールB' : 'プールA';
          $prefix  = "【{$g_label}・{$p_label}】";
          $opt_label = $prefix . ' ' . $t_row['name_ja'] . ' / ' . $t_row['name_en'];
          // チーム名とプールの一致で選択状態を決定
          $is_selected = ($team_a_ja === $t_row['name_ja'] && ($team_a_pool === '' || $team_a_pool === $p_code));
        ?>
          <option value="<?php echo esc_attr($t_row['name_ja']); ?>" data-en="<?php echo esc_attr($t_row['name_en']); ?>" data-pool="<?php echo esc_attr($p_code); ?>" <?php selected($is_selected, true); ?>><?php echo esc_html($opt_label); ?></option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="defragby_matches_data[<?php echo $index; ?>][team_a_ja]" id="team_a_ja_<?php echo $index; ?>" value="<?php echo $team_a_ja; ?>" />
      <input type="hidden" name="defragby_matches_data[<?php echo $index; ?>][team_a_en]" id="team_a_en_<?php echo $index; ?>" value="<?php echo $team_a_en; ?>" />
      <input type="hidden" name="defragby_matches_data[<?php echo $index; ?>][team_a_pool]" id="team_a_pool_<?php echo $index; ?>" value="<?php echo $team_a_pool; ?>" />
    </td>
    <td>
      <select class="team-selector" data-target-ja="team_b_ja_<?php echo $index; ?>" data-target-en="team_b_en_<?php echo $index; ?>" data-target-pool="team_b_pool_<?php echo $index; ?>" style="width:100%;" required>
        <option value="">-- チームを選択 --</option>
        <?php foreach ($teams as $t_row) : 
          $g_label = (isset($t_row['gender']) && $t_row['gender'] === 'women') ? '女子' : '男子';
          $p_code  = isset($t_row['pool']) ? $t_row['pool'] : '';
          $p_label = ($p_code === 'men-b') ? 'プールB' : 'プールA';
          $prefix  = "【{$g_label}・{$p_label}】";
          $opt_label = $prefix . ' ' . $t_row['name_ja'] . ' / ' . $t_row['name_en'];
          $is_selected = ($team_b_ja === $t_row['name_ja'] && ($team_b_pool === '' || $team_b_pool === $p_code));
        ?>
          <option value="<?php echo esc_attr($t_row['name_ja']); ?>" data-en="<?php echo esc_attr($t_row['name_en']); ?>" data-pool="<?php echo esc_attr($p_code); ?>" <?php selected($is_selected, true); ?>><?php echo esc_html($opt_label); ?></option>
        <?php endforeach; ?>
      </select>
      <input type="hidden" name="defragby_matches_data[<?php echo $index; ?>][team_b_ja]" id="team_b_ja_<?php echo $index; ?>" value="<?php echo $team_b_ja; ?>" />
      <input type="hidden" name="defragby_matches_data[<?php echo $index; ?>][team_b_en]" id="team_b_en_<?php echo $index; ?>" value="<?php echo $team_b_en; ?>" />
      <input type="hidden" name="defragby_matches_data[<?php echo $index; ?>][team_b_pool]" id="team_b_pool_<?php echo $index; ?>" value="<?php echo $team_b_pool; ?>" />
    </td>
    <td><input type="number" name="defragby_matches_data[<?php echo $index; ?>][score_a]" value="<?php echo $score_a; ?>" style="width:100%;" min="0" /></td>
    <td><input type="number" name="defragby_matches_data[<?php echo $index; ?>][score_b]" value="<?php echo $score_b; ?>" style="width:100%;" min="0" /></td>
    <td><button type="button" class="button button-link-delete delete-match-row" style="color:#d63638;">削除</button></td>
  </tr>
  <?php
}

/* ==========================================================================
   FAQ（よくある質問）設定画面 ＆ データ取得ヘルパー
   ========================================================================== */

/**
 * デフォルトFAQデータ
 */
function defragby_get_default_faq_data() {
  return array(
    array(
      'q_ja' => 'チケットは必要ですか？',
      'q_en' => 'Are tickets required for entry?',
      'a_ja' => '本大会は、全日程において「入場無料（チケット不要）」でご観戦いただけます。どなたでもお気軽に会場（秩父宮ラグビー場等）へお越しください。',
      'a_en' => 'Qualifying round matches are free of charge. Ticket regulations and requirements for finals at Chichibunomiya will be detailed.'
    ),
    array(
      'q_ja' => '雨天決行ですか？',
      'q_en' => 'Will matches proceed in rain?',
      'a_ja' => '基本的には雨天決行です。荒天等で観客の皆様の安全確保が難しい場合は中止となることがございます。最新の情報は公式SNSや本Webサイトの「ニュース」をご参照ください。',
      'a_en' => 'Matches proceed in standard rain, but severe conditions threatening safety may cause scheduling adjustments.'
    )
  );
}

/**
 * FAQデータ取得
 */
function defragby_get_faq_data() {
  $data = get_option( 'defragby_faq_data', array() );
  if ( empty( $data ) || ! is_array( $data ) ) {
    $data = defragby_get_default_faq_data();
  }
  return $data;
}

/**
 * FAQ管理画面 HTML
 */
function defragby_faq_settings_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  $faq_list = defragby_get_faq_data();
  ?>
  <div class="wrap">
    <h1>FAQ（よくある質問）管理・設定</h1>
    <p>よくある質問の追加・編集・削除・並び替えを行えます。プログラミングの知識なしで簡単に更新可能です。</p>

    <form action="options.php" method="post">
      <?php settings_fields( 'defragby-faq-group' ); ?>

      <table class="wp-list-table widefat fixed striped" id="faq-table" style="margin-top: 15px;">
        <thead>
          <tr>
            <th style="width: 70px; text-align: center;">移動</th>
            <th style="width: 35%;">質問（Q）</th>
            <th style="width: 50%;">回答（A）</th>
            <th style="width: 80px; text-align: center;">操作</th>
          </tr>
        </thead>
        <tbody id="faq-table-body">
          <?php
          if ( ! empty( $faq_list ) ) {
            foreach ( $faq_list as $index => $item ) {
              defragby_render_faq_row( $index, $item );
            }
          }
          ?>
        </tbody>
      </table>

      <p style="margin-top: 15px;">
        <button type="button" class="button button-secondary" id="add-faq-row-btn">
          <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle; margin-right: 4px;"></span>新しいFAQを追加
        </button>
      </p>

      <?php submit_button( '変更を保存' ); ?>
    </form>
  </div>

  <script>
  jQuery(document).ready(function($) {
    let nextIndex = $('#faq-table-body tr').length;

    // 行追加
    $('#add-faq-row-btn').on('click', function() {
      const newRowHtml = `
        <tr class="faq-row">
          <td style="text-align: center; vertical-align: middle;">
            <button type="button" class="button move-up-btn" title="上へ移動">▲</button>
            <button type="button" class="button move-down-btn" title="下へ移動" style="margin-top: 4px;">▼</button>
          </td>
          <td>
            <div style="margin-bottom: 8px;">
              <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">質問 [日本語]:</label>
              <input type="text" name="defragby_faq_data[${nextIndex}][q_ja]" value="" style="width: 100%;" placeholder="例: チケットは必要ですか？" required />
            </div>
            <div>
              <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">質問 [英語]:</label>
              <input type="text" name="defragby_faq_data[${nextIndex}][q_en]" value="" style="width: 100%;" placeholder="Example: Are tickets required?" />
            </div>
          </td>
          <td>
            <div style="margin-bottom: 8px;">
              <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">回答 [日本語]:</label>
              <textarea name="defragby_faq_data[${nextIndex}][a_ja]" rows="3" style="width: 100%;" placeholder="回答を入力してください。" required></textarea>
            </div>
            <div>
              <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">回答 [英語]:</label>
              <textarea name="defragby_faq_data[${nextIndex}][a_en]" rows="2" style="width: 100%;" placeholder="Enter answer in English..."></textarea>
            </div>
          </td>
          <td style="text-align: center; vertical-align: middle;">
            <button type="button" class="button button-link-delete delete-faq-btn" style="color: #d63638;">削除</button>
          </td>
        </tr>
      `;
      $('#faq-table-body').append(newRowHtml);
      nextIndex++;
      updateRowIndexes();
    });

    // 削除
    $(document).on('click', '.delete-faq-btn', function() {
      if (confirm('このFAQ項目を削除してもよろしいですか？')) {
        $(this).closest('tr').remove();
        updateRowIndexes();
      }
    });

    // 上へ移動
    $(document).on('click', '.move-up-btn', function() {
      const $row = $(this).closest('tr');
      const $prev = $row.prev('tr');
      if ($prev.length > 0) {
        $row.insertBefore($prev);
        updateRowIndexes();
      }
    });

    // 下へ移動
    $(document).on('click', '.move-down-btn', function() {
      const $row = $(this).closest('tr');
      const $next = $row.next('tr');
      if ($next.length > 0) {
        $row.insertAfter($next);
        updateRowIndexes();
      }
    });

    // インデックス再割り振りの連動
    function updateRowIndexes() {
      $('#faq-table-body tr').each(function(idx) {
        $(this).find('input[name*="[q_ja]"]').attr('name', `defragby_faq_data[${idx}][q_ja]`);
        $(this).find('input[name*="[q_en]"]').attr('name', `defragby_faq_data[${idx}][q_en]`);
        $(this).find('textarea[name*="[a_ja]"]').attr('name', `defragby_faq_data[${idx}][a_ja]`);
        $(this).find('textarea[name*="[a_en]"]').attr('name', `defragby_faq_data[${idx}][a_en]`);
      });
    }
  });
  </script>
  <?php
}

/**
 * FAQ行レンダリング
 */
function defragby_render_faq_row( $index, $item ) {
  $q_ja = isset( $item['q_ja'] ) ? esc_attr( $item['q_ja'] ) : '';
  $q_en = isset( $item['q_en'] ) ? esc_attr( $item['q_en'] ) : '';
  $a_ja = isset( $item['a_ja'] ) ? esc_textarea( $item['a_ja'] ) : '';
  $a_en = isset( $item['a_en'] ) ? esc_textarea( $item['a_en'] ) : '';
  ?>
  <tr class="faq-row">
    <td style="text-align: center; vertical-align: middle;">
      <button type="button" class="button move-up-btn" title="上へ移動">▲</button>
      <button type="button" class="button move-down-btn" title="下へ移動" style="margin-top: 4px;">▼</button>
    </td>
    <td>
      <div style="margin-bottom: 8px;">
        <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">質問 [日本語]:</label>
        <input type="text" name="defragby_faq_data[<?php echo $index; ?>][q_ja]" value="<?php echo $q_ja; ?>" style="width: 100%;" required />
      </div>
      <div>
        <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">質問 [英語]:</label>
        <input type="text" name="defragby_faq_data[<?php echo $index; ?>][q_en]" value="<?php echo $q_en; ?>" style="width: 100%;" />
      </div>
    </td>
    <td>
      <div style="margin-bottom: 8px;">
        <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">回答 [日本語]:</label>
        <textarea name="defragby_faq_data[<?php echo $index; ?>][a_ja]" rows="3" style="width: 100%;" required><?php echo $a_ja; ?></textarea>
      </div>
      <div>
        <label style="font-weight: bold; font-size: 11px; color: #475569; display: block;">回答 [英語]:</label>
        <textarea name="defragby_faq_data[<?php echo $index; ?>][a_en]" rows="2" style="width: 100%;"><?php echo $a_en; ?></textarea>
      </div>
    </td>
    <td style="text-align: center; vertical-align: middle;">
      <button type="button" class="button button-link-delete delete-faq-btn" style="color: #d63638;">削除</button>
    </td>
  </tr>
  <?php
}

/* ==========================================================================
   22. SPONSOR SETTINGS PAGE (ADMIN ONLY)
   ========================================================================== */

/**
 * スポンサー管理画面 HTML
 */
function defragby_sponsor_settings_page_html() {
  if ( ! current_user_can( 'manage_options' ) ) {
    return;
  }

  $sponsors = get_option( 'defragby_sponsors_data', array() );
  if ( ! is_array( $sponsors ) ) {
    $sponsors = array();
  }
  ?>
  <div class="wrap">
    <h1><?php echo esc_html( 'スポンサー情報設定 (管理者専用)' ); ?></h1>
    <p>スポンサー・パートナー情報を登録・管理します。「スポンサー名」と「スポンサーランク」は必須入力です。<br>
    ▲▼ ボタンでランク内の表示順を変更できます。変更後は必ず「スポンサー情報を保存する」を押して保存してください。</p>

    <form action="options.php" method="post" id="sponsor-settings-form">
      <?php
      settings_fields( 'defragby-sponsor-group' );
      do_settings_sections( 'defragby-sponsor-group' );
      ?>

      <table class="wp-list-table widefat fixed striped table-view-list" id="sponsors-settings-table" style="margin-top: 20px;">
        <thead>
          <tr>
            <th style="width: 60px;">順序</th>
            <th>スポンサー名 <span style="color:#d63638;">*</span></th>
            <th style="width: 210px;">スポンサーランク <span style="color:#d63638;">*</span></th>
            <th>スポンサーURL</th>
            <th>ロゴ画像</th>
            <th style="width: 60px;">削除</th>
          </tr>
        </thead>
        <tbody id="sponsors-tbody">
          <?php
          $index = 0;
          if ( ! empty( $sponsors ) ) {
            foreach ( $sponsors as $sponsor ) {
              defragby_render_sponsor_row( $index, $sponsor );
              $index++;
            }
          } else {
            defragby_render_sponsor_row( 0, array() );
            $index = 1;
          }
          ?>
        </tbody>
      </table>

      <div style="margin-top: 15px;">
        <button type="button" class="button button-secondary" id="add-sponsor-row">＋ スポンサーを追加する</button>
      </div>

      <!-- クラファン支援者テキストエリア -->
      <div style="margin-top: 32px; background: #fff; border: 1px solid #ccd0d4; padding: 20px 24px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
        <h3 style="margin-top: 0; color: #1d2327;">クラファン支援者さん一覧（テキスト入力）</h3>
        <p style="color: #646970; font-size: 13px;">支援者のお名前を1行に1名ずつ入力してください（URL・ロゴは不要）。</p>
        <?php
        $crowdfunding_text = get_option( 'defragby_crowdfunding_names', '' );
        ?>
        <textarea
          name="defragby_crowdfunding_names"
          id="defragby_crowdfunding_names_field"
          rows="10"
          style="width: 100%; font-size: 14px; font-family: monospace;"
          placeholder="山田 太郎&#10;田中 花子&#10;佐藤 一郎"
        ><?php echo esc_textarea( $crowdfunding_text ); ?></textarea>
        <p style="color: #646970; font-size: 12px; margin-top: 6px;">※ スポンサーページの「クラファン支援者」セクションに表示されます。</p>
      </div>

      <?php submit_button( 'スポンサー情報を保存する' ); ?>
    </form>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function() {
    var tbody   = document.getElementById('sponsors-tbody');
    var addBtn  = document.getElementById('add-sponsor-row');
    var rowIndex = <?php echo $index; ?>;

    /* ── 行追加 ── */
    addBtn.addEventListener('click', function() {
      var newIndex = rowIndex;
      var rowHtml = `
        <tr class="sponsor-row" data-index="${newIndex}">
          <td style="text-align:center; vertical-align:middle; white-space:nowrap;">
            <button type="button" class="button move-sponsor-up" title="上へ移動">▲</button><br>
            <button type="button" class="button move-sponsor-down" title="下へ移動" style="margin-top:4px;">▼</button>
          </td>
          <td><input type="text" name="defragby_sponsors_data[${newIndex}][name]" value="" placeholder="三機工業株式会社" style="width:100%;" required /></td>
          <td>
            <select name="defragby_sponsors_data[${newIndex}][rank]" style="width:100%;" required>
              <option value="gold">GOLD PARTNER</option>
              <option value="silver">SILVER PARTNER</option>
              <option value="partner_a">PARTNER A</option>
              <option value="partner_b">PARTNER B</option>
              <option value="co_host">共催 / 後援 / 提携</option>
              <option value="crowdfunding">クラファン支援者</option>
            </select>
          </td>
          <td><input type="url" name="defragby_sponsors_data[${newIndex}][url]" value="" placeholder="https://example.com" style="width:100%;" /></td>
          <td>
            <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
              <input type="text" name="defragby_sponsors_data[${newIndex}][logo_url]" id="sponsor_logo_${newIndex}" value="" placeholder="画像URL" style="width:55%;" readonly />
              <button type="button" class="button sponsor-choose-image-btn" data-target="sponsor_logo_${newIndex}">画像を選択</button>
              <img src="" alt="" class="sponsor-logo-preview" style="height:36px; display:none; border:1px solid #ddd; background:#fff; padding:3px; object-fit:contain;">
            </div>
          </td>
          <td><button type="button" class="button button-link-delete delete-sponsor-row" style="color:#d63638;">削除</button></td>
        </tr>
      `;
      var tempDiv = document.createElement('tbody');
      tempDiv.innerHTML = rowHtml.trim();
      var newRow = tempDiv.firstElementChild;
      tbody.appendChild(newRow);
      rowIndex++;
      reindexSponsorRows();
    });

    /* ── 行削除 ── */
    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('delete-sponsor-row')) {
        if (tbody.querySelectorAll('.sponsor-row').length > 1) {
          e.target.closest('tr').remove();
          reindexSponsorRows();
        } else {
          alert('これ以上削除できません。少なくとも1つのスポンサー行が必要です。');
        }
      }
    });

    /* ── 上へ移動 ── */
    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('move-sponsor-up')) {
        var row  = e.target.closest('tr');
        var prev = row.previousElementSibling;
        if (prev) {
          tbody.insertBefore(row, prev);
          reindexSponsorRows();
        }
      }
    });

    /* ── 下へ移動 ── */
    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('move-sponsor-down')) {
        var row  = e.target.closest('tr');
        var next = row.nextElementSibling;
        if (next) {
          tbody.insertBefore(next, row);
          reindexSponsorRows();
        }
      }
    });

    /* ── インデックス再割り振り ── */
    function reindexSponsorRows() {
      tbody.querySelectorAll('.sponsor-row').forEach(function(row, idx) {
        row.querySelectorAll('input[name], select[name], textarea[name]').forEach(function(el) {
          el.name = el.name.replace(/defragby_sponsors_data\[\d+\]/, 'defragby_sponsors_data[' + idx + ']');
        });
        row.querySelectorAll('input[id]').forEach(function(el) {
          el.id = el.id.replace(/sponsor_logo_\d+/, 'sponsor_logo_' + idx);
        });
        row.querySelectorAll('button.sponsor-choose-image-btn').forEach(function(btn) {
          if (btn.dataset.target) {
            btn.dataset.target = 'sponsor_logo_' + idx;
          }
        });
      });
    }

    /* ── メディアライブラリ ── */
    var mediaUploader;
    tbody.addEventListener('click', function(e) {
      if (e.target && e.target.classList.contains('sponsor-choose-image-btn')) {
        e.preventDefault();
        var targetInputId = e.target.getAttribute('data-target');
        var targetInput   = document.getElementById(targetInputId);
        if (!targetInput) return;

        if (mediaUploader) {
          mediaUploader.off('select');
        }
        mediaUploader = wp.media({
          title: 'スポンサーロゴを選択',
          button: { text: '画像をセットする' },
          multiple: false
        });
        mediaUploader.on('select', function() {
          var attachment = mediaUploader.state().get('selection').first().toJSON();
          targetInput.value = attachment.url;
          var previewImg = targetInput.parentElement.querySelector('.sponsor-logo-preview');
          if (previewImg) {
            previewImg.src   = attachment.url;
            previewImg.style.display = 'inline-block';
          }
        });
        mediaUploader.open();
      }
    });
  });
  </script>
  <?php
}

/**
 * スポンサー行のHTMLをレンダリング
 */
function defragby_render_sponsor_row( $index, $sponsor ) {
  $name     = isset( $sponsor['name'] )     ? esc_attr( $sponsor['name'] )    : '';
  $rank     = isset( $sponsor['rank'] )     ? esc_attr( $sponsor['rank'] )    : 'gold';
  $url      = isset( $sponsor['url'] )      ? esc_url( $sponsor['url'] )      : '';
  $logo_url = isset( $sponsor['logo_url'] ) ? esc_url( $sponsor['logo_url'] ) : '';

  $rank_options = array(
    'gold'         => 'GOLD PARTNER',
    'silver'       => 'SILVER PARTNER',
    'partner_a'    => 'PARTNER A',
    'partner_b'    => 'PARTNER B',
    'co_host'      => '共催 / 後援 / 提携',
    'crowdfunding' => 'クラファン支援者',
  );
  ?>
  <tr class="sponsor-row" data-index="<?php echo $index; ?>">
    <td style="text-align:center; vertical-align:middle; white-space:nowrap;">
      <button type="button" class="button move-sponsor-up" title="上へ移動">▲</button><br>
      <button type="button" class="button move-sponsor-down" title="下へ移動" style="margin-top:4px;">▼</button>
    </td>
    <td>
      <input type="text" name="defragby_sponsors_data[<?php echo $index; ?>][name]" value="<?php echo $name; ?>" placeholder="三機工業株式会社" style="width:100%;" required />
    </td>
    <td>
      <select name="defragby_sponsors_data[<?php echo $index; ?>][rank]" style="width:100%;" required>
        <?php foreach ( $rank_options as $val => $label ) : ?>
          <option value="<?php echo esc_attr( $val ); ?>" <?php selected( $rank, $val ); ?>><?php echo esc_html( $label ); ?></option>
        <?php endforeach; ?>
      </select>
    </td>
    <td>
      <input type="url" name="defragby_sponsors_data[<?php echo $index; ?>][url]" value="<?php echo $url; ?>" placeholder="https://example.com" style="width:100%;" />
    </td>
    <td>
      <div style="display:flex; gap:6px; align-items:center; flex-wrap:wrap;">
        <input type="text"
          name="defragby_sponsors_data[<?php echo $index; ?>][logo_url]"
          id="sponsor_logo_<?php echo $index; ?>"
          value="<?php echo $logo_url; ?>"
          placeholder="画像URL"
          style="width:55%;"
          readonly />
        <button type="button" class="button sponsor-choose-image-btn" data-target="sponsor_logo_<?php echo $index; ?>">画像を選択</button>
        <?php if ( ! empty( $logo_url ) ) : ?>
          <img src="<?php echo $logo_url; ?>" alt="ロゴ" class="sponsor-logo-preview" style="height:36px; border:1px solid #ddd; background:#fff; padding:3px; object-fit:contain; display:inline-block;">
        <?php else : ?>
          <img src="" alt="" class="sponsor-logo-preview" style="height:36px; display:none; border:1px solid #ddd; background:#fff; padding:3px; object-fit:contain;">
        <?php endif; ?>
      </div>
    </td>
    <td>
      <button type="button" class="button button-link-delete delete-sponsor-row" style="color:#d63638;">削除</button>
    </td>
  </tr>
  <?php
}

/* クラファン支援者テキスト保存（register_setting で保存できるよう登録） */
add_action( 'admin_init', function() {
  register_setting( 'defragby-sponsor-group', 'defragby_crowdfunding_names', array(
    'sanitize_callback' => 'sanitize_textarea_field',
    'default'           => '',
  ) );
} );
