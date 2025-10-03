<?php
/* ============================================================
 *  Vian Theme – integrated functions
 *  - Theme supports + menu
 *  - Enqueue CSS/JS (Bootstrap, fonts, main.css, header.js, footer.css)
 *  - CPT: Banner
 *  - ACF Footer Settings (Polylang-aware)
 *  - ACF About page (bind by page_template)
 *  - FIX: ACF WYSIWYG (gõ được trong ô “Nội dung”)
 * ============================================================ */

/* ==== Theme supports & menu ==== */
add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('custom-logo', [
    'height' => 60, 'width' => 200, 'flex-height' => true, 'flex-width' => true
  ]);
  register_nav_menus(['main_menu' => __('Main Menu', 'vian')]);
});

/* ==== Enqueue CSS/JS (1 chỗ duy nhất) ==== */
add_action('wp_enqueue_scripts', function () {
  // Fonts (Be Vietnam Pro + Oswald) + Bootstrap
  wp_enqueue_style(
    'vian-google-fonts',
    'https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Oswald:wght@400;500;700;800&display=swap',
    [],
    null
  );
  wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', [], '5.3.2');

  // theme style.css
  wp_enqueue_style('vian-style', get_stylesheet_uri(), ['bootstrap-css'], wp_get_theme()->get('Version'));

  // main.css (nếu có)
  $main_css = get_template_directory() . '/assets/css/main.css';
  if (file_exists($main_css)) {
    wp_enqueue_style('vian-main', get_template_directory_uri() . '/assets/css/main.css', ['vian-style'], filemtime($main_css));
  }

  // Footer CSS (nếu có)
  $footer_css = get_template_directory() . '/assets/css/footer.css';
  if (file_exists($footer_css)) {
    wp_enqueue_style('vian-footer', get_template_directory_uri() . '/assets/css/footer.css', ['vian-main'], filemtime($footer_css));
  }

  // Swiper + Bootstrap JS (nếu dùng)
  wp_enqueue_style('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], '11');
  wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', [], '5.3.2', true);
  wp_enqueue_script('swiper', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], '11', true);

  // header.js (mobile menu)
  $header_js = get_template_directory() . '/assets/js/header.js';
  if (file_exists($header_js)) {
    wp_enqueue_script('vian-header', get_template_directory_uri() . '/assets/js/header.js', [], filemtime($header_js), true);
  }

  // main.js (nếu có)
  $main_js = get_template_directory() . '/assets/js/main.js';
  if (file_exists($main_js)) {
    wp_enqueue_script('vian-main', get_template_directory_uri() . '/assets/js/main.js', ['swiper', 'bootstrap-js'], filemtime($main_js), true);
  }
});

/* ==== CPT: Banner ==== */
add_action('init', function () {
  register_post_type('banner', [
    'label' => __('Banners', 'vian'),
    'public' => true,
    'menu_icon' => 'dashicons-images-alt2',
    'supports' => ['title', 'editor', 'thumbnail', 'page-attributes'],
    'has_archive' => false,
    'show_in_rest' => true,
  ]);
});

/* ============================================================
 *  ACF – FOOTER SETTINGS (Polylang)
 * ============================================================ */

/* Tạo / lấy trang "Footer Settings" gốc (ưu tiên VI nếu có) */
if (!function_exists('vian_footer_base_page_id')) {
  function vian_footer_base_page_id() {
    $saved = (int) get_option('vian_footer_base_page_id', 0);
    if ($saved && get_post_status($saved)) return $saved;

    // tìm tất cả trang tên "Footer Settings"
    $cands = get_posts([
      'post_type'      => 'page',
      's'              => 'Footer Settings',
      'posts_per_page' => -1,
      'fields'         => 'ids',
    ]);

    if ($cands) {
      if (function_exists('pll_get_post_language')) {
        foreach ($cands as $pid) {
          if (pll_get_post_language($pid) === 'vi') {
            update_option('vian_footer_base_page_id', $pid);
            return $pid;
          }
        }
      }
      update_option('vian_footer_base_page_id', $cands[0]);
      return (int) $cands[0];
    }

    // không có -> tạo mới (VN)
    $page_id = wp_insert_post([
      'post_title'   => 'Footer Settings',
      'post_name'    => 'footer-settings',
      'post_type'    => 'page',
      'post_status'  => 'publish',
      'post_content' => 'Trang cấu hình footer (ACF).',
    ]);
    if (!is_wp_error($page_id) && $page_id) {
      update_option('vian_footer_base_page_id', $page_id);
      return (int) $page_id;
    }
    return 0;
  }
}

/* Lấy ID "Footer Settings" theo ngôn ngữ hiện tại */
if (!function_exists('vian_footer_settings_id')) {
  function vian_footer_settings_id() {
    $base = vian_footer_base_page_id();
    if ($base && function_exists('pll_get_post') && function_exists('pll_current_language')) {
      $cur = pll_current_language('slug'); // vi | en | ...
      if ($cur) {
        $tr = pll_get_post($base, $cur);
        if ($tr) return (int) $tr;
      }
    }
    return (int) $base;
  }
}

