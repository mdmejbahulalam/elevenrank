/* Progressive enhancement only. Every page works without this file. */
(function () {
  function toast(msg) {
    var t = document.createElement('div');
    t.className = 'toast';
    t.setAttribute('role', 'status');
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(function () { t.remove(); }, 2400);
  }
  document.addEventListener('click', function (e) {
    var c = e.target.closest('[data-copy]');
    if (c) {
      var text = c.getAttribute('data-copy');
      var done = function () { toast(c.getAttribute('data-copied') || 'Copied'); };
      if (navigator.clipboard) { navigator.clipboard.writeText(text).then(done, done); } else { done(); }
      return;
    }
    var o = e.target.closest('[data-open]');
    if (o) {
      var d = document.getElementById(o.getAttribute('data-open'));
      if (d && d.showModal) { d.showModal(); e.preventDefault(); }
      return;
    }
    var x = e.target.closest('[data-close]');
    if (x) { var m = x.closest('dialog'); if (m) { m.close(); } }
  });
  var tgl = document.querySelector('[data-theme-toggle]');
  if (tgl) {
    tgl.addEventListener('click', function () {
      var cur = document.documentElement.getAttribute('data-theme');
      var next = cur === 'dark' ? 'light' : 'dark';
      document.documentElement.setAttribute('data-theme', next);
      try { localStorage.setItem('er-theme', next); } catch (err) { /* private mode */ }
    });
  }
})();

/* Scroll reveal. Elements stay visible if this does not run (the .js class is set inline). */
(function () {
  var els = document.querySelectorAll('.rv');
  if (!els.length) return;
  if (!('IntersectionObserver' in window) || matchMedia('(prefers-reduced-motion: reduce)').matches) {
    els.forEach(function (e) { e.classList.add('in'); });
    return;
  }
  var io = new IntersectionObserver(function (list) {
    list.forEach(function (x) { if (x.isIntersecting) { x.target.classList.add('in'); io.unobserve(x.target); } });
  }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
  els.forEach(function (e) { io.observe(e); });
})();
