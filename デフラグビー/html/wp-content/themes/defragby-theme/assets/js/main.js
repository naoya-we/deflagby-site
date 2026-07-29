/**
 * defragby-theme Main JS v3.2
 * - Mobile navigation (hamburger + dropdown)
 * - Mock Cart system (localStorage)
 * - Cart badge in header
 * - Add-to-cart feedback toast
 * - Quantity stepper on goods cards (+ / -)
 * - Book form: cart pre-fill + confirm popup
 */

/* ═══════════════════════════════════════════════════════════════════
   CART STORE — uses localStorage key "defragby_cart"
   Cart item shape: { id, name, price, qty }
   ═══════════════════════════════════════════════════════════════════ */
const Cart = {
  KEY: 'defragby_cart',

  load() {
    try { return JSON.parse(localStorage.getItem(this.KEY)) || []; }
    catch { return []; }
  },

  save(items) {
    localStorage.setItem(this.KEY, JSON.stringify(items));
  },

  add(item) {
    const items = this.load();
    const existing = items.find(i => i.id === item.id);
    if (existing) {
      existing.qty += item.qty;
    } else {
      items.push({ ...item });
    }
    this.save(items);
    return items;
  },

  /** Set absolute quantity. If qty <= 0, removes the item entirely. */
  setQty(id, qty) {
    let items = this.load();
    if (qty <= 0) {
      items = items.filter(i => i.id !== id);
    } else {
      const existing = items.find(i => i.id === id);
      if (existing) existing.qty = qty;
    }
    this.save(items);
    return items;
  },

  /** Get current qty for a specific item (0 if not in cart). */
  getQty(id) {
    const item = this.load().find(i => i.id === id);
    return item ? item.qty : 0;
  },

  totalQty() {
    return this.load().reduce((sum, i) => sum + i.qty, 0);
  },

  clear() {
    localStorage.removeItem(this.KEY);
  }
};


/* ═══════════════════════════════════════════════════════════════════
   BADGE — shows item count next to "グッズ" nav link
   ═══════════════════════════════════════════════════════════════════ */
function updateCartBadge() {
  // Find the nav link that goes to /goods/
  const goodsLink = document.querySelector('a[href*="/goods/"]:not([href*="/goods/"])');
  // Broader selector: any nav-menu link whose href contains "/goods/"
  const allNavLinks = document.querySelectorAll('.nav-menu a');
  let targetLink = null;
  allNavLinks.forEach(a => {
    if (a.href && a.href.includes('/goods/') && !a.href.includes('/goods/terms') && !a.href.includes('/goods/book')) {
      targetLink = a;
    }
  });

  if (!targetLink) return;

  // Remove existing badge
  const existing = targetLink.querySelector('.cart-nav-badge');
  if (existing) existing.remove();

  const qty = Cart.totalQty();
  if (qty > 0) {
    const badge = document.createElement('span');
    badge.className = 'cart-nav-badge';
    badge.textContent = qty;
    badge.setAttribute('aria-label', `カートに ${qty} 件`);
    targetLink.appendChild(badge);
  }
}


/* ═══════════════════════════════════════════════════════════════════
   TOAST NOTIFICATION
   ═══════════════════════════════════════════════════════════════════ */
function showToast(message, bookUrl) {
  // Remove existing toast
  const old = document.querySelector('.cart-toast');
  if (old) old.remove();

  const toast = document.createElement('div');
  toast.className = 'cart-toast cart-toast--success';
  toast.setAttribute('role', 'status');
  toast.setAttribute('aria-live', 'polite');
  toast.innerHTML = `
    <span class="cart-toast-icon"><i class="fa-solid fa-circle-check"></i></span>
    <div class="cart-toast-body">
      <span class="cart-toast-msg">${message}</span>
      <a href="${bookUrl}" class="cart-toast-link" target="_blank" rel="noopener">
        <i class="fa-solid fa-bag-shopping"></i> 予約へ進む
      </a>
    </div>
    <button type="button" class="cart-toast-close" aria-label="閉じる">
      <i class="fa-solid fa-xmark"></i>
    </button>
  `;
  document.body.appendChild(toast);

  // Trigger enter animation
  requestAnimationFrame(() => {
    requestAnimationFrame(() => toast.classList.add('cart-toast--show'));
  });

  // Manual close button
  toast.querySelector('.cart-toast-close').addEventListener('click', () => {
    toast.classList.remove('cart-toast--show');
    toast.addEventListener('transitionend', () => toast.remove(), { once: true });
  });

  // トーストはカートが空になるまで持続（自動クローズしない）
}


