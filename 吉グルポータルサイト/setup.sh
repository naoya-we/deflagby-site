#!/bin/bash
# ============================================================
# グルメポータル例 — WordPress 一括セットアップスクリプト
# ============================================================
set -e

WP="wp --allow-root"
SITE_URL="http://localhost:8081"

echo "=================================================="
echo "  グルメポータル例 自動セットアップ開始"
echo "=================================================="

# ----------------------------------------------------------
# 1. パーマリンク設定
# ----------------------------------------------------------
echo ""
echo "[1/8] パーマリンクを「投稿名」に設定..."
$WP option update permalink_structure '/%postname%/'
$WP rewrite flush

# ----------------------------------------------------------
# 2. テーマ有効化
# ----------------------------------------------------------
echo ""
echo "[2/8] テーマ (gurumex) を有効化..."
$WP theme activate gurumex || echo "※テーマはすでに有効化済みかもしれません"

# ----------------------------------------------------------
# 3. プラグイン インストール & 有効化
# ----------------------------------------------------------
echo ""
echo "[3/8] プラグインをインストール・有効化..."

# Advanced Custom Fields
$WP plugin install advanced-custom-fields --activate || echo "ACF already installed"

# Site Reviews（すでにインストール済みの場合はスキップ）
$WP plugin activate site-reviews 2>/dev/null || $WP plugin install site-reviews --activate || echo "Site Reviews: 対応済み"

# Theme My Login（会員登録・マイページ）
$WP plugin install theme-my-login --activate || echo "Theme My Login already installed"

# Yoast SEO
$WP plugin install wordpress-seo --activate || echo "Yoast SEO already installed"

# Limit Login Attempts Reloaded（セキュリティ）
$WP plugin activate limit-login-attempts-reloaded 2>/dev/null || $WP plugin install limit-login-attempts-reloaded --activate || echo "LLA already installed"

echo "プラグイン設定完了"

# ----------------------------------------------------------
# 4. タクソノミー初期データ登録
# ----------------------------------------------------------
echo ""
echo "[4/8] タクソノミーデータを登録..."

# エリア（area）
$WP term create area "吉祥寺" --slug=kichijoji --porcelain 2>/dev/null || true

# ジャンル（genre）
for GENRE in "和食:washoku" "洋食:yoshoku" "中華・アジアン:asian" "カフェ・スイーツ:cafe" "ラーメン・麺類:ramen" "居酒屋・バー:izakaya"; do
  NAME="${GENRE%%:*}"
  SLUG="${GENRE##*:}"
  $WP term create genre "$NAME" --slug="$SLUG" --porcelain 2>/dev/null || true
done

# サブジャンル
WASHOKU_ID=$($WP term get genre washoku --field=term_id 2>/dev/null || echo "")
YOSHOKU_ID=$($WP term get genre yoshoku --field=term_id 2>/dev/null || echo "")
RAMEN_ID=$($WP term get genre ramen --field=term_id 2>/dev/null || echo "")

if [ -n "$WASHOKU_ID" ]; then
  $WP term create genre "寿司・魚介" --slug=sushi --parent=$WASHOKU_ID --porcelain 2>/dev/null || true
  $WP term create genre "焼鳥・串焼き" --slug=yakitori --parent=$WASHOKU_ID --porcelain 2>/dev/null || true
fi
if [ -n "$YOSHOKU_ID" ]; then
  $WP term create genre "イタリアン" --slug=italian --parent=$YOSHOKU_ID --porcelain 2>/dev/null || true
  $WP term create genre "ハンバーガー" --slug=burger --parent=$YOSHOKU_ID --porcelain 2>/dev/null || true
fi
if [ -n "$RAMEN_ID" ]; then
  $WP term create genre "つけ麺" --slug=tsukemen --parent=$RAMEN_ID --porcelain 2>/dev/null || true
fi

# こだわり条件（shop_tag）
for TAG in "ランチあり:lunch" "ディナーのみ:dinner-only" "子連れOK:family-friendly" "一人席あり:solo-ok" "テイクアウト:takeout" "テラス席:terrace" "個室あり:private-room" "禁煙:non-smoking" "深夜営業:late-night" "食べ放題:all-you-can-eat"; do
  NAME="${TAG%%:*}"
  SLUG="${TAG##*:}"
  $WP term create shop_tag "$NAME" --slug="$SLUG" --porcelain 2>/dev/null || true