/* ACF Group cho Footer Settings (cho tất cả bản dịch) */
add_action('acf/init', function () {
  if (!function_exists('acf_add_local_field_group')) return;

  $base = vian_footer_base_page_id();
  if (!$base) return;

  $locations = [];
  if (function_exists('pll_languages_list') && function_exists('pll_get_post')) {
    foreach ((array) pll_languages_list(['fields' => 'slug']) as $slug) {
      $pid = pll_get_post($base, $slug);
      if ($pid) $locations[] = [['param' => 'page', 'operator' => '==', 'value' => $pid]];
    }
  }
  if (empty($locations)) {
    $locations[] = [['param' => 'page', 'operator' => '==', 'value' => $base]];
  }

  acf_add_local_field_group([
    'key'    => 'group_footer_vian_free',
    'title'  => 'Footer – Vị An',
    'fields' => [
      ['key'=>'field_col1_title','label'=>'Tiêu đề cột 1','name'=>'col1_title','type'=>'text','default_value'=>'VỊ AN – CƠM NGON TRÒN VỊ'],
      [
        'key'=>'field_branches','label'=>'Danh sách cơ sở','name'=>'branches','type'=>'repeater','layout'=>'row','button_label'=>'Thêm cơ sở',
        'sub_fields'=>[
          ['key'=>'field_branch_label','label'=>'Nhãn cơ sở (vd: Cơ sở 1)','name'=>'label','type'=>'text'],
          ['key'=>'field_branch_address','label'=>'Địa chỉ','name'=>'address','type'=>'text'],
          ['key'=>'field_branch_hotline','label'=>'Hotline','name'=>'hotline','type'=>'text'],
        ],
      ],
      ['key'=>'field_contact_email','label'=>'Email liên hệ','name'=>'contact_email','type'=>'email'],
      ['key'=>'field_contact_note','label'=>'Ghi chú ngắn','name'=>'contact_note','type'=>'text'],

      ['key'=>'field_col2_title','label'=>'Tiêu đề cột 2','name'=>'col2_title','type'=>'text','default_value'=>'Giờ mở cửa'],
      ['key'=>'field_open_morning','label'=>'Sáng','name'=>'open_morning','type'=>'text','default_value'=>'Sáng: 10h - 14h'],
      ['key'=>'field_open_evening','label'=>'Chiều','name'=>'open_evening','type'=>'text','default_value'=>'Chiều: 18h - 22h'],
      ['key'=>'field_open_note','label'=>'Ghi chú ngày mở cửa','name'=>'open_note','type'=>'text','default_value'=>'Tất cả các ngày trong tuần'],

      ['key'=>'field_col3_title','label'=>'Tiêu đề cột 3','name'=>'col3_title','type'=>'text','default_value'=>'Mạng xã hội'],
      [
        'key'=>'field_socials','label'=>'Mạng xã hội','name'=>'socials','type'=>'repeater','layout'=>'table','button_label'=>'Thêm kênh',
        'sub_fields'=>[
          ['key'=>'field_social_platform','label'=>'Nền tảng','name'=>'platform','type'=>'select','return_format'=>'value',
           'choices'=>['facebook'=>'Facebook','youtube'=>'YouTube','instagram'=>'Instagram','tiktok'=>'TikTok']],
          ['key'=>'field_social_url','label'=>'URL','name'=>'url','type'=>'url'],
        ],
      ],

      ['key'=>'field_copyright','label'=>'Copyright','name'=>'copyright','type'=>'text','default_value'=>'Copyright © '.date('Y').' Vị An – Cơm ngon tròn vị. All rights reserved.'],
      [
        'key'=>'field_footer_links','label'=>'Liên kết dưới (vd: Bảo mật | Liên hệ)','name'=>'footer_links','type'=>'repeater','layout'=>'table','button_label'=>'Thêm liên kết',
        'sub_fields'=>[
          ['key'=>'field_flink_label','label'=>'Nhãn','name'=>'label','type'=>'text'],
          ['key'=>'field_flink_url','label'=>'URL','name'=>'url','type'=>'url'],
        ],
      ],
    ],
    'location' => $locations,
    'active'   => true,
  ]);
});

/* ============================================================
 *  ACF – ABOUT PAGE (bind theo PAGE TEMPLATE)
 * ============================================================ */