/* ═══════════════════════════════════════════════════════════════════
   CTA BUTTON — enable/disable based on cart state
   ═══════════════════════════════════════════════════════════════════ */
function updateGoodsPageCTA() {
  return;
}

function initGoodsPage() {
  return;
}



/* ═══════════════════════════════════════════════════════════════════
   BOOK (PRE-ORDER) PAGE — pre-fill cart items + confirm popup
   ═══════════════════════════════════════════════════════════════════ */
function initBookPage() {
  const cartPreview = document.getElementById('cart-preview');
  if (!cartPreview) return;

  const items = Cart.load();
  const lang  = new URLSearchParams(window.location.search).get('lang') === 'en' ? 'en' : 'ja';

  if (items.length === 0) {
    cartPreview.innerHTML = `
      <div class="cart-preview-empty">
        <i class="fa-solid fa-cart-shopping"></i>
        <p>${lang === 'en' ? 'No items in cart yet. <a href="/goods/">Browse merchandise →</a>' : 'カートに商品がありません。<a href="/goods/">グッズページへ →</a>'}</p>
      </div>`;
    return;
  }

  let html = `<ul class="cart-preview-list" role="list">`;
  let total = 0;
  items.forEach(item => {
    const lineTotal = parseInt(item.price.replace(/[^0-9]/g, ''), 10) * item.qty;
    total += lineTotal;
    html += `
      <li class="cart-preview-item">
        <span class="cpi-name">${item.name}</span>
        <span class="cpi-qty">× ${item.qty}</span>
        <span class="cpi-price">¥${lineTotal.toLocaleString()}</span>
        <button type="button" class="cpi-remove js-remove-item" data-id="${item.id}" aria-label="削除">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </li>`;
  });
  html += `</ul>
    <div class="cart-preview-total">
      <span>${lang === 'en' ? 'Total (est.)' : '合計（目安）'}</span>
      <strong>¥${total.toLocaleString()}</strong>
    </div>
    <p class="cart-preview-note">${lang === 'en' ? '* Payment at venue. Tax included.' : '※現地払い・税込表示'}</p>`;

  cartPreview.innerHTML = html;

  // Remove item buttons
  cartPreview.querySelectorAll('.js-remove-item').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.id;
      const current = Cart.load().filter(i => i.id !== id);
      Cart.save(current);
      updateCartBadge();
      initBookPage(); // re-render
    });
  });
}


/* ═══════════════════════════════════════════════════════════════════
   CONFIRM POPUP — fires when booking form submits
   ═══════════════════════════════════════════════════════════════════ */
function initBookForm() {
  const form = document.getElementById('book-form');
  if (!form) return;

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const lang = new URLSearchParams(window.location.search).get('lang') === 'en' ? 'en' : 'ja';
    const items = Cart.load();

    // Build popup
    let itemsHtml = '';
    if (items.length > 0) {
      itemsHtml = '<ul class="confirm-item-list">' +
        items.map(i => `<li>${i.name} × ${i.qty}</li>`).join('') +
        '</ul>';
    }

    const overlay = document.createElement('div');
    overlay.className = 'confirm-overlay';
    overlay.setAttribute('role', 'dialog');
    overlay.setAttribute('aria-modal', 'true');
    overlay.setAttribute('aria-label', lang === 'en' ? 'Booking Confirmed' : '予約受付完了');

    overlay.innerHTML = `
      <div class="confirm-modal">
        <div class="confirm-modal-header">
          <i class="fa-solid fa-circle-check"></i>
          <h2>${lang === 'en' ? 'Pre-Order Received!' : '予約を受け付けました！'}</h2>
        </div>
        <div class="confirm-modal-body">
          <p>${lang === 'en'
            ? 'Thank you! This is a demo — no data was transmitted. Your "order" details:'
            : 'ありがとうございます！これはデモです。実際のデータ送信は行われていません。ご予約内容：'}</p>
          ${itemsHtml}
          <p class="confirm-note">${lang === 'en'
            ? 'Please visit the venue merchandise counter to complete payment.'
            : '大会当日、グッズ受取カウンターにてお支払いをお願いします。'}</p>
        </div>
        <button type="button" class="confirm-close-btn js-confirm-close">
          ${lang === 'en' ? 'Close & Clear Cart' : '閉じてカートをクリア'}
        </button>
      </div>`;

    document.body.appendChild(overlay);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
      requestAnimationFrame(() => overlay.classList.add('confirm-overlay--show'));
    });

    overlay.querySelector('.js-confirm-close').addEventListener('click', () => {
      Cart.clear();
      updateCartBadge();
      overlay.classList.remove('confirm-overlay--show');
      overlay.addEventListener('transitionend', () => {
        overlay.remove();
        document.body.style.overflow = '';
        // Re-render cart preview
        initBookPage();
      }, { once: true });
    });

    // Close on overlay backdrop click
    overlay.addEventListener('click', e => {
      if (e.target === overlay) overlay.querySelector('.js-confirm-close').click();
    });
  });
}


