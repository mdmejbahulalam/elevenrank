try { var t = localStorage.getItem('er-theme'); if (t) { document.documentElement.setAttribute('data-theme', t); } } catch (e) { /* storage blocked */ }