done

echo "タクソノミー登録完了"

# ----------------------------------------------------------
# 5. ダミー店舗データ登録（15店舗）
# ----------------------------------------------------------
echo ""
echo "[5/8] ダミー店舗データを登録 (15件)..."

create_shop() {
  local TITLE="$1"
  local CONTENT="$2"
  local GENRE="$3"
  local TAGS="$4"
  local ADDRESS="$5"
  local LAT="$6"
  local LNG="$7"
  local BUDGET="$8"
  local HOURS="$9"
  local CLOSED="${10}"
  local INSTA="${11}"
  local RATING="${12}"

  local POST_ID=$($WP post create \
    --post_type=shop \
    --post_title="$TITLE" \
    --post_content="$CONTENT" \
    --post_status=publish \
    --porcelain)

  # タクソノミー設定
  $WP post term set $POST_ID area kichijoji 2>/dev/null || true
  if [ -n "$GENRE" ]; then
    $WP post term set $POST_ID genre $GENRE 2>/dev/null || true
  fi
  if [ -n "$TAGS" ]; then
    $WP post term set $POST_ID shop_tag $TAGS 2>/dev/null || true
  fi

  # カスタムフィールド
  $WP post meta update $POST_ID shop_address "$ADDRESS"
  $WP post meta update $POST_ID shop_lat "$LAT"
  $WP post meta update $POST_ID shop_lng "$LNG"
  $WP post meta update $POST_ID shop_budget "$BUDGET"
  $WP post meta update $POST_ID shop_hours "$HOURS"
  $WP post meta update $POST_ID shop_closed "$CLOSED"
  $WP post meta update $POST_ID shop_instagram_url "$INSTA"
  $WP post meta update $POST_ID shop_rating_owner "$RATING"

  echo "  ✓ 登録完了: $TITLE (ID: $POST_ID)"
}

# 店舗1
create_shop \
  "鮨処 吉祥" \
  "吉祥寺駅北口から徒歩3分。旬の魚介を使った本格江戸前寿司が楽しめる。毎日市場から仕入れる新鮮なネタが自慢。カウンター席から職人の技を間近に見られる。" \
  "sushi" \
  "dinner-only private-room non-smoking" \
  "東京都武蔵野市吉祥寺本町1-8-5" \
  "35.7034" "139.5797" \
  "4000~6000" \
  "17:00〜23:00（L.O. 22:30）" \
  "月曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.8"

# 店舗2
create_shop \
  "麺屋 武蔵野らあめん" \
  "こだわりの鶏白湯スープと自家製麺が絶品。吉祥寺で行列のできる人気ラーメン店。限定メニューも毎月登場。駅から徒歩5分の好立地。" \
  "ramen" \
  "lunch solo-ok non-smoking" \
  "東京都武蔵野市吉祥寺南町1-2-3" \
  "35.7018" "139.5810" \
  "1000~2000" \
  "11:00〜22:00（スープなくなり次第終了）" \
  "火曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.5"

# 店舗3
create_shop \
  "CAFÉ BLEND 吉祥寺" \
  "スペシャルティコーヒーと自家製スイーツの専門カフェ。こだわりの豆を丁寧にハンドドリップ。週替わりのケーキセットが人気。一人利用も大歓迎。" \
  "cafe" \
  "lunch solo-ok takeout non-smoking" \
  "東京都武蔵野市吉祥寺本町2-14-7" \
  "35.7028" "139.5788" \
  "~1000" \
  "9:00〜20:00" \
  "水曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.3"

# 店舗4
create_shop \
  "イタリア食堂 ピアッツァ" \
  "本場イタリアで修業したシェフが作る本格パスタとピザ。薪窯で焼くナポリピッツァが人気。ランチのパスタセットはコスパ抜群。テラス席あり。" \
  "italian" \
  "lunch family-friendly terrace" \
  "東京都武蔵野市吉祥寺東町1-5-9" \
  "35.7041" "139.5821" \
  "2000~4000" \
  "11:30〜15:00 / 18:00〜22:30" \
  "月曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.4"

