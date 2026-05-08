<?php
/**
 * Footer テンプレート
 */
?>
<footer class="gap-footer">
  <div class="gap-container">
    <div class="gap-footer-grid">
      <div>
        <div class="gap-font-serif" style="font-size:18px;font-weight:700;">
          <?php echo esc_html(gap_aio_clinic('name') ?: get_bloginfo('name')); ?>
        </div>
        <p style="margin-top:.75rem;font-size:13px;line-height:1.7;color:#64748b;">
          <?php echo esc_html(gap_aio_clinic('tagline')); ?>
        </p>
        <?php if ($addr = gap_aio_clinic('address')): ?>
          <p style="margin-top:.75rem;font-size:11px;color:#94a3b8;"><?php echo esc_html($addr); ?></p>
        <?php endif; ?>
        <?php if ($station = gap_aio_clinic('station')): ?>
          <p style="margin-top:.25rem;font-size:11px;color:#94a3b8;"><?php echo esc_html($station); ?></p>
        <?php endif; ?>
      </div>

      <div>
        <div class="gap-footer-eyebrow">CONTACT</div>
        <?php if ($phone = gap_aio_clinic('phone')): ?>
          <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" style="display:block;font-size:14px;margin-top:.5rem;">📞 <?php echo esc_html($phone); ?></a>
        <?php endif; ?>
        <?php if ($line = gap_aio_clinic('line_url')): ?>
          <a href="<?php echo esc_url($line); ?>" target="_blank" rel="noopener" style="display:block;font-size:14px;margin-top:.5rem;">💬 LINE予約</a>
        <?php endif; ?>
        <?php if ($hours = gap_aio_clinic('hours')): ?>
          <p style="margin-top:.75rem;font-size:13px;color:#64748b;">🕒 <?php echo esc_html($hours); ?></p>
        <?php endif; ?>
        <?php if ($closed = gap_aio_clinic('closed')): ?>
          <p style="margin-top:.25rem;font-size:13px;color:#64748b;"><?php echo esc_html($closed); ?></p>
        <?php endif; ?>
      </div>

      <div>
        <div class="gap-footer-eyebrow">MENU</div>
        <nav style="margin-top:.5rem;display:grid;grid-template-columns:repeat(2,1fr);gap:.4rem;font-size:13px;color:#64748b;">
          <a href="#features">特徴</a>
          <a href="#symptoms">症状別</a>
          <a href="#menu">料金</a>
          <a href="#staff">院長</a>
          <a href="#voices">お客様の声</a>
          <a href="<?php echo esc_url(home_url('/blog/')); ?>">症状解説</a>
          <a href="#access">アクセス</a>
        </nav>
        <?php
        $sns_links = [
            'instagram' => gap_aio_clinic('instagram'),
            'youtube' => gap_aio_clinic('youtube'),
        ];
        $sns_links = array_filter($sns_links);
        if (!empty($sns_links)): ?>
          <div style="margin-top:1rem;display:flex;gap:.75rem;font-size:13px;color:#64748b;">
            <?php foreach ($sns_links as $name => $url): ?>
              <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener"><?php echo ucfirst($name); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="gap-footer-bottom">
      © <?php echo date('Y'); ?> <?php echo esc_html(gap_aio_clinic('name') ?: get_bloginfo('name')); ?>. All rights reserved. ／ Powered by GAP-AIO
    </div>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
