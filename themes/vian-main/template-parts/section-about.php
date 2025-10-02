<?php
$heading = get_field('about_heading');
$title   = get_field('about_title');
$intro   = get_field('about_intro');
$desc    = get_field('about_content');
$img     = get_field('about_image');

// ảnh nền cố định trong thư mục theme
$bg = get_template_directory_uri() . '/assets/images/background.jpg';
?>
<?php if($heading || $title || $intro || $desc): ?>
    
<section class="vian-about" style="background-image:url('<?php echo esc_url($bg); ?>')">
  <div class="container">
    <div class="row g-4 align-items-center">
      <div class="col-lg-6">
        <?php if($heading): ?>
          <div class="about-heading"><?php echo esc_html($heading); ?></div>
        <?php endif; ?>
        <?php if($title): ?>
          <h2 class="about-title"><?php echo esc_html($title); ?></h2>
        <?php endif; ?>
        <?php if($intro): ?>
          <p class="about-intro"><?php echo esc_html($intro); ?></p>
        <?php endif; ?>
        <?php if($desc): ?>
          <div class="about-desc"><?php echo wp_kses_post($desc); ?></div>
        <?php endif; ?>
      </div>
      <div class="col-lg-6">
        <?php if(!empty($img['url'])): ?>
          <div class="about-photo">
            <img src="<?php echo esc_url($img['url']); ?>" alt="">
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<?php endif; ?>