# 店舗5
create_shop \
  "焼鳥 とりまる" \
  "備長炭で丁寧に焼き上げる絶品焼鳥。吉祥寺の隠れた名店として地元民に愛されている。希少部位もあり、鳥好きには堪らない一軒。もつ煮込みも絶品。" \
  "yakitori" \
  "dinner-only late-night solo-ok" \
  "東京都武蔵野市吉祥寺本町1-15-2" \
  "35.7030" "139.5802" \
  "2000~4000" \
  "17:00〜24:00" \
  "日曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.6"

# 店舗6
create_shop \
  "吉祥寺バーガー KICHI" \
  "国産和牛100%パティのグルメバーガー専門店。ボリューム満点のバーガーは食べごたえ抜群。テイクアウトにも対応。井の頭公園近くで休日のランチに最適。" \
  "burger" \
  "lunch takeout family-friendly" \
  "東京都武蔵野市御殿山1-3-2" \
  "35.6985" "139.5776" \
  "1000~2000" \
  "11:00〜20:00" \
  "火曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.2"

# 店舗7
create_shop \
  "中国料理 龍門" \
  "本格中華料理の老舗。北京ダックや点心など、伝統的な中国料理をリーズナブルに楽しめる。家族連れにも人気の広々とした店内。宴会コースも充実。" \
  "asian" \
  "lunch family-friendly all-you-can-eat" \
  "東京都武蔵野市吉祥寺南町2-7-4" \
  "35.7011" "139.5815" \
  "2000~4000" \
  "11:30〜14:30 / 17:30〜22:00" \
  "水曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.1"

# 店舗8
create_shop \
  "つけ麺 武蔵" \
  "濃厚魚介豚骨スープのつけ麺専門店。もちもちの極太麺とたっぷりのチャーシューが自慢。吉祥寺でナンバーワンの呼び声高いつけ麺。行列必至の人気店。" \
  "tsukemen" \
  "lunch solo-ok non-smoking" \
  "東京都武蔵野市吉祥寺本町1-19-4" \
  "35.7033" "139.5793" \
  "1000~2000" \
  "11:00〜23:00" \
  "不定休" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.7"

# 店舗9
create_shop \
  "ビストロ シャポー" \
  "フランス料理をカジュアルに楽しめるビストロ。厳選素材を使ったコースメニューは記念日にも最適。ワインの品揃えも豊富。静かな雰囲気で大人の時間を演出。" \
  "yoshoku" \
  "dinner-only private-room non-smoking" \
  "東京都武蔵野市吉祥寺北町1-2-8" \
  "35.7058" "139.5791" \
  "4000~6000" \
  "18:00〜23:00" \
  "月・火曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.5"

# 店舗10
create_shop \
  "和食ダイニング 結" \
  "旬の食材を使った創作和食。おしゃれな空間で本格的な和の味を楽しめる。ランチの定食は地元会社員に大人気。夜は一品料理とお酒でゆっくり過ごせる。" \
  "washoku" \
  "lunch dinner-only solo-ok non-smoking" \
  "東京都武蔵野市吉祥寺本町2-3-12" \
  "35.7025" "139.5801" \
  "2000~4000" \
  "11:30〜14:30 / 17:00〜22:30" \
  "日曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.3"

# 店舗11
create_shop \
  "パンケーキ&カフェ FLUFFY" \
  "ふわふわのスフレパンケーキが名物のカフェ。インスタ映えするビジュアルで休日は行列必至。スムージーやフルーツサンドも人気。女性客に圧倒的人気。" \
  "cafe" \
  "lunch solo-ok non-smoking" \
  "東京都武蔵野市吉祥寺本町1-11-6" \
  "35.7031" "139.5795" \
  "1000~2000" \
  "10:00〜20:00（L.O. 19:30）" \
  "木曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.4"

# 店舗12
create_shop \
  "居酒屋 八海山" \
  "全国各地の日本酒を100種類以上取り揃える居酒屋。旬の肴とお酒を楽しむ大人の空間。日本酒初心者向けのおすすめプランもあり。週末は予約必須。" \
  "izakaya" \
  "dinner-only late-night solo-ok" \
  "東京都武蔵野市吉祥寺南町1-4-7" \
  "35.7020" "139.5805" \
  "2000~4000" \
  "17:00〜翌2:00" \
  "無休" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.2"

