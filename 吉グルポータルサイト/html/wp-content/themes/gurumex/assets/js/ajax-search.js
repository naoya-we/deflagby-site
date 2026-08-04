/**
 * ajax-search.js — AJAX絞り込み検索
 * グルメポータル例テーマ
 */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    const form    = document.getElementById('filter-form');
    const grid    = document.getElementById('search-results-grid');
    const spinner = document.getElementById('search-loading');
    const countEl = document.querySelector('.search-results-header__count strong');

    if (!form || !grid || !window.gurumexAjax) return;

    // フォーム送信をAJAXに差し替え
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      doSearch();
    });

    // セレクト変更でも自動検索
    form.querySelectorAll('select').forEach(function (sel) {
      sel.addEventListener('change', doSearch);
    });

    // タグチェックボックス変更でも自動検索
    form.querySelectorAll('input[type="checkbox"]').forEach(function (cb) {
      cb.addEventListener('change', doSearch);
    });

    function doSearch() {
      const area    = form.querySelector('[name="area"]')?.value  || '';
      const genre   = form.querySelector('[name="genre"]')?.value || '';
      const tagCbs  = form.querySelectorAll('[name="shop_tag[]"]:checked');
      const tags    = Array.from(tagCbs).map(function (cb) { return cb.value; });

      // スピナー表示
      if (spinner) {
        spinner.classList.add('is-visible');
        spinner.setAttribute('aria-busy', 'true');
      }
      grid.style.opacity = '0.5';
      grid.style.pointerEvents = 'none';

      const formData = new FormData();
      formData.append('action', 'gurumex_search');
      formData.append('nonce',  gurumexAjax.nonce);
      formData.append('area',   area);
      formData.append('genre',  genre);
      tags.forEach(function (tag) {
        formData.append('tags[]', tag);
      });

      fetch(gurumexAjax.ajaxUrl, {
        method: 'POST',
        body:   formData,
      })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data.success) {
            grid.innerHTML = data.data.html ||
              '<div class="empty-state" style="grid-column:1/-1">' +
              '<div class="empty-state__icon">😔</div>' +
              '<p class="empty-state__text">条件に合うお店が見つかりませんでした</p>' +
              '</div>';

            if (countEl) {
              countEl.textContent = data.data.count;
            }

            // お気に入りボタンの再バインド
            if (typeof initFavoriteButtons === 'function') {
              initFavoriteButtons();
            }
          }
        })
        .catch(function (err) {
          console.error('検索に失敗しました:', err);
        })
        .finally(function () {
          if (spinner) {
            spinner.classList.remove('is-visible');
            spinner.setAttribute('aria-busy', 'false');
          }
          grid.style.opacity = '1';
          grid.style.pointerEvents = '';
        });
    }
  });

})();
