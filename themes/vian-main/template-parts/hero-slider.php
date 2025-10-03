<?php
/**
 * Hero slider – tối ưu LCP + lazy + responsive
 * - Slide đầu: fetchpriority="high", loading="eager"
 * - Slide sau: loading="lazy", fetchpriority="low"
 * - Tự ưu tiên .avif/.webp nếu tồn tại, fallback .jpg
 * - NHỚ sửa $W/$H theo kích thước thật của ảnh hero
 */

$W = 1440;   // <-- đổi cho đúng ảnh thật
$H = 700;    // <-- đổi cho đúng ảnh thật

// Khai báo basename ảnh ở /assets/images (không kèm đuôi)
$names = ['slide2']; // ví dụ: slide2.[avif|webp|jpg], slide2-768.[...]
$dir   = get_theme_file_path('assets/images/');
$uri   = get_theme_file_uri('assets/images/');

function pick_src($base, $dir, $uri, $suffix='') {
  foreach (['.avif','.webp','.jpg','.jpeg','.png'] as $ext) {
    $p = $dir.$base.$suffix.$ext;
    if (file_exists($p)) return $uri.$base.$suffix.$ext;
  }
  return '';
}

$slides = [];
foreach ($names as $n) {
  $full = pick_src($n, $dir, $uri, '');
  $w768 = pick_src($n, $dir, $uri, '-768'); // nếu có bản 768, sẽ dùng cho srcset
  $slides[] = [
    'src'    => $full,
    'src768' => $w768 ?: $full,
    'alt'    => 'Banner'
  ];
}
?>
<section class="vian-hero">
    <div class="swiper vian-hero-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $i => $s): ?>
            <div class="swiper-slide">
                <div class="vian-hero-slide">
                    <img class="vian-hero-img" src="<?php echo esc_url($s['src']); ?>"
                        srcset="<?php echo esc_url($s['src768']); ?> 768w, <?php echo esc_url($s['src']); ?> 1440w"
                        sizes="100vw" width="<?php echo (int)$W; ?>" height="<?php echo (int)$H; ?>"
                        alt="<?php echo esc_attr($s['alt']); ?>" decoding="async" <?php if ($i === 0): ?>
                        fetchpriority="high" loading="eager" <?php else: ?> fetchpriority="low" loading="lazy"
                        <?php endif; ?>>
                    <div class="vian-hero-overlay"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- (tùy chọn) pagination/nav Swiper để đây -->
    </div>
</section>