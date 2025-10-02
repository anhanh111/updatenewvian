<?php
$heading = get_field('space_heading');
?>
<?php if ($heading): ?>
<?php
// ảnh nền cố định trong thư mục theme
$bg = get_template_directory_uri() . '/assets/images/background.jpg';
?>
<section class="vian-space" style="background-image:url('<?php echo esc_url($bg); ?>')">
    <div class="container">
        <h2 class="space-heading text-center"><?php echo esc_html($heading); ?></h2>

        <div class="row g-4">
            <?php for ($i=1; $i<=3; $i++): 
        $img = get_field("space_image_$i");
        if (!$img) continue; ?>
            <div class="col-md-4">
                <div class="space-photo">
                    <img src="<?php echo esc_url($img['url']); ?>" alt="">
                </div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
<?php endif; ?>