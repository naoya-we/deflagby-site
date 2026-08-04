import os

with open('map_optimized.svg', encoding='utf-8') as f:
    svg_code = f.read()

theme_dir = 'wp-content/themes/kichiguru-theme'
img_path = "<?php echo get_template_directory_uri(); ?>/assets/images/"

front_page_code = f"""<?php
/**
 * Template Name: Front Page
 */
get_header();
?>

<!-- 2. HERO MAP SECTION (地図) -->
<section id="map-section" class="hero-map-section">
    <div class="hero-container">
        <div class="hero-header-text">
            <h1>三鷹・吉祥寺・西荻窪を<span>深く味わう</span></h1>
            <p>中央線の人気の3つのエリアのカルチャー・カフェ・カルチャースポットをつなぐ地域メディア</p>
        </div>
        <div class="svg-map-wrapper">
            {svg_code}
        </div>
    </div>
</section>

<!-- 3. STEP-BY-STEP SEARCH & FILTER -->
<section id="search-section" class="search-section">
    <div class="search-container">
        <h2 class="section-title">吉グルで探す</h2>
        
        <div class="step-grid">
            <!-- STEP 1: Area -->
            <div class="step-card" id="step-1-card">
                <div class="step-header">
                    <span class="step-num">1</span>
                    <h3 class="step-title">エリアを選択</h3>
                </div>
                <div class="chip-group" id="area-chip-group">
                    <button class="chip-btn is-active" data-area="all">すべて</button>
                    <button class="chip-btn" data-area="mitaka">三鷹</button>
                    <button class="chip-btn" data-area="kichijoji">吉祥寺</button>
                    <button class="chip-btn" data-area="nishiogi">西荻窪</button>
                </div>
            </div>

            <!-- STEP 2: Category -->
            <div class="step-card" id="step-2-card">
                <div class="step-header">
                    <span class="step-num">2</span>
                    <h3 class="step-title">カテゴリーを選択</h3>
                </div>
                <div class="chip-group" id="category-chip-group">
                    <button class="chip-btn is-active" data-category="all">すべて</button>
                    <button class="chip-btn" data-category="gourmet">グルメ</button>
                    <button class="chip-btn" data-category="art">アート</button>
                    <button class="chip-btn" data-category="beauty">ビューティー</button>
                    <button class="chip-btn" data-category="antique">アンティーク</button>
                </div>
            </div>

            <!-- STEP 3: Tag & Keyword -->
            <div class="step-card" id="step-3-card">
                <div class="step-header">
                    <span class="step-num">3</span>
                    <h3 class="step-title">タグで絞り込む</h3>
                </div>
                <!-- Dynamic Tag List -->
                <div class="chip-group" id="tag-chip-group">
                    <!-- Populated dynamically via main.js -->
                </div>
                <!-- Incremental Real-time Input -->
                <div class="search-input-wrapper">
                    <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input type="text" id="tag-search-input" placeholder="キーワードでリアルタイム検索... (例: カフェ, 古着, 珈琲)" />
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 4. FEATURE ARTICLE GALLERY (Curated Stories) -->
<section id="gallery-section" class="gallery-section">
    <div class="gallery-container">
        <div class="gallery-header">
            <h2 class="section-title" style="margin-bottom:0;">吉グルを楽しむ</h2>
            <a href="#" class="view-all-link">
                すべて見る
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <div class="article-grid p-top-news__list" id="article-grid">
            <!-- Card 1 -->
            <article class="story-card c-card-event js-fade-up-item" data-area="kichijoji" data-category="gourmet" data-tags="cafe,retro,morning,coffee">
                <div class="card-image-wrap">
                    <img src="{img_path}8_426.png" alt="吉祥寺で最高のコーヒーが飲める場所" />
                    <span class="card-cat-badge">グルメ</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">吉祥寺で最高のコーヒーが飲める場所</h3>
                    <div class="card-tags">
                        <span class="tag-badge">#cafe</span>
                        <span class="tag-badge">#retro</span>
                        <span class="tag-badge">#morning</span>
                    </div>
                    <a href="#" class="card-btn">
                        詳しく読む
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

            <!-- Card 2 -->
            <article class="story-card c-card-event js-fade-up-item" data-area="mitaka" data-category="art" data-tags="exhibition,localcraft,gallery">
                <div class="card-image-wrap">
                    <img src="{img_path}8_446.png" alt="三鷹の隠れたギャラリー巡り" />
                    <span class="card-cat-badge">アート</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">三鷹の隠れたギャラリー巡り</h3>
                    <div class="card-tags">
                        <span class="tag-badge">#exhibition</span>
                        <span class="tag-badge">#localcraft</span>
                    </div>
                    <a href="#" class="card-btn">
                        詳しく読む
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

            <!-- Card 3 -->
            <article class="story-card c-card-event js-fade-up-item" data-area="nishiogi" data-category="antique" data-tags="vintage,shopping,furniture">
                <div class="card-image-wrap">
                    <img src="{img_path}8_464.png" alt="週末のアンティーク探しガイド" />
                    <span class="card-cat-badge">アンティーク</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">週末のアンティーク探しガイド</h3>
                    <div class="card-tags">
                        <span class="tag-badge">#vintage</span>
                        <span class="tag-badge">#shopping</span>
                    </div>
                    <a href="#" class="card-btn">
                        詳しく読む
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

            <!-- Card 4 -->
            <article class="story-card c-card-event js-fade-up-item" data-area="mitaka" data-category="gourmet" data-tags="mitaka,gourmet,walk">
                <div class="card-image-wrap">
                    <img src="{img_path}8_482.png" alt="三鷹の隠れた名店散歩" />
                    <span class="card-cat-badge">グルメ</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">三鷹の隠れた名店散歩</h3>
                    <div class="card-tags">
                        <span class="tag-badge">#mitaka</span>
                        <span class="tag-badge">#gourmet</span>
                        <span class="tag-badge">#walk</span>
                    </div>
                    <a href="#" class="card-btn">
                        詳しく読む
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

            <!-- Card 5 -->
            <article class="story-card c-card-event js-fade-up-item" data-area="kichijoji" data-category="gourmet" data-tags="kichijoji,cafe,bakery">
                <div class="card-image-wrap">
                    <img src="{img_path}8_503.png" alt="吉祥寺のカフェ文化を巡る" />
                    <span class="card-cat-badge">グルメ</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">吉祥寺のカフェ文化を巡る</h3>
                    <div class="card-tags">
                        <span class="tag-badge">#kichijoji</span>
                        <span class="tag-badge">#cafe</span>
                    </div>
                    <a href="#" class="card-btn">
                        詳しく読む
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>

            <!-- Card 6 -->
            <article class="story-card c-card-event js-fade-up-item" data-area="nishiogi" data-category="antique" data-tags="nishiogi,antique,interior">
                <div class="card-image-wrap">
                    <img src="{img_path}8_522.png" alt="西荻窪のアンティーク家具店" />
                    <span class="card-cat-badge">アンティーク</span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">西荻窪のアンティーク家具店</h3>
                    <div class="card-tags">
                        <span class="tag-badge">#nishiogi</span>
                        <span class="tag-badge">#antique</span>
                    </div>
                    <a href="#" class="card-btn">
                        詳しく読む
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
        </div>
    </div>
</section>

<!-- 5. INSTAGRAM DOUBLE VERTICAL FEED (Community Lens) -->
<section id="insta-section" class="insta-section">
    <div class="insta-container">
        <h2 class="section-title">吉グルで出会う</h2>

        <div class="insta-feed-wrapper">
            <!-- Account A: @mitaka_vibes -->
            <div class="insta-column">
                <div class="insta-header">
                    <div class="account-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                    <div>
                        <div class="account-handle">@mitaka_vibes</div>
                        <div class="account-sub">ローカルライフスタイル</div>
                    </div>
                </div>

                <div class="insta-viewport">
                    <div class="insta-track" id="insta-track-a">
                        <div class="insta-item"><img src="{img_path}8_554.png" alt="IG Post 1" /></div>
                        <div class="insta-item"><img src="{img_path}8_556.png" alt="IG Post 2" /></div>
                        <div class="insta-item"><img src="{img_path}8_558.png" alt="IG Post 3" /></div>
                        <div class="insta-item"><img src="{img_path}8_560.png" alt="IG Post 4" /></div>
                    </div>
                </div>
            </div>

            <!-- Account B: @nishiogi_finds -->
            <div class="insta-column">
                <div class="insta-header">
                    <div class="account-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </div>
                    <div>
                        <div class="account-handle">@nishiogi_finds</div>
                        <div class="account-sub">ヴィンテージ＆クラフト</div>
                    </div>
                </div>

                <div class="insta-viewport">
                    <div class="insta-track" id="insta-track-b">
                        <div class="insta-item"><img src="{img_path}8_573.png" alt="IG Post 5" /></div>
                        <div class="insta-item"><img src="{img_path}8_575.png" alt="IG Post 6" /></div>
                        <div class="insta-item"><img src="{img_path}8_577.png" alt="IG Post 7" /></div>
                        <div class="insta-item"><img src="{img_path}8_579.png" alt="IG Post 8" /></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
"""

with open(os.path.join(theme_dir, 'front-page.php'), 'w', encoding='utf-8') as f:
    f.write(front_page_code)

print('Updated front-page.php with js-fade-up-item classes!')
