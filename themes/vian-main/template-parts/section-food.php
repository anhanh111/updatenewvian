<?php
$heading = get_field('food_heading');
?>
<?php if($heading): ?>
<section class="vian-food">
  <div class="container">
    <h2 class="food-heading text-center"><?php echo esc_html($heading); ?></h2>

    <div class="row g-4">
      <?php for($i=1; $i<=4; $i++):
        $img  = get_field("food_image_$i");
        $name = get_field("food_name_$i");
        $desc = get_field("food_desc_$i");
        if(!$img && !$name && !$desc) continue; ?>
        <div class="col-md-3 col-sm-6">
          <div class="food-item text-center">
            <?php if(!empty($img['url'])): ?>
              <div class="food-photo">
                <img src="<?php echo esc_url($img['url']); ?>" alt="">
              </div>
            <?php endif; ?>
            <?php if($name): ?><h3 class="food-name"><?php echo esc_html($name); ?></h3><?php endif; ?>
            <?php if($desc): ?><p class="food-desc"><?php echo esc_html($desc); ?></p><?php endif; ?>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </div>
</section>
<?php endif; ?>
