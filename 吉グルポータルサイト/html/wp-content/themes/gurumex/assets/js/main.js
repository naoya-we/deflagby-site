/**
 * main.js — 共通JavaScript
 * グルメポータル例テーマ
 */

(function () {
  'use strict';

  // ===================================================
  // DOMContentLoaded
  // ===================================================
  document.addEventListener('DOMContentLoaded', function () {
    initHamburger();
    initFilterTagToggle();
    initFavoriteButtons();
  });


  // ===================================================
  // ハンバーガーメニュー
  // ===================================================
  function initHamburger() {
    const btn     = document.getElementById('hamburger-btn');
    const drawer  = document.getElementById('nav-drawer');
    const overlay = document.getElementById('nav-overlay');
    if (!btn || !drawer) return;

    function openDrawer() {
      btn.classList.add('is-active');
      drawer.classList.add('is-open');
      btn.setAttribute('aria-expanded', 'true');
      document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
      btn.classList.remove('is-active');
      drawer.classList.remove('is-open');
      btn.setAttribute('aria-expanded', 'false');
      document.body.style.overflow = '';
    }

    btn.addEventListener('click', function () {
      const isOpen = drawer.classList.contains('is-open');
      isOpen ? closeDrawer() : openDrawer();
    });

    if (overlay) {
      overlay.addEventListener('click', closeDrawer);
    }

    // Escキーで閉じる
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
        closeDrawer();
        btn.focus();
      }
    });
  }


  // ===================================================
  // フィルタータグのチェックボックストグル
  // ===================================================
  function initFilterTagToggle() {
    const filterTags = document.querySelectorAll('.filter-tag');
    filterTags.forEach(function (label) {
      const input = label.querySelector('input[type="checkbox"]');
      if (!input) return;
      input.addEventListener('change', function () {
        label.classList.toggle('is-active', input.checked);
      });
    });
  }


  // ===================================================
  // お気に入りボタン（AJAX）
  // ===================================================
  function initFavoriteButtons() {
    document.querySelectorAll('.js-fav-btn').forEach(function (btn) {
      btn.addEventListener('click', handleFavoriteClick);
    });
  }

  function handleFavoriteClick(e) {
    e.preventDefault();
    const btn    = e.currentTarget;
    const shopId = btn.dataset.shopId;

    if (!window.gurumexFav) return;

    // 未ログインの場合
    if (!gurumexFav.loggedIn) {
      window.location.href = gurumexFav.loginUrl;
      return;
    }

    // 連続クリック防止
    if (btn.dataset.loading === 'true') return;
    btn.dataset.loading = 'true';

    const formData = new FormData();
    formData.append('action',  'gurumex_toggle_favorite');
    formData.append('nonce',   gurumexFav.nonce);
    formData.append('shop_id', shopId);

    fetch(gurumexFav.ajaxUrl, {
      method: 'POST',
      body:   formData,
    })
      .then(function (res) { return res.json(); })
      .then(function (data) {
        if (data.success) {
          const isFav = data.data.is_fav;
          btn.classList.toggle('is-fav', isFav);
          btn.setAttribute('aria-pressed', isFav ? 'true' : 'false');

          // アイコン更新（ハートアイコン）
          const icon = btn.querySelector('.fav-btn__icon, .js-fav-icon');
          if (icon) icon.textContent = isFav ? '❤️' : '🤍';

          // テキスト更新（詳細ページ用）
          const text = btn.querySelector('.fav-btn__text');
          if (text) text.textContent = isFav ? 'お気に入り済み' : 'お気に入りに追加';

          // aria-label更新
          btn.setAttribute('aria-label', isFav ? 'お気に入りから外す' : 'お気に入りに追加');

          // カード内のハートも同期（詳細ページと一覧間の同期）
          document.querySelectorAll(`.js-fav-btn[data-shop-id="${shopId}"]`).forEach(function (b) {
            if (b === btn) return;
            b.classList.toggle('is-fav', isFav);
            b.textContent = isFav ? '❤️' : '🤍';
          });
        }
      })
      .catch(function (err) {
        console.error('お気に入り処理に失敗しました:', err);
      })
      .finally(function () {
        btn.dataset.loading = 'false';
      });
  }

})();
