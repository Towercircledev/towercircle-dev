// Towercircle site — small, no libraries.
const $ = (s, el = document) => el.querySelector(s);
const $$ = (s, el = document) => [...el.querySelectorAll(s)];

// Mobile menu
const btn = $('.menu-btn');
const nav = $('#nav');
btn?.addEventListener('click', () => {
    const open = nav.classList.toggle('open');
    btn.setAttribute('aria-expanded', open);
    document.body.style.overflow = open ? 'hidden' : '';
});

// Header shadow on scroll
const header = $('.header');
const onScroll = () => header?.classList.toggle('scrolled', scrollY > 10);
addEventListener('scroll', onScroll, { passive: true });
onScroll();

// Reveal + counters when they come into view
const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
        if (!e.isIntersecting) return;
        e.target.classList.add('in');
        if (e.target.dataset.count) countUp(e.target);
        io.unobserve(e.target);
    });
}, { rootMargin: '0px 0px -60px 0px' });
$$('.reveal, [data-count]').forEach((el) => io.observe(el));

function countUp(el) {
    const end = +el.dataset.count;
    const t0 = performance.now();
    const step = (t) => {
        const p = Math.min((t - t0) / 1400, 1);
        el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
        if (p < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
}

// Portfolio filter
$$('.filters button').forEach((b) => b.addEventListener('click', () => {
    $$('.filters button').forEach((x) => x.classList.toggle('on', x === b));
    const f = b.dataset.filter;
    $$('[data-cat]').forEach((p) => { p.hidden = f !== '*' && p.dataset.cat !== f; });
}));

// Forms: timestamp for the bot check, and stop double-submits
$$('form.lead-form').forEach((f) => {
    const t = f.querySelector('[name=_t]');
    if (t) t.value = Math.floor(Date.now() / 1000);
    f.addEventListener('submit', () => {
        const b = f.querySelector('[type=submit]');
        if (b) { b.disabled = true; b.textContent = 'Sending…'; }
    });
});

// Track WhatsApp / call clicks as Google Ads + GA4 events
$$('a[href^="https://wa.me"], a[href^="tel:"]').forEach((a) => a.addEventListener('click', () => {
    if (typeof gtag !== 'function') return;
    const kind = a.href.startsWith('tel:') ? 'call_click' : 'whatsapp_click';
    gtag('event', kind, { link_url: a.href });
}));
