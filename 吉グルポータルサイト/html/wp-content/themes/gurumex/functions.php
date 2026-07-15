<?php
/**
 * グルメポータル例 functions.php
 * テーマの主要機能をここで定義する
 */

// テキストドメイン設定
function gurumex_setup() {
    load_theme_textdomain( 'gurumex', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'gallery', 'caption' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );

    // ナビゲーションメニュー
    register_nav_menus( [
        'primary' => __( 'メインナビゲーション', 'gurumex' ),
        'footer'  => __( 'フッターナビゲーション', 'gurumex' ),
    ] );
}
add_action( 'after_setup_theme', 'gurumex_setup' );

// スタイルシート・スクリプト読み込み
function gurumex_enqueue_scripts() {
    // テーマのメインスタイル（変数定義など）
    wp_enqueue_style( 'gurumex-style', get_stylesheet_uri() );

    // メインCSS
    wp_enqueue_style(
        'gurumex-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [ 'gurumex-style' ],
        '1.0.0'
    );

    // Leaflet.js（地図）
    wp_enqueue_style(
        'leaflet-css',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css',
        [],
        '1.9.4'
    );
    wp_enqueue_script(
        'leaflet-js',
        'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js',
        [],
        '1.9.4',
        true
    );

    // メインJS
    wp_enqueue_script(
        'gurumex-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        '1.0.0',
        true
    );

    // AJAX検索JS（検索結果ページのみ）
    if ( is_search() || is_tax( [ 'area', 'genre', 'shop_tag' ] ) || is_post_type_archive( 'shop' ) ) {
        wp_enqueue_script(
            'gurumex-ajax-search',
            get_template_directory_uri() . '/assets/js/ajax-search.js',
            [ 'gurumex-main' ],
            '1.0.0',
            true
        );
        wp_localize_script( 'gurumex-ajax-search', 'gurumexAjax', [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'gurumex_search_nonce' ),
        ] );
    }

    // Leaflet地図JS（トップページ・店舗詳細ページ）
    if ( is_front_page() || is_singular( 'shop' ) ) {
        wp_enqueue_script(
            'gurumex-map',
            get_template_directory_uri() . '/assets/js/map.js',
            [ 'leaflet-js' ],
            '1.0.0',
            true
        );
        // 地図用データをJSに渡す
        if ( is_front_page() ) {
            $shop_locations = gurumex_get_all_shop_locations();
            wp_localize_script( 'gurumex-map', 'gurumexMap', [
                'mode'      => 'index',
                'locations' => $shop_locations,
                'center'    => [ 35.7022, 139.5804 ], // 吉祥寺駅
                'zoom'      => 15,
            ] );
        } elseif ( is_singular( 'shop' ) ) {
            global $post;
            $lat = get_post_meta( $post->ID, 'shop_lat', true );
            $lng = get_post_meta( $post->ID, 'shop_lng', true );
            wp_localize_script( 'gurumex-map', 'gurumexMap', [
                'mode'   => 'single',
                'center' => [ $lat ?: 35.7022, $lng ?: 139.5804 ],
                'zoom'   => 16,
                'name'   => get_the_title(),
            ] );
        }
    }

    // お気に入りJS
    if ( is_singular( 'shop' ) || is_front_page() ) {
        wp_enqueue_script(
            'gurumex-favorites',
            get_template_directory_uri() . '/assets/js/favorites.js',
            [ 'gurumex-main' ],
            '1.0.0',
            true
        );
        wp_localize_script( 'gurumex-favorites', 'gurumexFav', [
            'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'gurumex_fav_nonce' ),
            'loggedIn' => is_user_logged_in(),
            'loginUrl' => wp_login_url( get_permalink() ),
        ] );
    }
}
add_action( 'wp_enqueue_scripts', 'gurumex_enqueue_scripts' );


