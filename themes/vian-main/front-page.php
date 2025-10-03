<?php get_header(); ?>

<main class="home-page">
    <section class="hero" aria-label="Hero">
        <?php
    // Truyền tham số cho template để set fetchpriority='high' cho ảnh LCP
    get_template_part('template-parts/hero-slider', null, ['fetchpriority' => 'high']);
    ?>
    </section>

    <section class="section-about" aria-label="Giới thiệu">
        <?php get_template_part('template-parts/section-about'); ?>
    </section>

    <section class="section-food" aria-label="Món ăn">
        <?php get_template_part('template-parts/section-food'); ?>
    </section>

    <section class="section-space" aria-label="Không gian">
        <?php get_template_part('template-parts/section-space'); ?>
    </section>
</main>

<?php get_footer(); ?>