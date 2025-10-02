<?php
$FOOTER_ID = function_exists('vian_footer_settings_id') ? vian_footer_settings_id() : 0;

$col1_title = get_field('col1_title', $FOOTER_ID);
$branches   = get_field('branches', $FOOTER_ID);
$email      = get_field('contact_email', $FOOTER_ID);
$note       = get_field('contact_note', $FOOTER_ID);

$col2_title = get_field('col2_title', $FOOTER_ID);
$open_m     = get_field('open_morning', $FOOTER_ID);
$open_e     = get_field('open_evening', $FOOTER_ID);
$open_note  = get_field('open_note', $FOOTER_ID);

$col3_title = get_field('col3_title', $FOOTER_ID);
$socials    = get_field('socials', $FOOTER_ID);

$copyright  = get_field('copyright', $FOOTER_ID);
$links      = get_field('footer_links', $FOOTER_ID);
?>


<footer class="vian-footer">
    <div class="vf-container vf-grid">

        <!-- Cột 1 -->
        <div class="vf-col">
            <?php if ($col1_title): ?><h3 class="vf-title"><?php echo esc_html($col1_title); ?></h3><?php endif; ?>

            <?php if ($branches): foreach ($branches as $b): ?>
            <div class="vf-branch">
                <p class="vf-branch-line">
                    <?php if (!empty($b['label'])): ?><span
                        class="vf-branch-label"><?php echo esc_html($b['label']); ?>:</span><?php endif; ?>
                    <?php if (!empty($b['address'])): ?> <strong><?php echo esc_html($b['address']); ?></strong>
                    <?php endif; ?>
                </p>
                <?php if (!empty($b['hotline'])): ?>
                <p class="vf-hotline">Hotline: <a
                        href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $b['hotline'])); ?>"><?php echo esc_html($b['hotline']); ?></a>
                </p>
                <?php endif; ?>
            </div>
            <?php endforeach; endif; ?>

            <?php if ($email || $note): ?>
            <div class="vf-mini-sep"></div>
            <?php endif; ?>

            <?php if ($email): ?>
            <p>Email: <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
            <?php endif; ?>

            <?php if ($note): ?><p class="vf-note"><?php echo esc_html($note); ?></p><?php endif; ?>

            <div class="vf-long-sep"></div>
        </div>

        <!-- Cột 2 -->
        <div class="vf-col">
            <?php if ($col2_title): ?><h3 class="vf-title"><?php echo esc_html($col2_title); ?></h3><?php endif; ?>
            <?php if ($open_m): ?><p><?php echo esc_html($open_m); ?></p><?php endif; ?>
            <?php if ($open_e): ?><p><?php echo esc_html($open_e); ?></p><?php endif; ?>
            <?php if ($open_note): ?><p><?php echo esc_html($open_note); ?></p><?php endif; ?>
        </div>

        <!-- Cột 3 -->
        <div class="vf-col">
            <?php if ($col3_title): ?><h3 class="vf-title"><?php echo esc_html($col3_title); ?></h3><?php endif; ?>
            <?php if ($socials): ?>
            <ul class="vf-socials">
                <?php foreach ($socials as $s):
            $plat = $s['platform'] ?? ''; $url = $s['url'] ?? '';
            if (!$url) continue; ?>
                <li>
                    <a class="vf-social" target="_blank" rel="noopener" href="<?php echo esc_url($url); ?>">
                        <?php switch ($plat) {
                  case 'facebook':
                    echo '<svg viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.8V12h2.8V9.7c0-2.8 1.7-4.3 4.2-4.3 1.2 0 2.5.2 2.5.2v2.7h-1.4c-1.4 0-1.8.9-1.8 1.8V12h3.1l-.5 2.9h-2.6v7A10 10 0 0 0 22 12z"/></svg>'; break;
                  case 'youtube':
                    echo '<svg viewBox="0 0 24 24"><path d="M23.5 7.1a3 3 0 0 0-2.1-2.1C19.2 4.5 12 4.5 12 4.5s-7.2 0-9.4.5A3 3 0 0 0 .5 7.1 31 31 0 0 0 0 12a31 31 0 0 0 .5 4.9 3 3 0 0 0 2.1 2.1C4.8 19.5 12 19.5 12 19.5s7.2 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-4.9zM9.6 15.4V8.6L15.8 12l-6.2 3.4z"/></svg>'; break;
                  case 'instagram':
                    echo '<svg viewBox="0 0 24 24"><path d="M12 7a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0-5c3.3 0 3.7 0 5 .1 1.2.1 2 .3 2.7.7.7.4 1.3 1 1.7 1.7.4.7.6 1.5.7 2.7.1 1.3.1 1.7.1 5s0 3.7-.1 5c-.1 1.2-.3 2-.7 2.7-.4.7-1 1.3-1.7 1.7-.7.4-1.5.6-2.7.7-1.3.1-1.7.1-5 .1s-3.7 0-5-.1c-1.2-.1-2-.3-2.7-.7a4.4 4.4 0 0 1-1.7-1.7c-.4-.7-.6-1.5-.7-2.7C2 15.7 2 15.3 2 12s0-3.7.1-5c.1-1.2.3-2 .7-2.7.4-.7 1-1.3 1.7-1.7.7-.4 1.5-.6 2.7-.7C8.3 2 8.7 2 12 2z"/><circle cx="18" cy="6" r="1.2"/></svg>'; break;
                  default:
                    echo '<svg viewBox="0 0 24 24"><path d="M21 8.1a7 7 0 0 1-4.4-1.5v7.2A6.6 6.6 0 1 1 9.1 7.3v3.1a3.6 3.6 0 1 0 2.5 3.4V2h3a7 7 0 0 0 6.4 6.1z"/></svg>'; break;
                } ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <div class="vf-bottom">
        <div class="vf-container">
            <p><?php echo esc_html($copyright ?: ('Copyright © ' . date('Y') . ' ' . get_bloginfo('name') . '. All rights reserved.')); ?>
            </p>
            <?php if ($links): ?>
            <span class="vf-bottom-links">
                <?php
          $out = [];
          foreach ($links as $lk) {
            if (!empty($lk['url'])) $out[] = '<a href="'.esc_url($lk['url']).'">'.esc_html($lk['label'] ?: 'Link').'</a>';
          }
          echo implode(' <span class="dot">|</span> ', $out);
          ?>
            </span>
            <?php endif; ?>
        </div>
    </div>

    <?php wp_footer(); ?>
</footer>