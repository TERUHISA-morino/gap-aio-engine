<?php
/**
 * Header テンプレート
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="gap-header">
  <div class="gap-header-inner">
    <a class="gap-header-logo" href="<?php echo esc_url(home_url('/')); ?>">
      <?php echo esc_html(gap_aio_clinic('short_name') ?: gap_aio_clinic('name') ?: get_bloginfo('name')); ?>
    </a>
    <nav class="gap-header-nav" aria-label="Main">
      <a href="#features">特徴</a>
      <a href="#symptoms">症状別</a>
      <a href="#menu">料金</a>
      <a href="#staff">院長</a>
      <a href="#voices">お客様の声</a>
      <a href="<?php echo esc_url(home_url('/blog/')); ?>">症状解説</a>
      <a href="#access">アクセス</a>
    </nav>
    <div class="gap-header-cta">
      <?php if ($phone = gap_aio_clinic('phone')): ?>
        <a class="gap-header-phone" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">
          📞 <?php echo esc_html($phone); ?>
        </a>
      <?php endif; ?>
      <?php if ($line = gap_aio_clinic('line_url')): ?>
        <a class="gap-header-line" href="<?php echo esc_url($line); ?>" target="_blank" rel="noopener">
          LINE予約 →
        </a>
      <?php endif; ?>
    </div>
  </div>
</header>