/* ═══════════════════════════════════════════════════════════════════
   MOBILE NAV (updated: sync with 900px breakpoint)
   ═══════════════════════════════════════════════════════════════════ */
function initMobileNav() {
  const menuToggle     = document.querySelector('.menu-toggle');
  const mainNavigation = document.getElementById('site-navigation');

  if (menuToggle && mainNavigation) {
    menuToggle.addEventListener('click', () => {
      const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', String(!expanded));
      mainNavigation.classList.toggle('active');
      menuToggle.classList.toggle('active');
    });

    // Close on outside click
    document.addEventListener('click', e => {
      if (!menuToggle.contains(e.target) && !mainNavigation.contains(e.target)) {
        mainNavigation.classList.remove('active');
        menuToggle.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    });
  }

  // Mobile dropdown toggle (900px breakpoint synced with CSS)
  document.querySelectorAll('.menu-item-has-children > a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      if (window.innerWidth <= 900) {
        e.preventDefault();
        const sub = this.nextElementSibling;
        if (sub) sub.style.display = sub.style.display === 'block' ? 'none' : 'block';
      }
    });
  });
}


/* ═══════════════════════════════════════════════════════════════════
   SCROLL HEADER SHADOW
   ═══════════════════════════════════════════════════════════════════ */
function initScrollHeader() {
  const header = document.querySelector('.site-header');
  if (!header) return;
  const onScroll = () => header.classList.toggle('scrolled', window.scrollY > 10);
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}


/* ═══════════════════════════════════════════════════════════════════
   FRONT PAGE — New countdown (fp-countdown__num IDs)
   ═══════════════════════════════════════════════════════════════════ */
function initFrontPageCountdown() {
  const daysEl    = document.getElementById('days');
  const hoursEl   = document.getElementById('hours');
  const minsEl    = document.getElementById('minutes');
  const secsEl    = document.getElementById('seconds');
  const progressBar = document.getElementById('js-progress-bar');

  if (!daysEl) return;

  // Tournament start: 2026-10-31 10:00 JST
  const TARGET  = new Date('2026-10-31T10:00:00+09:00').getTime();
  // Announcement date for progress bar (approx 1 year before)
  const ORIGIN  = new Date('2025-10-31T10:00:00+09:00').getTime();
  const TOTAL   = TARGET - ORIGIN;

  function pad(n) { return String(Math.max(0, n)).padStart(2, '0'); }

  function tick() {
    const now  = Date.now();
    const diff = TARGET - now;

    if (diff <= 0) {
      daysEl.textContent  = '00';
      hoursEl.textContent = '00';
      minsEl.textContent  = '00';
      secsEl.textContent  = '00';
      if (progressBar) progressBar.style.width = '100%';
      return;
    }

    const d = Math.floor(diff / 86400000);
    const h = Math.floor((diff % 86400000) / 3600000);
    const m = Math.floor((diff % 3600000)  / 60000);
    const s = Math.floor((diff % 60000)    / 1000);

    daysEl.textContent  = pad(d);
    hoursEl.textContent = pad(h);
    minsEl.textContent  = pad(m);
    secsEl.textContent  = pad(s);

    // Progress bar: percentage elapsed since ORIGIN
    if (progressBar) {
      const pct = Math.min(100, Math.max(0, ((now - ORIGIN) / TOTAL) * 100));
      progressBar.style.width = pct.toFixed(2) + '%';
    }
  }

  tick();
  setInterval(tick, 1000);
}


/* ═══════════════════════════════════════════════════════════════════
   BOOT
   ═══════════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  initMobileNav();
  initScrollHeader();
  updateGoodsPageCTA();
  initGoodsPage();
  initBookPage();
  initBookForm();
  initFrontPageCountdown(); // ← new dynamic countdown
});