// =============================================
// カスタム投稿タイプ: shop
// =============================================
function gurumex_register_post_types() {
    $labels = [
        'name'               => '店舗',
        'singular_name'      => '店舗',
        'add_new'            => '新しい店舗を追加',
        'add_new_item'       => '新しい店舗を追加',
        'edit_item'          => '店舗を編集',
        'new_item'           => '新しい店舗',
        'view_item'          => '店舗を見る',
        'search_items'       => '店舗を検索',
        'not_found'          => '店舗が見つかりません',
        'not_found_in_trash' => 'ゴミ箱に店舗はありません',
        'menu_name'          => '店舗管理',
    ];

    register_post_type( 'shop', [
        'labels'             => $labels,
        'public'             => true,
        'show_in_rest'       => true,
        'has_archive'        => true,
        'rewrite'            => [ 'slug' => 'shop' ],
        'menu_icon'          => 'dashicons-store',
        'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt' ],
        'taxonomies'         => [ 'area', 'genre', 'shop_tag' ],
    ] );
}
add_action( 'init', 'gurumex_register_post_types' );


// =============================================
// カスタムタクソノミー
// =============================================
function gurumex_register_taxonomies() {
    // エリア（階層あり）
    register_taxonomy( 'area', 'shop', [
        'labels'       => [
            'name'          => 'エリア',
            'singular_name' => 'エリア',
            'add_new_item'  => '新しいエリアを追加',
        ],
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'area' ],
    ] );

    // ジャンル（階層あり）
    register_taxonomy( 'genre', 'shop', [
        'labels'       => [
            'name'          => '料理ジャンル',
            'singular_name' => '料理ジャンル',
            'add_new_item'  => '新しいジャンルを追加',
        ],
        'hierarchical' => true,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'genre' ],
    ] );

    // こだわりタグ（階層なし）
    register_taxonomy( 'shop_tag', 'shop', [
        'labels'       => [
            'name'          => 'こだわり条件',
            'singular_name' => 'こだわり条件',
            'add_new_item'  => '新しいタグを追加',
        ],
        'hierarchical' => false,
        'public'       => true,
        'show_in_rest' => true,
        'rewrite'      => [ 'slug' => 'tag' ],
    ] );
}
add_action( 'init', 'gurumex_register_taxonomies' );


// =============================================
// ACF カスタムフィールド定義（ACFがない場合のフォールバック用）
// =============================================
function gurumex_register_acf_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( [
        'key'    => 'group_shop_fields',
        'title'  => '店舗情報',
        'fields' => [
            [
                'key'   => 'field_shop_address',
                'label' => '住所',
                'name'  => 'shop_address',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_shop_lat',
                'label' => '緯度',
                'name'  => 'shop_lat',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_shop_lng',
                'label' => '経度',
                'name'  => 'shop_lng',
                'type'  => 'text',
            ],
            [
                'key'     => 'field_shop_budget',
                'label'   => '予算帯',
                'name'    => 'shop_budget',
                'type'    => 'select',
                'choices' => [
                    '~1000'       => '〜¥1,000',
                    '1000~2000'   => '¥1,000〜2,000',
                    '2000~4000'   => '¥2,000〜4,000',
                    '4000~6000'   => '¥4,000〜6,000',
                    '6000~'       => '¥6,000〜',
                ],
            ],
            [
                'key'   => 'field_shop_hours',
                'label' => '営業時間',
                'name'  => 'shop_hours',
                'type'  => 'textarea',
                'rows'  => 3,
            ],
            [
                'key'   => 'field_shop_closed',
                'label' => '定休日',
                'name'  => 'shop_closed',
                'type'  => 'text',
            ],
            [
                'key'   => 'field_shop_instagram_url',
                'label' => 'Instagram URL（埋め込み用）',
                'name'  => 'shop_instagram_url',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_shop_official_url',
                'label' => '公式サイト URL',
                'name'  => 'shop_official_url',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_shop_tabelog_url',
                'label' => '食べログ URL',
                'name'  => 'shop_tabelog_url',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_shop_rating_owner',
                'label' => 'オーナー評価（0〜5）',
                'name'  => 'shop_rating_owner',
                'type'  => 'number',
                'min'   => 0,
                'max'   => 5,
                'step'  => 0.1,
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'post_type',
                    'operator' => '==',
                    'value'    => 'shop',
                ],
            ],
        ],
    ] );
}
add_action( 'acf/init', 'gurumex_register_acf_fields' );


