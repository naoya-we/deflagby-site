/**
 * Kichiguru Portal Main JavaScript
 * - Loading Screen Fadeout
 * - Interactive Map Trigger
 * - MotionPath Continuous Train Animation (id="train-icon" along id="train-track")
 * - Map Spots Pop-in Animation (tree&bard, Cherry Blossoms, cafe, pan, saron)
 * - Step-by-Step Search & Incremental Filter Logic
 * - GSAP ScrollTrigger Playful Random Stagger Fade-Up for Feature Story Cards (.js-fade-up-item)
 * - GSAP Instagram Continuous Vertical Loop
 */

document.addEventListener('DOMContentLoaded', () => {

  // Register GSAP Plugins
  if (typeof gsap !== 'undefined') {
    if (typeof ScrollTrigger !== 'undefined') gsap.registerPlugin(ScrollTrigger);
    if (typeof MotionPathPlugin !== 'undefined') gsap.registerPlugin(MotionPathPlugin);
  }

  // ==========================================
  // 1. LOADING SCREEN & HERO MAP ENTRANCE
  // ==========================================
  const loadingScreen = document.getElementById('loading-screen');
  
  const hideLoading = () => {
    if (loadingScreen && !loadingScreen.classList.contains('is-loaded')) {
      loadingScreen.classList.add('is-loaded');
      
      // Animate Hero Content entrance after loading
      if (typeof gsap !== 'undefined') {
        gsap.from('.hero-header-text', { opacity: 0, y: 24, duration: 0.8, ease: 'power2.out' });
        gsap.from('.svg-map-wrapper', { 
          opacity: 0, 
          scale: 0.96, 
          duration: 1, 
          delay: 0.2, 
          ease: 'power2.out',
          onComplete: initMapAnimations
        });
      }
    }
  };

  // Trigger loading complete after load or max 1.2s timeout
  window.addEventListener('load', () => {
    setTimeout(hideLoading, 600);
  });
  setTimeout(hideLoading, 1200);


  // ==========================================
  // 2. MAP ANIMATIONS (TRAIN MOTION PATH & SPOTS POP-IN)
  // ==========================================
  function initMapAnimations() {
    if (typeof gsap === 'undefined') return;

    // A. Train Back-and-Forth MotionPath Animation along id="train-track"
    const trainIcon = document.getElementById('train-icon');
    const trainTrack = document.getElementById('train-track');

    if (trainIcon && trainTrack && typeof MotionPathPlugin !== 'undefined') {
      gsap.to(trainIcon, {
        duration: 7,
        repeat: -1,
        yoyo: true,
        ease: 'power1.inOut',
        motionPath: {
          path: '#train-track',
          align: '#train-track',
          alignOrigin: [0.5, 0.5],
          autoRotate: true
        }
      });
    }

    // B. Spot Elements Pop-in ("ポンっ") Animation
    const spotSelectors = [
      '#cafe',
      '#pan',
      '#saron',
      '[id="Cherry Blossoms and a Pond"]',
      '#tree\\&amp\\;bard',
      '#tree\\&bard'
    ];

    const spotElements = [];
    spotSelectors.forEach(selector => {
      const elems = document.querySelectorAll(selector);
      elems.forEach(el => spotElements.push(el));
    });

    if (spotElements.length > 0) {
      gsap.fromTo(spotElements,
        {
          scale: 0,
          transformOrigin: 'center center',
          opacity: 0
        },
        {
          scale: 1,
          opacity: 1,
          duration: 0.7,
          stagger: 0.12,
          ease: 'back.out(1.8)',
          delay: 0.2
        }
      );
    }
  }


  // ==========================================
  // 3. STEP-BY-STEP SEARCH & FILTER LOGIC
  // ==========================================
  const areaBtns = document.querySelectorAll('#area-chip-group .chip-btn');
  const catBtns = document.querySelectorAll('#category-chip-group .chip-btn');
  const tagChipGroup = document.getElementById('tag-chip-group');
  const tagSearchInput = document.getElementById('tag-search-input');
  const articles = document.querySelectorAll('.story-card');

  let currentArea = 'all';
  let currentCategory = 'all';
  let currentTag = 'all';

  // Article Database Map
  const articleDataMap = Array.from(articles).map(art => ({
    element: art,
    area: art.dataset.area,
    category: art.dataset.category,
    tags: art.dataset.tags ? art.dataset.tags.split(',') : []
  }));

  // Update Available Categories based on selected Area
  function updateAvailableCategories() {
    const validCats = new Set(['all']);
    articleDataMap.forEach(item => {
      if (currentArea === 'all' || item.area === currentArea) {
        validCats.add(item.category);
      }
    });

    catBtns.forEach(btn => {
      const cat = btn.dataset.category;
      if (validCats.has(cat)) {
        btn.classList.remove('is-disabled');
      } else {
        btn.classList.add('is-disabled');
        if (cat === currentCategory) {
          currentCategory = 'all';
        }
      }
    });
  }

  // Update Dynamic Tags based on selected Area & Category
  function updateDynamicTags() {
    const validTags = new Set();
    articleDataMap.forEach(item => {
      const areaMatch = currentArea === 'all' || item.area === currentArea;
      const catMatch = currentCategory === 'all' || item.category === currentCategory;
      if (areaMatch && catMatch) {
        item.tags.forEach(t => validTags.add(t));
      }
    });

    tagChipGroup.innerHTML = '';
    
    // Add 'All Tags' button
    const allBtn = document.createElement('button');
    allBtn.className = `chip-btn ${currentTag === 'all' ? 'is-active' : ''}`;
    allBtn.textContent = 'すべて';
    allBtn.dataset.tag = 'all';
    allBtn.addEventListener('click', () => selectTag('all', allBtn));
    tagChipGroup.appendChild(allBtn);

    // Add extracted tags
    validTags.forEach(tag => {
      const btn = document.createElement('button');
      btn.className = `chip-btn ${currentTag === tag ? 'is-active' : ''}`;
      btn.textContent = `#${tag}`;
      btn.dataset.tag = tag;
      btn.addEventListener('click', () => selectTag(tag, btn));
      tagChipGroup.appendChild(btn);
    });
  }

  // Filter Cards
  function filterArticles() {
    const query = tagSearchInput.value.trim().toLowerCase();

    articleDataMap.forEach(item => {
      const areaMatch = currentArea === 'all' || item.area === currentArea;
      const catMatch = currentCategory === 'all' || item.category === currentCategory;
      const tagMatch = currentTag === 'all' || item.tags.includes(currentTag);
      
      // Incremental Search Query Match
      let textMatch = true;
      if (query !== '') {
        const titleText = item.element.querySelector('.card-title').textContent.toLowerCase();
        const tagText = item.tags.join(' ').toLowerCase();
        textMatch = titleText.includes(query) || tagText.includes(query);
      }

      if (areaMatch && catMatch && tagMatch && textMatch) {
        item.element.style.display = 'flex';
        gsap.to(item.element, { opacity: 1, scale: 1, duration: 0.3 });
      } else {
        item.element.style.display = 'none';
        item.element.style.opacity = '0';
      }
    });
  }

  // Event Listeners for Area Selection
  areaBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      areaBtns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      currentArea = btn.dataset.area;
      currentTag = 'all';
      updateAvailableCategories();
      updateDynamicTags();
      filterArticles();
    });
  });

  // Event Listeners for Category Selection
  catBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      if (btn.classList.contains('is-disabled')) return;
      catBtns.forEach(b => b.classList.remove('is-active'));
      btn.classList.add('is-active');
      currentCategory = btn.dataset.category;
      currentTag = 'all';
      updateDynamicTags();
      filterArticles();
    });
  });

  // Select Tag
  function selectTag(tag, btnElem) {
    const allTagBtns = tagChipGroup.querySelectorAll('.chip-btn');
    allTagBtns.forEach(b => b.classList.remove('is-active'));
    btnElem.classList.add('is-active');
    currentTag = tag;
    filterArticles();
  }

  // Incremental Keyword Input Listener
  tagSearchInput.addEventListener('input', () => {
    filterArticles();

    const query = tagSearchInput.value.trim().toLowerCase();
    const tagBtns = tagChipGroup.querySelectorAll('.chip-btn');
    tagBtns.forEach(btn => {
      const tagVal = btn.dataset.tag;
      if (tagVal === 'all' || query === '' || tagVal.includes(query)) {
        btn.style.display = 'inline-block';
      } else {
        btn.style.display = 'none';
      }
    });
  });

  // Initialize Step State
  updateAvailableCategories();
  updateDynamicTags();


  // ==========================================
  // 4. MAP CLICK TO SELECT AREA
  // ==========================================
  const mapStationIds = {
    'mitaka': 'mitaka',
    'kichijoji': 'kichijoji',
    'nishiogikubo': 'nishiogi'
  };

  Object.entries(mapStationIds).forEach(([svgId, areaVal]) => {
    const svgElem = document.getElementById(svgId);
    if (svgElem) {
      svgElem.addEventListener('click', () => {
        const targetBtn = document.querySelector(`#area-chip-group [data-area="${areaVal}"]`);
        if (targetBtn) {
          targetBtn.click();
          document.getElementById('search-section').scrollIntoView({ behavior: 'smooth' });
        }
      });
    }
  });


  // ==========================================
  // 5. PLAYFUL & LONGER DURATION GSAP FADE-UP (.js-fade-up-item)
  // ==========================================
  if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
    const fadeUpItems = document.querySelectorAll(".js-fade-up-item");

    if (fadeUpItems.length > 0) {
      gsap.fromTo(fadeUpItems, 
        {
          opacity: 0,
          y: () => gsap.utils.random(45, 90),
          rotation: () => gsap.utils.random(-3, 3),
          scale: () => gsap.utils.random(0.92, 0.96)
        },
        {
          opacity: 1,
          y: 0,
          rotation: 0,
          scale: 1,
          duration: 1.35, // 1.35秒かけて優雅・滑らかにゆっくり浮き上がる
          ease: "back.out(1.3)", // 心地よいクオリティの高い減速
          stagger: {
            amount: 0.5,
            from: "random"
          },
          scrollTrigger: {
            trigger: ".p-top-news__list",
            start: "top 85%",
            toggleActions: "play none none none"
          }
        }
      );
    }
  }


  // ==========================================
  // 6. INSTAGRAM DOUBLE VERTICAL INFINITE LOOP
  // ==========================================
  function setupVerticalInfiniteLoop(trackId, duration, direction = 'down') {
    const track = document.getElementById(trackId);
    if (!track) return;

    const items = Array.from(track.children);
    items.forEach(item => {
      const clone = item.cloneNode(true);
      track.appendChild(clone);
    });

    const totalHeight = track.scrollHeight / 2;

    if (direction === 'up') {
      gsap.to(track, {
        y: -totalHeight,
        duration: duration,
        ease: 'none',
        repeat: -1
      });
    } else {
      gsap.fromTo(track, 
        { y: -totalHeight },
        {
          y: 0,
          duration: duration,
          ease: 'none',
          repeat: -1
        }
      );
    }
  }

  setupVerticalInfiniteLoop('insta-track-a', 18, 'up');
  setupVerticalInfiniteLoop('insta-track-b', 24, 'down');

});
