<?php
/**
 * Hero slider – tối ưu LCP + lazy
 * - Slide đầu: fetchpriority="high", loading="eager"
 * - Slide sau: loading="lazy"
 * - Tự ưu tiên .avif/.webp nếu tồn tại, fallback .jpg
 * - NHỚ sửa $W/$H theo kích thước thật của ảnh hero để tránh CLS
 */

$W = 1440;   // TODO: đổi theo ảnh thật
$H = 700;    // TODO: đổi theo ảnh thật

// Khai báo theo basename ảnh trong /assets/images
$names = [
  'slide2',         // => slide2.avif|webp|jpg
  // 'slide3',
];

$dir = get_theme_file_path('assets/images/');
$uri = get_theme_file_uri('assets/images/');

$slides = [];
foreach ($names as $n) {
  $src = file_exists($dir.$n.'.avif') ? $uri.$n.'.avif'
       : (file_exists($dir.$n.'.webp') ? $uri.$n.'.webp' : $uri.$n.'.jpg');
  $slides[] = ['src' => $src, 'alt' => 'Banner'];
}
?>

<section class="vian-hero">
    <div class="swiper vian-hero-swiper">
        <div class="swiper-wrapper">
            <?php foreach ($slides as $i => $s): ?>
            <div class="swiper-slide">
                <div class="vian-hero-slide">
                    <img class="vian-hero-img" src="<?php echo esc_url($s['src']); ?>"
                        alt="<?php echo esc_attr($s['alt']); ?>" width="<?php echo (int)$W; ?>"
                        height="<?php echo (int)$H; ?>" decoding="async" <?php if ($i === 0): ?> fetchpriority="high"
                        loading="eager" <?php else: ?> loading="lazy" <?php endif; ?>>
                    <div class="vian-hero-overlay"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Nếu dùng Swiper pagination/nav thì đặt ở đây -->
    </div>
</section>