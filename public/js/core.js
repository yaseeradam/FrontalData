
  document.querySelectorAll('.icon-text').forEach(item => {
    item.addEventListener('click', function() {
      document.querySelectorAll('.icon-text').forEach(el => {
        el.querySelector('i').classList.remove('text-primary');
        el.querySelector('span').classList.add('hidden');
        el.classList.remove('active');
      });
      item.querySelector('i').classList.add('text-primary');
      item.querySelector('span').classList.remove('hidden');
      item.classList.add('active');
    });
  });

  document.querySelectorAll('.icon-text').forEach(item => {
    if (item.href === window.location.href) {
      item.classList.add('active');
      item.querySelector('i').classList.add('text-primary');
      item.querySelector('span').classList.remove('hidden');
    }
  });
