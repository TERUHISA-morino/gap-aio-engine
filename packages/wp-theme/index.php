<?php
/**
 * 記事一覧 (ブログ・カテゴリ・タグ・検索結果) の汎用テンプレート
 */
get_header();
?>

<section class="gap-section" style="background:#fff;">
  <div class="gap-container" style="max-width:896px;">
    <h1 class="gap-h1 gap-font-serif" style="font-size:clamp(24px,4vw,36px);">
      <?php
      if (is_category()) single_cat_title();
      elseif (is_tag()) single_tag_title();
      elseif (is_search()) printf('「%s」の検索結果', esc_html(get_search_query()));
      else echo '症状解説記事';
      ?>
    </h1>
    <p style="margin-top:.5rem;font-size:13px;color:#64748b;">
      GAP理論に基づく独自の考察記事
    </p>

    <?php if (have_posts()): ?>
      <div style="margin-top:2.5rem;display:flex;flex-direction:column;gap:1rem;">
        <?php while (have_posts()): the_post(); ?>
          <a href="<?php the_permalink(); ?>" class="gap-card" style="display:block;">
            <div style="display:flex;flex-direction:column;gap:.5rem;">
              <div style="display:flex;align-items:center;gap:.75rem;font-size:11px;color:#64748b;">
                <span><?php echo get_the_date(); ?></span>
                <?php $cats = get_the_category(); if ($cats): ?>
                  <span>／</span><span><?php echo esc_html($cats[0]->name); ?></span>
                <?php endif; ?>
              </div>
              <h2 class="gap-font-serif" style="font-size:17px;font-weight:700;line-height:1.5;"><?php the_title(); ?></h2>
              <p style="font-size:13px;line-height:1.8;color:#64748b;"><?php echo wp_trim_words(get_the_excerpt(), 60); ?></p>
            </div>
          </a>
        <?php endwhile; ?>
      </div>

      <div style="margin-top:2.5rem;display:flex;justify-content:center;gap:.5rem;">
        <?php
        echo paginate_links([
            'prev_text' => '←',
            'next_text' => '→',
        ]);
        ?>
      </div>
    <?php else: ?>
      <p style="margin-top:2rem;color:#64748b;">記事がまだありません。</p>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
