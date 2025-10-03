<!doctype html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <header class="site-header py-2">
        <div class="container">
            <div class="row align-items-center g-3">

                <!-- Logo -->
                <div class="col-6 col-md-2">
                    <a class="site-logo d-inline-flex align-items-center" href="<?php echo esc_url(home_url('/')); ?>"
                        aria-label="<?php bloginfo('name'); ?>">
                        <?php if (has_custom_logo()) {
              the_custom_logo();
            } else {
              echo '<span class="vian-logo-text fw-bold">' . esc_html(get_bloginfo('name')) . '</span>';
            } ?>
                    </a>
                </div>
                <!-- MENU -->
                <nav class="main-nav" aria-label="<?php esc_attr_e('Primary', 'vian'); ?>">
                    <?php
          if (has_nav_menu('main_menu')) {
            wp_nav_menu([
              'theme_location' => 'main_menu',
              'container'      => false,
              'menu_class'     => 'nav-list',   // trùng với CSS bạn đang dùng
              'fallback_cb'    => false,
              'depth'          => 2,
            ]);
          } else {
            // Gợi ý nếu chưa gán vị trí
            echo '<ul class="nav-list"><li><a href="' . esc_url(admin_url('nav-menus.php')) . '">Thêm menu…</a></li></ul>';
          }
          ?>
                </nav>

                <!-- Search + Language + Burger -->
                <div class="col-6 col-md-3 d-flex justify-content-end align-items-center gap-2 order-2 order-md-3">
                    <form role="search" method="get" class="vian-search" action="<?php echo esc_url(home_url('/')); ?>">
                        <label class="visually-hidden" for="s"><?php _e('Search', 'vian'); ?></label>
                        <input id="s" type="search" name="s" class="form-control form-control-sm vian-search-input"
                            placeholder="<?php esc_attr_e('Tìm kiếm', 'vian'); ?>">
                    </form>

                    <div class="vian-lang ms-1">
                        <?php if (function_exists('pll_the_languages')) : ?>
                        <ul class="m-0 p-0 d-flex list-unstyled gap-2">
                            <?php pll_the_languages(['show_flags' => 0, 'show_names' => 1]); ?>
                        </ul>
                        <?php else: ?>
                        <a class="btn btn-link p-0 fw-bold" href="#">EN</a>
                        <?php endif; ?>
                    </div>

                    <button class="vian-burger d-inline-flex d-md-none ms-1" aria-label="Menu" aria-expanded="false">
                        <span></span><span></span><span></span>
                    </button>
                </div>

            </div>
        </div>
    </header>
    <div class="nav-overlay" hidden></div>