// =============================================
// AJAX 検索ハンドラ
// =============================================
function gurumex_ajax_search() {
    check_ajax_referer( 'gurumex_search_nonce', 'nonce' );

    $area    = isset( $_POST['area'] ) ? sanitize_text_field( $_POST['area'] ) : '';
    $genre   = isset( $_POST['genre'] ) ? sanitize_text_field( $_POST['genre'] ) : '';
    $tags    = isset( $_POST['tags'] ) ? array_map( 'sanitize_text_field', (array) $_POST['tags'] ) : [];

    $tax_query = [ 'relation' => 'AND' ];
    if ( $area ) {
        $tax_query[] = [
            'taxonomy' => 'area',
            'field'    => 'slug',
            'terms'    => $area,
        ];
    }
    if ( $genre ) {
        $tax_query[] = [
            'taxonomy' => 'genre',
            'field'    => 'slug',
            'terms'    => $genre,
        ];
    }
    if ( ! empty( $tags ) ) {
        $tax_query[] = [
            'taxonomy' => 'shop_tag',
            'field'    => 'slug',
            'terms'    => $tags,
            'operator' => 'IN',
        ];
    }

    $args = [
        'post_type'      => 'shop',
        'posts_per_page' => 20,
        'tax_query'      => count( $tax_query ) > 1 ? $tax_query : [],
    ];

    $query = new WP_Query( $args );
    $output = '';
    $count  = $query->found_posts;

    if ( $query->have_posts() ) {
        ob_start();
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part( 'template-parts/shop', 'card' );
        }
        $output = ob_get_clean();
        wp_reset_postdata();
    }

    wp_send_json_success( [
        'html'  => $output,
        'count' => $count,
    ] );
}
add_action( 'wp_ajax_gurumex_search', 'gurumex_ajax_search' );
add_action( 'wp_ajax_nopriv_gurumex_search', 'gurumex_ajax_search' );


// =============================================
// お気に入り機能
// =============================================
function gurumex_toggle_favorite() {
    check_ajax_referer( 'gurumex_fav_nonce', 'nonce' );

    if ( ! is_user_logged_in() ) {
        wp_send_json_error( [ 'message' => 'ログインが必要です' ] );
    }

    $user_id = get_current_user_id();
    $shop_id = intval( $_POST['shop_id'] );
    $favs    = get_user_meta( $user_id, 'gurumex_favorites', true );
    if ( ! is_array( $favs ) ) {
        $favs = [];
    }

    if ( in_array( $shop_id, $favs, true ) ) {
        $favs = array_diff( $favs, [ $shop_id ] );
        $is_fav = false;
    } else {
        $favs[] = $shop_id;
        $is_fav = true;
    }

    update_user_meta( $user_id, 'gurumex_favorites', array_values( $favs ) );
    wp_send_json_success( [ 'is_fav' => $is_fav ] );
}
add_action( 'wp_ajax_gurumex_toggle_favorite', 'gurumex_toggle_favorite' );


// =============================================
// ヘルパー関数
// =============================================

/**
 * 全店舗の位置情報を取得（地図用）
 */
function gurumex_get_all_shop_locations() {
    $shops = get_posts( [
        'post_type'      => 'shop',
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ] );
    $locations = [];
    foreach ( $shops as $shop_id ) {
        $lat = get_post_meta( $shop_id, 'shop_lat', true );
        $lng = get_post_meta( $shop_id, 'shop_lng', true );
        if ( $lat && $lng ) {
            $genres = wp_get_post_terms( $shop_id, 'genre', [ 'fields' => 'names' ] );
            $locations[] = [
                'id'    => $shop_id,
                'name'  => get_the_title( $shop_id ),
                'lat'   => (float) $lat,
                'lng'   => (float) $lng,
                'genre' => ! is_wp_error( $genres ) && ! empty( $genres ) ? $genres[0] : '',
                'url'   => get_permalink( $shop_id ),
            ];
        }
    }
    return $locations;
}

/**
 * ユーザーがお気に入りしているか確認
 */
function gurumex_is_favorite( $shop_id, $user_id = null ) {
    if ( ! $user_id ) {
        $user_id = get_current_user_id();
    }
    if ( ! $user_id ) {
        return false;
    }
    $favs = get_user_meta( $user_id, 'gurumex_favorites', true );
    return is_array( $favs ) && in_array( (int) $shop_id, $favs, true );
}