add_action('acf/init', function () {
  if (!function_exists('acf_add_local_field_group')) return;

  // Location: chấp nhận 3 đường dẫn template
  $loc = [
    [ ['param'=>'page_template','operator'=>'==','value'=>'template-parts/page-about-vian.php'] ],
    [ ['param'=>'page_template','operator'=>'==','value'=>'templates/page-about-vian.php'] ],
    [ ['param'=>'page_template','operator'=>'==','value'=>'page-about-vian.php'] ],
  ];

  // Fallback: tìm mọi page đang dùng template trên và gắn theo ID
  $candidates = get_posts([
    'post_type' => 'page',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'meta_query' => [
      'relation' => 'OR',
      ['key'=>'_wp_page_template','value'=>'template-parts/page-about-vian.php'],
      ['key'=>'_wp_page_template','value'=>'templates/page-about-vian.php'],
      ['key'=>'_wp_page_template','value'=>'page-about-vian.php'],
    ],
  ]);
  foreach ($candidates as $pid) { $loc[] = [ ['param'=>'page','operator'=>'==','value'=>$pid] ]; }

  acf_add_local_field_group([
    'key'   => 'group_about_vian',
    'title' => 'About – Vị An',
    'fields'=> [
      ['key'=>'field_about_hero_bg','label'=>'Ảnh nền hero','name'=>'about_hero_bg','type'=>'image','return_format'=>'id','preview_size'=>'medium'],
      ['key'=>'field_about_hero_title','label'=>'Tiêu đề hero','name'=>'about_hero_title','type'=>'text','default_value'=>'VỀ CHÚNG TÔI'],
      ['key'=>'field_about_hero_sub','label'=>'Phụ đề hero','name'=>'about_hero_sub','type'=>'text','default_value'=>'Nhà hàng Vị An'],

      ['key'=>'field_about_left_heading','label'=>'Tiêu đề lớn bên trái','name'=>'about_left_heading','type'=>'text','default_value'=>'VỊ AN – CƠM NGON TRÒN VỊ'],
      ['key'=>'field_about_left_intro','label'=>'Đoạn mở đầu đậm','name'=>'about_left_intro','type'=>'textarea','rows'=>2],

      // WYSIWYG: để mặc định, mình sẽ ép nạp editor & full toolbar ở dưới
      ['key'=>'field_about_left_content','label'=>'Nội dung','name'=>'about_left_content','type'=>'wysiwyg','tabs'=>'visual','media_upload'=>0],

      ['key'=>'field_about_right_image','label'=>'Ảnh cột phải','name'=>'about_right_image','type'=>'image','return_format'=>'id','preview_size'=>'large'],
    ],
    'location' => $loc,
    'active'   => true,
  ]);
});

/* ============================================================
 *  FIX: ACF WYSIWYG không gõ được trong ô “Nội dung”
 * ============================================================ */
// Ép WordPress nạp script editor trong admin
add_action('admin_enqueue_scripts', function () {
  if (function_exists('wp_enqueue_editor')) {
    wp_enqueue_editor();
  }
});
// Set full toolbar + khởi tạo ngay (không delay) cho field “about_left_content”
add_filter('acf/prepare_field/key=field_about_left_content', function ($field) {
  $field['tabs']         = 'all';
  $field['toolbar']      = 'full';
  $field['media_upload'] = 0;
  $field['delay']        = 0;
  return $field;
});

/* ============================================================
 *  PERF: defer JS, async CSS, resource hints, lazy images, cleanup
 * ============================================================ */

/** Preconnect tới Google Fonts để giảm handshake */
add_filter('wp_resource_hints', function($hints, $relation){
  if ($relation === 'preconnect') {
    $hints[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin' => true];
    $hints[] = 'https://fonts.googleapis.com';
  }
  return $hints;
}, 10, 2);

/** Defer các script KHÔNG critical (giữ jQuery nếu plugin cần) */
add_filter('script_loader_tag', function($tag, $handle, $src){
  // các handle bạn đang enqueue ở trên
  $defer = ['bootstrap-js','swiper','vian-header','vian-main'];
  if (in_array($handle, $defer, true)) {
    return '<script src="'.esc_url($src).'" defer></script>';
  }
  return $tag;
}, 10, 3);

/** Async/preload cho CSS phụ (tránh chặn FCP) */
add_filter('style_loader_tag', function($html, $handle, $href, $media){
  // CSS không cần ngay màn đầu: swiper, footer
  $async_css = ['swiper','vian-footer'];
  if (in_array($handle, $async_css, true)) {
    $pre = "<link rel='preload' as='style' href='".esc_url($href)."' onload=\"this.onload=null;this.rel='stylesheet'\">";
    $ns  = "<noscript><link rel='stylesheet' href='".esc_url($href)."'></noscript>";
    return $pre.$ns;
  }
  return $html;
}, 10, 4);

/** Lazy + decoding cho ảnh */
add_filter('wp_get_attachment_image_attributes', function($attr){
  $attr['decoding'] = 'async';
  if (!isset($attr['loading'])) $attr['loading'] = 'lazy';
  return $attr;
});

/** Dọn asset thừa theo trang (ví dụ) */
add_action('wp_enqueue_scripts', function(){
  if (is_front_page()) {
    // nếu không dùng Contact Form 7 ở trang chủ
    wp_dequeue_style('contact-form-7');
    wp_dequeue_script('contact-form-7');
  }
}, 100);

/** Tắt emoji frontend để giảm request nhỏ */
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/** Tách CSS core block theo block để nhẹ hơn */
add_filter('should_load_separate_core_block_assets', '__return_true');