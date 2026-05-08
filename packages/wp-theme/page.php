<?php
/**
 * 固定ページ汎用テンプレート
 */
get_header();
?>

<section class="gap-section" style="background:#fff;">
  <div class="gap-container" style="max-width:768px;">
    <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <h1 class="gap-h1 gap-font-serif" style="font-size:clamp(24px,4vw,36px);"><?php the_title(); ?></h1>
      <div class="gap-article-body" style="margin-top:2rem;font-size:15px;">
        <?php the_content(); ?>
      </div>
    <?php endwhile; endif; ?>
  </div>
</section>

<?php get_footer(); ?>
