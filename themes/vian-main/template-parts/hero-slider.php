<?php // === SLIDES TĨNH: sửa tên ảnh cho đúng thư mục của bạn === $slides = [ get_template_directory_uri().'/assets/images/slide2.jpg', ]; ?>
<section class="vian-hero">
    <div class="swiper vian-hero-swiper">
        <div class="swiper-wrapper"> <?php foreach ($slides as $src): ?> <div class="swiper-slide">
                <div class="vian-hero-slide"> <img class="vian-hero-img" src="<?php echo esc_url($src); ?>"
                        alt="Banner">
                    <div class="vian-hero-overlay"></div>
                </div>
            </div> <?php endforeach; ?>
</section>