# 店舗13
create_shop \
  "タイ料理 サワディー" \
  "本場タイ出身のシェフが作る本格タイ料理。グリーンカレーやパッタイは絶品。スパイス好きにはたまらない一軒。ランチセットがお得でリピーター続出。" \
  "asian" \
  "lunch family-friendly takeout" \
  "東京都武蔵野市吉祥寺東町2-9-3" \
  "35.7048" "139.5828" \
  "1000~2000" \
  "11:30〜15:00 / 17:30〜22:00" \
  "月曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.3"

# 店舗14
create_shop \
  "炭火焼肉 和牛苑" \
  "黒毛和牛の最高級部位を炭火で堪能できる焼肉店。個室完備で接待やお祝いにも最適。ソムリエが選ぶワインとの相性も抜群。特上カルビが看板メニュー。" \
  "washoku" \
  "dinner-only private-room non-smoking" \
  "東京都武蔵野市吉祥寺本町1-24-1" \
  "35.7036" "139.5801" \
  "6000~" \
  "17:00〜23:00（L.O. 22:00）" \
  "月曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.9"

# 店舗15
create_shop \
  "ピザ&パスタ KICHIJOJI BASE" \
  "石窯で焼くナポリピッツァとホームメイドパスタのカジュアルイタリアン。子ども用メニューも充実で家族連れに大人気。テイクアウトのピザも好評。" \
  "italian" \
  "lunch family-friendly takeout terrace" \
  "東京都武蔵野市吉祥寺南町1-8-2" \
  "35.7015" "139.5808" \
  "2000~4000" \
  "11:30〜22:00" \
  "火曜日" \
  "https://www.instagram.com/kichijojigourmet/" \
  "4.1"

echo "店舗データ登録完了（15件）"

# ----------------------------------------------------------
# 6. 固定ページ作成
# ----------------------------------------------------------
echo ""
echo "[6/8] 固定ページを作成..."

# マイページ
$WP post create \
  --post_type=page \
  --post_title="マイページ" \
  --post_name=mypage \
  --post_content="ログインユーザーのマイページです。" \
  --post_status=publish \
  --porcelain 2>/dev/null || echo "  ※マイページはすでに存在します"
echo "  ✓ マイページ作成"

# お問い合わせ
$WP post create \
  --post_type=page \
  --post_title="お問い合わせ" \
  --post_name=contact \
  --post_content="<h2>お問い合わせ</h2>
<p>グルメポータル例へのお問い合わせは、以下のフォームよりお送りください。</p>
<p>お名前：<br><input type='text' name='name' placeholder='山田 太郎'></p>
<p>メールアドレス：<br><input type='email' name='email' placeholder='example@email.com'></p>
<p>お問い合わせ内容：<br><textarea rows='5' name='message'></textarea></p>
<p><button type='submit'>送信する</button></p>" \
  --post_status=publish \
  --porcelain 2>/dev/null || echo "  ※お問い合わせページはすでに存在します"
echo "  ✓ お問い合わせページ作成"

# プライバシーポリシー
$WP post create \
  --post_type=page \
  --post_title="プライバシーポリシー" \
  --post_name=privacy-policy \
  --post_content="<h2>プライバシーポリシー</h2>
<p>グルメポータル例（以下「当サイト」）は、ユーザーの個人情報の取り扱いについて、以下のとおりプライバシーポリシーを定めます。</p>

<h3>収集する情報</h3>
<p>当サイトでは、会員登録時にメールアドレス・ニックネームを収集します。</p>

<h3>利用目的</h3>
<p>収集した情報は、サービスの提供・改善および口コミ機能の運営のためのみに使用します。第三者への提供は行いません。</p>

<h3>Cookieについて</h3>
<p>当サイトはCookieを使用してログイン状態の維持を行います。</p>

<h3>お問い合わせ</h3>
<p>プライバシーポリシーに関するお問い合わせは、お問い合わせページよりお願いします。</p>

