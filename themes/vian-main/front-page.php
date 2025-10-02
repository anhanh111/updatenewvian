<?php get_header(); ?>

<main class="home-page">
    <section class="hero">
        <?php get_template_part('template-parts/hero-slider'); ?>
    </section>

    <section class="section-about">
        <?php get_template_part('template-parts/section-about'); ?>
    </section>

    <section class="section-food'">
        <?php get_template_part('template-parts/section-food'); ?>

    </section>
        <section class="section-space'">
        <?php get_template_part('template-parts/section-space'); ?>

    </section>
</main>

<?php get_footer(); ?>