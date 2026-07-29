<?php
/**
 * Template Name: Single Team Detail
 * Description: カスタム投稿タイプ team の個別詳細ページ用テンプレート
 */
get_header();
?>

<main id="primary" class="site-main container">
  <?php while ( have_posts() ) : the_post(); 
    $post_id = get_the_ID();

    // 対応する国旗・チーム画像を取得
    $teams_data = get_option( 'defragby_teams_data', array() );
    $team_image_url = '';
    $team_name_en   = '';

    if ( is_array( $teams_data ) ) {
      foreach ( $teams_data as $t ) {
        if ( isset( $t['detail_post_id'] ) && intval( $t['detail_post_id'] ) === $post_id ) {
          $team_image_url = isset( $t['image_url'] ) ? $t['image_url'] : '';
          $team_name_en   = isset( $t['name_en'] ) ? $t['name_en'] : '';
          break;
        }
      }
    }
  ?>
    <!-- Subpage Header -->
    <header class="page-header" style="text-align: center; position: relative;">
      <?php if ( ! empty( $team_image_url ) ) : ?>
        <div class="team-header-flag" style="margin: 0 auto 16px; width: 100px; height: 65px; border-radius: 6px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.25); background: url('<?php echo esc_url($team_image_url); ?>') no-repeat center/cover;"></div>
      <?php endif; ?>
      <h1 class="page-title"><?php the_title(); ?></h1>
      <?php if ( ! empty( $team_name_en ) ) : ?>
        <div class="entry-meta" style="text-transform: uppercase; letter-spacing: 2px; font-weight: 700; color: var(--color-gold);"><?php echo esc_html( $team_name_en ); ?></div>
      <?php endif; ?>
    </header>

    <div style="max-width: 900px; margin: 0 auto; padding: 20px 0 60px;">
      
      <!-- アイキャッチ画像（管理画面で設定した場合表示） -->
      <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail" style="margin-bottom: 40px; border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-md, 0 4px 20px rgba(0,0,0,0.1));">
          <?php the_post_thumbnail( 'large', array( 'style' => 'width:100%; height:auto; display:block;' ) ); ?>
        </div>
      <?php endif; ?>

      <!-- 管理画面（投稿エディタ）で入力された本文エリア -->
      <article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-content' ); ?> style="background: var(--color-surface, #ffffff); padding: 40px 32px; border-radius: 12px; border: 1px solid var(--color-border, #e2e8f0); line-height: 1.8; font-size: 1.05rem; box-shadow: 0 2px 12px rgba(0,0,0,0.03);">
        <?php 
          the_content(); 
          
          // 本文が空の場合の初期メッセージプロンプト（下書き時など）
          if ( get_the_content() === '' ) :
        ?>
          <div style="padding: 40px 20px; text-align: center; color: var(--color-text-muted, #718096); border: 2px dashed #cbd5e0; border-radius: 8px; background: #f8fafc;">
            <p style="margin: 0; font-size: 0.95rem; font-weight: 500;">
              <?php echo esc_html( get_multilang_text('※現在、チーム情報を作成中・準備中です。管理画面の「チーム」投稿一覧から自由に編集できます。', '※ Team details are currently being prepared. You can edit this page content from the WP admin panel.') ); ?>
            </p>
          </div>
        <?php endif; ?>
      </article>

      <!-- チーム一覧へ戻るボタン -->
      <div style="margin-top: 50px; text-align: center;">
        <a href="<?php echo esc_url( home_url( '/teams/' ) ); ?>" class="button" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 28px; background: var(--color-navy, #1D4C5E); color: #ffffff; text-decoration: none; border-radius: 30px; font-weight: 600; font-size: 0.95rem; transition: all 0.25s ease;">
          <i class="fa-solid fa-arrow-left"></i>
          <?php echo esc_html( get_multilang_text('チーム一覧に戻る', 'Back to Nations & Teams') ); ?>
        </a>
      </div>

    </div>
  <?php endwhile; ?>
</main>

<?php
get_footer();
