/**
 * map.js — Leaflet.js 地図初期化
 * グルメポータル例テーマ
 */

(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    if (typeof L === 'undefined') {
      console.warn('Leaflet.js が読み込まれていません');
      return;
    }
    if (!window.gurumexMap) return;

    const mapEl = document.getElementById('shop-map');
    if (!mapEl) return;

    const config = window.gurumexMap;

    // ===================================================
    // マップ初期化
    // ===================================================
    const map = L.map('shop-map', {
      center:         config.center,
      zoom:           config.zoom,
      scrollWheelZoom: false, // モバイルでのスクロール干渉を防ぐ
    });

    // タイル（OpenStreetMap）
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom:     19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    }).addTo(map);

    // カスタムマーカーアイコン
    const shopIcon = L.divIcon({
      className: '',
      html: '<div style="width:32px;height:32px;background:#E8734A;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:2px solid #fff;box-shadow:0 2px 6px rgba(0,0,0,0.3);display:flex;align-items:center;justify-content:center;"><span style="transform:rotate(45deg);font-size:14px;">🍴</span></div>',
      iconSize:    [32, 32],
      iconAnchor:  [16, 32],
      popupAnchor: [0, -36],
    });

    // ===================================================
    // モード別処理
    // ===================================================
    if (config.mode === 'index' && config.locations && config.locations.length > 0) {
      // トップページ：全店舗ピン
      const bounds = [];

      config.locations.forEach(function (loc) {
        const marker = L.marker([loc.lat, loc.lng], { icon: shopIcon }).addTo(map);
        marker.bindPopup(
          '<div class="map-popup">' +
          '<p class="map-popup__name">' + escHtml(loc.name) + '</p>' +
          '<p class="map-popup__genre">' + escHtml(loc.genre) + '</p>' +
          '<a href="' + escHtml(loc.url) + '" class="map-popup__link">詳細を見る →</a>' +
          '</div>'
        );
        bounds.push([loc.lat, loc.lng]);
      });

      // 全ピンが見えるようにフィットさせる
      if (bounds.length > 1) {
        map.fitBounds(bounds, { padding: [30, 30], maxZoom: 16 });
      }

    } else if (config.mode === 'single') {
      // 詳細ページ：単一ピン
      const marker = L.marker(config.center, { icon: shopIcon }).addTo(map);
      marker.bindPopup(
        '<div class="map-popup"><p class="map-popup__name">' + escHtml(config.name) + '</p></div>'
      ).openPopup();
    }

    // スクロールホイール有効化ボタン（モバイルUX改善）
    map.on('focus', function () {
      map.scrollWheelZoom.enable();
    });
    map.on('blur', function () {
      map.scrollWheelZoom.disable();
    });
  });

  /**
   * XSS対策: HTMLエスケープ
   */
  function escHtml(str) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(str || ''));
    return div.innerHTML;
  }

})();