<p>制定日：$(date +%Y年%m月%d日)</p>" \
  --post_status=publish \
  --porcelain 2>/dev/null || echo "  ※プライバシーポリシーページはすでに存在します"
echo "  ✓ プライバシーポリシーページ作成"

# 利用規約
$WP post create \
  --post_type=page \
  --post_title="利用規約" \
  --post_name=terms \
  --post_content="<h2>利用規約</h2>
<p>グルメポータル例（以下「当サイト」）の利用にあたっては、以下の規約に同意いただいた上でご利用ください。</p>

<h3>第1条（目的）</h3>
<p>本規約は、当サイトが提供するグルメ情報ポータルサービスの利用条件を定めるものです。</p>

<h3>第2条（会員登録）</h3>
<p>口コミの投稿・お気に入り機能を利用するには、会員登録が必要です。虚偽の情報での登録は禁止します。</p>

<h3>第3条（禁止事項）</h3>
<p>以下の行為を禁止します：</p>
<ul>
  <li>虚偽・誹謗中傷を含む口コミの投稿</li>
  <li>第三者の権利を侵害する行為</li>
  <li>サービスの正常な運営を妨害する行為</li>
</ul>

<h3>第4条（免責事項）</h3>
<p>当サイトに掲載する情報の正確性について万全を期しますが、完全性・正確性を保証するものではありません。</p>

<p>制定日：$(date +%Y年%m月%d日)</p>" \
  --post_status=publish \
  --porcelain 2>/dev/null || echo "  ※利用規約ページはすでに存在します"
echo "  ✓ 利用規約ページ作成"

# ----------------------------------------------------------
# 7. ダミーレビュー登録（Site Reviewsがある場合）
# ----------------------------------------------------------
echo ""
echo "[7/8] サンプルレビューを登録..."

# WP_Post型で口コミをコメントとして登録（Site Reviews用）
# Site Reviewsは独自のpost_type=site-reviewを使う
if $WP post-type exists site-review 2>/dev/null; then
  echo "  Site Reviews が確認できました。レビューを登録します..."
  # IDを取得して最初の3店舗にレビューを追加
  SHOP_IDS=$($WP post list --post_type=shop --posts_per_page=3 --fields=ID --format=csv 2>/dev/null | tail -n +2)

  for SHOP_ID in $SHOP_IDS; do
    SHOP_TITLE=$($WP post get $SHOP_ID --field=post_title 2>/dev/null)

    $WP post create \
      --post_type=site-review \
      --post_title="とても美味しかったです！" \
      --post_content="友人と訪れましたが、料理のクオリティが高く大満足でした。スタッフの対応も丁寧で、また必ず来たいと思います。吉祥寺に来たらぜひ立ち寄ってほしいお店です。" \
      --post_status=publish \
      --meta_input='{"rating":"5","author_name":"グルメ太郎","assigned_posts":"'$SHOP_ID'"}' \
      --porcelain 2>/dev/null || true
    echo "  ✓ ${SHOP_TITLE} にレビュー登録"
  done
else
  echo "  ※Site Reviewsのレビュー型は管理画面でのみ登録可能です（スキップ）"
fi

# ----------------------------------------------------------
# 8. 設定最終調整
# ----------------------------------------------------------
echo ""
echo "[8/8] 最終設定..."

# サイトタイトル確認
$WP option update blogname "グルメポータル例"
$WP option update blogdescription "吉祥寺グルメ情報ポータル | Instagram連動"

# フロントページをフロントページとして設定（テーマのfront-page.phpが使われるよう）
$WP option update show_on_front "posts"

# ディスカッション設定（コメント承認制）
$WP option update comment_moderation 1
$WP option update comment_whitelist 0

# コメントスパム対策
$WP option update require_name_email 1

# 会員登録を許可（Theme My Login用）
$WP option update users_can_register 1
$WP option update default_role subscriber

echo "設定完了"

echo ""
echo "=================================================="
echo "  ✅ セットアップ完了!"
echo ""
echo "  WordPress: http://localhost:8081"
echo "  管理画面:  http://localhost:8081/wp-admin/"
echo "  店舗一覧:  http://localhost:8081/shop/"
echo "=================================================="
