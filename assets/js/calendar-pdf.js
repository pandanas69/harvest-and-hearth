document.addEventListener('DOMContentLoaded', function () {
  const btn = document.getElementById('download-pdf');
  if (!btn || typeof window.html2pdf === 'undefined') return;

  btn.addEventListener('click', function () {
    const element = document.getElementById('site-content');
    window.html2pdf().from(element).save('planting-calendar.pdf');
  });
});

