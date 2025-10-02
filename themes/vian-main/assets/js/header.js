// vian header mobile – robust cho nhiều markup WP
document.addEventListener('DOMContentLoaded', () => {
  const burger = document.querySelector('.vian-burger');
  // nav có thể là .main-nav hoặc nav[role=navigation]...
  const nav =
    document.querySelector('.main-nav') ||
    document.querySelector('nav[role="navigation"]') ||
    document.querySelector('.site-navigation');

  if (!burger || !nav) return;

  // list có thể là .nav-list / #primary-menu / .menu
  const list =
    nav.querySelector('.nav-list') ||
    nav.querySelector('#primary-menu') ||
    nav.querySelector('.menu');

  if (!list) return;

  // overlay (tạo nếu chưa có)
  let overlay = document.querySelector('.nav-overlay');
  if (!overlay) {
    overlay = document.createElement('div');
    overlay.className = 'nav-overlay';
    document.body.appendChild(overlay);
  }

  const open = () => {
    nav.classList.add('is-open');
    burger.classList.add('is-active');
    document.body.classList.add('no-scroll');
    overlay.classList.add('show');
  };
  const close = () => {
    nav.classList.remove('is-open');
    burger.classList.remove('is-active');
    document.body.classList.remove('no-scroll');
    overlay.classList.remove('show');
  };

  burger.addEventListener('click', () => {
    if (nav.classList.contains('is-open')) close();
    else open();
  });

  overlay.addEventListener('click', close);
  document.addEventListener('keydown', (e) => e.key === 'Escape' && close());
  list.querySelectorAll('a').forEach(a => a.addEventListener('click', close));

  // flag kiểm tra nhanh trên console
  window.VIAN_HEADER_READY = true;
  // console.log('VIAN header JS loaded');
});