/**
 * 星評価表示のHTMLを返す
 */
function gurumex_render_stars( $rating, $max = 5 ) {
    $html = '<span class="rating-stars" aria-label="評価: ' . esc_attr( $rating ) . '/' . $max . '">';
    for ( $i = 1; $i <= $max; $i++ ) {
        if ( $rating >= $i ) {
            $html .= '★';
        } elseif ( $rating >= $i - 0.5 ) {
            $html .= '⭐'; // half star fallback
        } else {
            $html .= '☆';
        }
    }
    $html .= '</span>';
    return $html;
}

/**
 * 予算ラベルを返す
 */
function gurumex_budget_label( $budget_key ) {
    $labels = [
        '~1000'     => '〜¥1,000',
        '1000~2000' => '¥1,000〜2,000',
        '2000~4000' => '¥2,000〜4,000',
        '4000~6000' => '¥4,000〜6,000',
        '6000~'     => '¥6,000〜',
    ];
    return $labels[ $budget_key ] ?? '未設定';
}

// =============================================
// パーミッション: 一般ユーザーはwp-adminにアクセスさせない
// =============================================
function gurumex_restrict_admin_access() {
    if ( is_admin() && ! current_user_can( 'edit_posts' ) && ! wp_doing_ajax() ) {
        wp_redirect( home_url() );
        exit;
    }
}
add_action( 'admin_init', 'gurumex_restrict_admin_access' );

// ダッシュバーを一般ユーザーには表示しない
add_filter( 'show_admin_bar', function( $show ) {
    if ( ! current_user_can( 'edit_posts' ) ) {
        return false;
    }
    return $show;
} );

// =============================================
// メインクエリの絞り込み（初期ページロード用）
// =============================================
function gurumex_filter_shops_main_query( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_post_type_archive( 'shop' ) || is_tax( [ 'area', 'genre', 'shop_tag' ] ) || $query->is_home() ) {
        // GETパラメータを取得
        $area  = isset( $_GET['area'] ) ? sanitize_text_field( $_GET['area'] ) : '';
        $genre = isset( $_GET['genre'] ) ? sanitize_text_field( $_GET['genre'] ) : '';
        $tags  = isset( $_GET['shop_tag'] ) ? array_map( 'sanitize_text_field', (array) $_GET['shop_tag'] ) : [];

        $tax_query = [ 'relation' => 'AND' ];

        if ( $area ) {
            $tax_query[] = [
                'taxonomy' => 'area',
                'field'    => 'slug',
                'terms'    => $area,
            ];
        }
        if ( $genre ) {
            $tax_query[] = [
                'taxonomy' => 'genre',
                'field'    => 'slug',
                'terms'    => $genre,
            ];
        }
        if ( ! empty( $tags ) ) {
            $tax_query[] = [
                'taxonomy' => 'shop_tag',
                'field'    => 'slug',
                'terms'    => $tags,
                'operator' => 'IN',
            ];
        }

        if ( count( $tax_query ) > 1 ) {
            $query->set( 'tax_query', $tax_query );
        }

        // トップページからの遷移時にpost_type=shopが指定されている場合、強制的にshopの一覧にする
        if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'shop' ) {
            $query->set( 'post_type', 'shop' );
        }
    }
}
add_action( 'pre_get_posts', 'gurumex_filter_shops_main_query' );

