<?php
/* Template Name: Vian – About */
get_header();

/* Helper: đổi link watch?v / youtu.be sang embed */
function vian_youtube_embed_src($url){
  if (empty($url)) return '';
  // youtu.be/ID
  if (preg_match('~youtu\.be/([a-zA-Z0-9_-]{6,})~', $url, $m)) {
    $id = $m[1];
  }
  // youtube.com/watch?v=ID
  elseif (preg_match('~v=([a-zA-Z0-9_-]{6,})~', $url, $m)) {
    $id = $m[1];
  }
  // nếu user tự dán link embed sẵn
  elseif (preg_match('~embed/([a-zA-Z0-9_-]{6,})~', $url, $m)) {
    $id = $m[1];
  } else {
    return '';
  }

  // Thêm params thân thiện
  $params = [
    'rel' => 0,
    'controls' => 1,
    'modestbranding' => 1,
    'playsinline' => 1,
  ];
  return 'https://www.youtube.com/embed/'.rawurlencode($id).'?'.http_build_query($params);
}

$hero_bg_id = get_field('about_hero_bg');
$hero_bg    = $hero_bg_id ? wp_get_attachment_image_url($hero_bg_id, 'full')
                          : get_template_directory_uri().'/assets/images/background.jpg';
$hero_title = get_field('about_hero_title') ?: 'VỀ CHÚNG TÔI';
$hero_sub   = get_field('about_hero_sub')   ?: 'Nhà hàng Vị An';

// Thêm background cho phần giữa
$bg = get_template_directory_uri() . '/assets/images/background.jpg';

$left_heading = get_field('about_left_heading');
$left_intro   = get_field('about_left_intro');

$left_content = get_field('about_left_content');
if (empty($left_content)) {
  $left_content = apply_filters('the_content', get_post_field('post_content', get_the_ID()));
}

$right_img_id = get_field('about_right_image');
$right_img    = $right_img_id ? wp_get_attachment_image_url($right_img_id, 'full') : '';

$video_url = get_field('about_video_url');
$video_src = vian_youtube_embed_src($video_url);
?>
<main class="about-page">

  <!-- Hero -->
  <section class="about-hero" style="--hero:url('<?php echo esc_url($hero_bg); ?>')">
    <div class="container">
      <h1 class="about-hero-title"><?php echo esc_html($hero_title); ?></h1>
      <p class="about-hero-sub"><?php echo esc_html($hero_sub); ?></p>
    </div>
  </section>

  <!-- Giới thiệu: văn bản + ảnh -->
  <section class="about-wrap" style="background-image:url('<?php echo esc_url($bg); ?>')">
    <div class="about-intro container-narrow">
      <div class="about-left">
        <?php if ($left_heading): ?>
          <h2 class="about-left-heading"><?php echo esc_html($left_heading); ?></h2>
        <?php endif; ?>

        <?php if ($left_intro): ?>
          <p class="about-left-intro"><strong><?php echo nl2br(esc_html($left_intro)); ?></strong></p>
        <?php endif; ?>

        <div class="about-left-content">
          <?php echo $left_content; ?>
        </div>
      </div>

      <?php if ($right_img): ?>
        <aside class="about-right">
          <figure class="about-photo">
            <img src="<?php echo esc_url($right_img); ?>" alt="Về Vị An" loading="lazy">
          </figure>
        </aside>
      <?php endif; ?>
    </div>
  </section>

<!-- Video: xuống hàng riêng -->
<?php if ($video_src): ?>
  <section class="about-video-section">
    <div class="container-narrow">
      <div class="about-video">
        <iframe
          src="<?php echo esc_url($video_src); ?>"
          title="Vị An - video"
          loading="lazy"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          referrerpolicy="strict-origin-when-cross-origin"
          allowfullscreen>
        </iframe>
      </div>
    </div>
  </section>
<?php endif; ?>

</main>
<?php get_footer(); ?>