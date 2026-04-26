import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
//── Beauty Enhancements ──
/**
 * American Beauty — Premium JS Enhancements
 * Drop inside @push('scripts') after existing script block
 */

document.addEventListener('DOMContentLoaded', () => {

    /* ═══════════════════════════════════════════════
       1. SCROLL-TRIGGERED ENTRANCE ANIMATIONS
       Elements gracefully fade + slide in as they
       enter the viewport.
    ═══════════════════════════════════════════════ */
    const revealCSS = document.createElement('style');
    revealCSS.textContent = `
    .reveal {
      opacity: 0;
      transform: translateY(32px);
      transition: opacity .65s cubic-bezier(.22,1,.36,1),
                  transform .65s cubic-bezier(.22,1,.36,1);
    }
    .reveal.visible {
      opacity: 1;
      transform: translateY(0);
    }
    .reveal-left {
      opacity: 0;
      transform: translateX(-32px);
      transition: opacity .65s cubic-bezier(.22,1,.36,1),
                  transform .65s cubic-bezier(.22,1,.36,1);
    }
    .reveal-left.visible { opacity: 1; transform: translateX(0); }
    .reveal-scale {
      opacity: 0;
      transform: scale(.93);
      transition: opacity .6s cubic-bezier(.22,1,.36,1),
                  transform .6s cubic-bezier(.22,1,.36,1);
    }
    .reveal-scale.visible { opacity: 1; transform: scale(1); }
  `;
    document.head.appendChild(revealCSS);

    // Tag elements for reveal
    document.querySelectorAll(
        '.cat-card, .product-card, .feature, .section-header, .hero-stat, .hero-stat-rating, .banner-strip h2, .banner-strip p'
    ).forEach((el, i) => {
        el.classList.add('reveal');
        el.style.transitionDelay = `${(i % 6) * 60}ms`; // stagger within rows
    });

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                revealObserver.unobserve(e.target);
            }
        });
    }, { threshold: 0.12 });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-scale')
        .forEach(el => revealObserver.observe(el));


    /* ═══════════════════════════════════════════════
       2. ANIMATED STATS COUNTER
       Numbers count up from 0 when they scroll in.
    ═══════════════════════════════════════════════ */
    const statsData = [
        { el: null, target: 500, suffix: '+', label: 'Products' },
        { el: null, target: 10, suffix: 'K+', label: 'Happy Clients' },
        { el: null, target: 100, suffix: '%', label: 'Authentic' },
        { el: null, target: 4.9, suffix: ' / 5', label: null, decimals: 1 },
    ];

    document.querySelectorAll('.hero-stat strong, .hero-stat-rating strong').forEach((el, i) => {
        if (statsData[i]) statsData[i].el = el;
    });

    function animateCounter(obj) {
        if (!obj.el) return;
        const duration = 1600;
        const start = performance.now();
        const decimals = obj.decimals || 0;

        function tick(now) {
            const progress = Math.min((now - start) / duration, 1);
            // Ease out expo
            const eased = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
            const value = eased * obj.target;
            obj.el.textContent = (decimals ? value.toFixed(decimals) : Math.floor(value)) + obj.suffix;
            if (progress < 1) requestAnimationFrame(tick);
        }
        requestAnimationFrame(tick);
    }

    const statsSection = document.querySelector('.hero-stats');
    if (statsSection) {
        const statsObserver = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    statsData.forEach(animateCounter);
                    statsObserver.disconnect();
                }
            });
        }, { threshold: 0.5 });
        statsObserver.observe(statsSection);
    }


    /* ═══════════════════════════════════════════════
       3. 3-D TILT ON PRODUCT & CATEGORY CARDS
       Mouse-tracking perspective tilt.
    ═══════════════════════════════════════════════ */
    function addTilt(selector, intensity = 10) {
        document.querySelectorAll(selector).forEach(card => {
            card.style.transformStyle = 'preserve-3d';
            card.style.willChange = 'transform';

            card.addEventListener('mousemove', e => {
                const r = card.getBoundingClientRect();
                const x = (e.clientX - r.left) / r.width - 0.5;
                const y = (e.clientY - r.top) / r.height - 0.5;
                card.style.transform =
                    `perspective(600px) rotateY(${x * intensity}deg) rotateX(${-y * intensity}deg) translateZ(6px)`;
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform =
                    'perspective(600px) rotateY(0deg) rotateX(0deg) translateZ(0)';
                card.style.transition = 'transform .45s cubic-bezier(.22,1,.36,1)';
                setTimeout(() => card.style.transition = '', 450);
            });

            card.addEventListener('mouseenter', () => {
                card.style.transition = 'transform .12s ease';
            });
        });
    }

    addTilt('.product-card', 8);
    addTilt('.cat-card', 10);


    /* ═══════════════════════════════════════════════
       4. SMOOTH FADE TAB SWITCHING
       Replace the instant show/hide with crossfade.
    ═══════════════════════════════════════════════ */
    const tabCSS = document.createElement('style');
    tabCSS.textContent = `
    .product-grid {
      transition: opacity .3s ease, transform .3s ease;
    }
    .product-grid.tab-hidden {
      display: none !important;
    }
    .product-grid.tab-fade-in {
      animation: tabFadeIn .35s cubic-bezier(.22,1,.36,1) forwards;
    }
    @keyframes tabFadeIn {
      from { opacity: 0; transform: translateY(12px); }
      to   { opacity: 1; transform: translateY(0); }
    }
  `;
    document.head.appendChild(tabCSS);

    // Override the global showTab
    window.showTab = function (name, btn) {
        ['featured', 'new', 'best'].forEach(t => {
            const el = document.getElementById('tab-' + t);
            if (el) {
                el.style.display = 'none';
                el.classList.remove('tab-fade-in');
            }
        });
        document.querySelectorAll('.tab').forEach(b => b.classList.remove('active'));

        const target = document.getElementById('tab-' + name);
        if (target) {
            target.style.display = 'grid';
            // Trigger reflow then animate
            void target.offsetWidth;
            target.classList.add('tab-fade-in');

            // Re-observe new cards that just appeared
            target.querySelectorAll('.reveal:not(.visible)').forEach(el => {
                el.classList.add('visible');
            });
        }
        btn.classList.add('active');
    };


    /* ═══════════════════════════════════════════════
       5. PROMO BAR DISMISS
       Slide up and remember with sessionStorage.
    ═══════════════════════════════════════════════ */
    const promoBar = document.querySelector('.promo-bar');
    if (promoBar && !sessionStorage.getItem('promo-dismissed')) {
        // Inject close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '✕';
        closeBtn.style.cssText = `
      position: absolute; right: 1.2rem; top: 50%; transform: translateY(-50%);
      background: rgba(255,255,255,.25); border: none; color: #fff;
      width: 22px; height: 22px; border-radius: 50%; cursor: pointer;
      font-size: .7rem; line-height: 1; display: flex; align-items: center;
      justify-content: center; transition: background .2s;
    `;
        closeBtn.addEventListener('mouseenter', () => closeBtn.style.background = 'rgba(255,255,255,.45)');
        closeBtn.addEventListener('mouseleave', () => closeBtn.style.background = 'rgba(255,255,255,.25)');
        promoBar.style.position = 'relative';
        promoBar.appendChild(closeBtn);

        closeBtn.addEventListener('click', () => {
            promoBar.style.transition = 'max-height .4s ease, opacity .3s ease, padding .4s ease';
            promoBar.style.overflow = 'hidden';
            promoBar.style.maxHeight = promoBar.offsetHeight + 'px';
            void promoBar.offsetWidth;
            promoBar.style.maxHeight = '0';
            promoBar.style.opacity = '0';
            promoBar.style.padding = '0';
            sessionStorage.setItem('promo-dismissed', '1');
            setTimeout(() => promoBar.remove(), 450);
        });
    } else if (promoBar && sessionStorage.getItem('promo-dismissed')) {
        promoBar.remove();
    }


    /* ═══════════════════════════════════════════════
       6. BOOK BUTTON PULSE ANIMATION
       Gentle heartbeat pulse on the video overlay CTA.
    ═══════════════════════════════════════════════ */
    const pulseCSS = document.createElement('style');
    pulseCSS.textContent = `
    @keyframes bookPulse {
      0%, 100% { box-shadow: 0 6px 24px rgba(200,53,157,.45), 0 0 0 0 rgba(200,53,157,.4); }
      60%       { box-shadow: 0 6px 24px rgba(200,53,157,.45), 0 0 0 12px rgba(200,53,157,0); }
    }
    .book-btn {
      animation: bookPulse 2.4s ease-in-out infinite;
    }
    .book-btn:hover {
      animation: none;
    }
  `;
    document.head.appendChild(pulseCSS);


    /* ═══════════════════════════════════════════════
       7. CART BUTTON RIPPLE EFFECT
       Material-style ripple on "Add to Cart".
    ═══════════════════════════════════════════════ */
    const rippleCSS = document.createElement('style');
    rippleCSS.textContent = `
    .btn-add-cart { position: relative; overflow: hidden; }
    .ripple-wave {
      position: absolute;
      border-radius: 50%;
      background: rgba(255,255,255,.35);
      transform: scale(0);
      animation: rippleAnim .55s linear;
      pointer-events: none;
    }
    @keyframes rippleAnim {
      to { transform: scale(4); opacity: 0; }
    }
  `;
    document.head.appendChild(rippleCSS);

    document.addEventListener('click', e => {
        const btn = e.target.closest('.btn-add-cart');
        if (!btn) return;
        const r = btn.getBoundingClientRect();
        const size = Math.max(r.width, r.height);
        const wave = document.createElement('span');
        wave.className = 'ripple-wave';
        wave.style.cssText = `
      width: ${size}px; height: ${size}px;
      left: ${e.clientX - r.left - size / 2}px;
      top:  ${e.clientY - r.top - size / 2}px;
    `;
        btn.appendChild(wave);
        wave.addEventListener('animationend', () => wave.remove());
    });


    /* ═══════════════════════════════════════════════
       8. CATEGORY CARD SHIMMER ON HOVER
       Light sweep effect across cat cards.
    ═══════════════════════════════════════════════ */
    const shimmerCSS = document.createElement('style');
    shimmerCSS.textContent = `
    .cat-card::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(
        105deg,
        transparent 40%,
        rgba(255,255,255,.55) 50%,
        transparent 60%
      );
      background-size: 200% 100%;
      background-position: -100% 0;
      border-radius: inherit;
      pointer-events: none;
      transition: background-position 0s;
      opacity: 0;
    }
    .cat-card:hover::after {
      opacity: 1;
      background-position: 200% 0;
      transition: background-position .55s ease, opacity .1s;
    }
  `;
    document.head.appendChild(shimmerCSS);


    /* ═══════════════════════════════════════════════
       9. SMOOTH SCROLL FOR ALL ANCHOR LINKS
    ═══════════════════════════════════════════════ */
    document.querySelectorAll('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const target = document.querySelector(a.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });


    /* ═══════════════════════════════════════════════
       10. NAVBAR SHADOW ON SCROLL
       Adds depth to the nav as the page scrolls.
    ═══════════════════════════════════════════════ */
    const navbar = document.querySelector('nav, header, .navbar');
    if (navbar) {
        const navCSS = document.createElement('style');
        navCSS.textContent = `
      nav, header, .navbar {
        transition: box-shadow .3s ease, background .3s ease;
      }
      .nav-scrolled {
        box-shadow: 0 4px 24px rgba(123,47,190,.12) !important;
        background: rgba(255,255,255,.97) !important;
        backdrop-filter: blur(12px);
      }
    `;
        document.head.appendChild(navCSS);

        window.addEventListener('scroll', () => {
            navbar.classList.toggle('nav-scrolled', window.scrollY > 30);
        }, { passive: true });
    }


    /* ═══════════════════════════════════════════════
       11. HERO TEXT WORD-BY-WORD ENTRANCE
       Each word in the hero title animates in
       with a slight stagger on page load.
    ═══════════════════════════════════════════════ */
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        const heroEntranceCSS = document.createElement('style');
        heroEntranceCSS.textContent = `
      .hero-content { animation: heroFade .8s cubic-bezier(.22,1,.36,1) both; }
      @keyframes heroFade {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
      }
      .hero-eyebrow  { animation: heroFade .7s .1s cubic-bezier(.22,1,.36,1) both; }
      .hero-title    { animation: heroFade .7s .2s cubic-bezier(.22,1,.36,1) both; }
      .hero-sub      { animation: heroFade .7s .35s cubic-bezier(.22,1,.36,1) both; }
      .hero-btns     { animation: heroFade .7s .48s cubic-bezier(.22,1,.36,1) both; }
      .hero-stats    { animation: heroFade .7s .6s  cubic-bezier(.22,1,.36,1) both; }
      .hero-image    { animation: heroFade .9s .25s cubic-bezier(.22,1,.36,1) both; }
    `;
        document.head.appendChild(heroEntranceCSS);
    }


    /* ═══════════════════════════════════════════════
       12. WISHLIST HEART TOGGLE
       Heart button toggles filled/empty state.
    ═══════════════════════════════════════════════ */
    const wishCSS = document.createElement('style');
    wishCSS.textContent = `
    .product-wish.wished { color: #C8359D !important; }
    .product-wish.wished i { animation: heartPop .3s cubic-bezier(.36,.07,.19,.97); }
    @keyframes heartPop {
      0%   { transform: scale(1); }
      40%  { transform: scale(1.4); }
      100% { transform: scale(1); }
    }
  `;
    document.head.appendChild(wishCSS);

    document.addEventListener('click', e => {
        const btn = e.target.closest('.product-wish');
        if (!btn) return;
        btn.classList.toggle('wished');
        const icon = btn.querySelector('i');
        if (icon) {
            if (btn.classList.contains('wished')) {
                icon.className = 'fas fa-heart';
            } else {
                icon.className = 'far fa-heart';
            }
        }
    });

});