// =============================================
// Site Reviews フォームの日本語化 & カスタムタグ追加
// =============================================
add_filter( 'site-reviews/config/forms/review-form', function ( array $fields ) : array {
    // 既存項目の日本語化
    if ( isset( $fields['rating'] ) ) {
        $fields['rating']['label'] = 'お店の評価';
    }
    if ( isset( $fields['title'] ) ) {
        $fields['title']['label'] = 'タイトル';
        $fields['title']['placeholder'] = 'タイトルを入力してください（例: 料理も接客も大満足！）';
    }
    if ( isset( $fields['content'] ) ) {
        $fields['content']['label'] = 'レビュー内容';
        $fields['content']['placeholder'] = 'お店の雰囲気、おすすめメニュー、混雑状況などを教えてください';
    }
    if ( isset( $fields['name'] ) ) {
        $fields['name']['label'] = 'ニックネーム';
        $fields['name']['placeholder'] = 'ニックネームを入力してください';
    }
    if ( isset( $fields['email'] ) ) {
        $fields['email']['label'] = 'メールアドレス';
        $fields['email']['placeholder'] = 'メールアドレスを入力してください';
    }
    if ( isset( $fields['terms'] ) ) {
        $fields['terms']['label'] = 'このレビューは私の実体験に基づいており、私の純粋な意見であることを確認します。';
    }

    // 動的にこだわり条件（shop_tag）を取得してチェックボックスを追加
    $terms = get_terms( [
        'taxonomy'   => 'shop_tag',
        'hide_empty' => false,
    ] );
    $options = [];
    if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
    }

    if ( ! empty( $options ) ) {
        $fields['custom_shop_tags'] = [
            'label'    => 'このお店の特徴・こだわり（レビューで報告）',
            'type'     => 'checkbox',
            'options'  => $options,
            'required' => false,
        ];
    }

    return $fields;
} );

// =============================================
// レビュー投稿時のカスタムタグ保存と集計再計算
// =============================================

// 新規作成時にカスタムタグをレビューのメタに保存する
add_action( 'site-reviews/review/created', function ( $review, $command ) {
    if ( isset( $_POST['custom_shop_tags'] ) && is_array( $_POST['custom_shop_tags'] ) ) {
        $tags = array_map( 'sanitize_text_field', $_POST['custom_shop_tags'] );
        update_post_meta( $review->ID, '_custom_shop_tags', $tags );
    }

    // 店舗の集計を更新
    $assigned_posts = get_post_meta( $review->ID, '_assigned_posts', true );
    if ( ! empty( $assigned_posts ) ) {
        foreach ( (array) $assigned_posts as $shop_id ) {
            gurumex_recalculate_shop_review_tags( $shop_id );
        }
    }
}, 10, 2 );

// 承認状態変更時に集計を更新する
add_action( 'site-reviews/review/approved', 'gurumex_on_review_status_changed' );
add_action( 'site-reviews/review/unapproved', 'gurumex_on_review_status_changed' );
add_action( 'site-reviews/review/transitioned', 'gurumex_on_review_status_changed_transitioned', 10, 3 );

function gurumex_on_review_status_changed( $review ) {
    $assigned_posts = get_post_meta( $review->ID, '_assigned_posts', true );
    if ( ! empty( $assigned_posts ) ) {
        foreach ( (array) $assigned_posts as $shop_id ) {
            gurumex_recalculate_shop_review_tags( $shop_id );
        }
    }
}

function gurumex_on_review_status_changed_transitioned( $review, $new_status, $old_status ) {
    gurumex_on_review_status_changed( $review );
}

// 店舗のこだわりタグ集計再計算ロジック
function gurumex_recalculate_shop_review_tags( $shop_id ) {
    // 該当店舗に紐づく、公開済みかつ承認済みのレビューを全件取得
    $reviews = get_posts( [
        'post_type'      => 'site-review',
        'post_status'    => 'publish', // 通常、承認済みレビューは publish ステータス
        'posts_per_page' => -1,
        'meta_query'     => [
            'relation' => 'AND',
            [
                'key'     => '_assigned_posts',
                'value'   => $shop_id,
                'compare' => '=',
            ],
            [
                'key'     => '_approved',
                'value'   => '1',
                'compare' => '=',
            ],
        ],
    ] );

    $tag_counts = [];
    foreach ( $reviews as $review ) {
        $tags = get_post_meta( $review->ID, '_custom_shop_tags', true );
        if ( is_array( $tags ) ) {
            foreach ( $tags as $tag_slug ) {
                if ( ! isset( $tag_counts[ $tag_slug ] ) ) {
                    $tag_counts[ $tag_slug ] = 0;
                }
                $tag_counts[ $tag_slug ]++;
            }
        }
    }

    // 集計結果を店舗のカスタムフィールドにキャッシュ保存
    update_post_meta( $shop_id, 'gurumex_review_tag_counts', $tag_counts );
}

