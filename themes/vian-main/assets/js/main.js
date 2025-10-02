document.addEventListener('DOMContentLoaded', function () {
  // Danh sách ảnh banner
  const slides = [
    vian_theme_url + '/assets/images/slide1.jpg',
    vian_theme_url + '/assets/images/slide2.jpg',
    vian_theme_url + '/assets/images/slide3.jpg'
  ];

  let index = 0;
  const imgEl = document.getElementById('vian-hero-img');

  if (imgEl) {
    setInterval(() => {
      index = (index + 1) % slides.length;
      imgEl.src = slides[index];
    }, 4000); // 4 giây đổi 1 ảnh
  }
});
