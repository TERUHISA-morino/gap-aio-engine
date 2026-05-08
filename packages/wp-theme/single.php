<?php
/**
 * 単一記事テンプレート（AI生成記事用）
 */
get_header();
?>

<article class="gap-section" style="background:#fff;">
  <div class="gap-container" style="max-width:768px;">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <div style="display:flex;flex-wrap:wrap;gap:.75rem;font-size:11px;color:#64748b;margin-bottom:1rem;">
        <a href="<?php echo esc_url(home_url('/blog/')); ?>" style="color:var(--gap-primary);">症状解説</a>
        <span>／</span>
        <span><?php echo get_the_date(); ?></span>
        <?php $cats = get_the_category(); if ($cats): ?>
          <span>／</span>
          <span><?php echo esc_html($cats[0]->name); ?></span>
        <?php endif; ?>
      </div>

      <h1 class="gap-h1 gap-font-serif" style="font-size:clamp(24px,4vw,36px);line-height:1.4;">
        <?php the_title(); ?>
      </h1>

      <?php if ($excerpt = get_the_excerpt()): ?>
        <div style="margin-top:1.5rem;padding:1rem 1.25rem;border-left:4px solid var(--gap-primary);background:var(--gap-bg-1);border-radius:8px;font-size:14px;line-height:1.8;color:#475569;">
          <?php echo esc_html($excerpt); ?>
        </div>
      <?php endif; ?>

      <div class="gap-article-body" style="margin-top:2.5rem;font-size:15px;">
        <?php the_content(); ?>
      </div>

      <!-- 来院誘導カード -->
      <div style="margin-top:4rem;padding:1.5rem;background:#f8fafc;border:1px solid #e2e8f0;border-radius:16px;text-align:center;">
        <h3 class="gap-font-serif" style="font-size:18px;font-weight:700;"><?php echo esc_html(gap_aio_clinic('name')); ?></h3>
        <p style="margin-top:.5rem;font-size:13px;color:#64748b;line-height:1.7;"><?php echo esc_html(gap_aio_clinic('tagline')); ?></p>
        <?php if ($line = gap_aio_clinic('line_url')): ?>
          <a class="gap-btn gap-btn-primary" href="<?php echo esc_url($line); ?>" target="_blank" rel="noopener" style="margin-top:1.25rem;">💬 来院相談する →</a>
        <?php endif; ?>
      </div>

      <div style="margin-top:2rem;font-size:11px;color:#94a3b8;">
        <a href="<?php echo esc_url(home_url('/blog/')); ?>">← 記事一覧に戻る</a>
      </div>
    <?php endwhile; endif; ?>
  </div>
</article>

<?php get_footer(); ?>
