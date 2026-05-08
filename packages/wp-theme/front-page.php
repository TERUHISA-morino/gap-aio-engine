<?php
/**
 * トップページテンプレート（10セクション構成）
 */
get_header();

$features = gap_aio_get_features();
$symptoms = gap_aio_get_symptoms();
$menu_items = gap_aio_get_menu_items();
$staff = gap_aio_get_staff();
$voices = gap_aio_get_voices();
$specialties = gap_aio_get_specialties();

// 最新記事
$recent = get_posts(['numberposts' => 3]);

// JSON-LD MedicalBusiness
$jsonld = [
    '@context' => 'https://schema.org',
    '@type' => 'MedicalBusiness',
    'name' => gap_aio_clinic('name') ?: get_bloginfo('name'),
    'description' => gap_aio_clinic('tagline'),
    'url' => home_url('/'),
    'telephone' => gap_aio_clinic('phone'),
    'address' => ['@type' => 'PostalAddress', 'streetAddress' => gap_aio_clinic('address')],
    'openingHours' => gap_aio_clinic('hours'),
];
?>
<script type="application/ld+json"><?php echo wp_json_encode($jsonld, JSON_UNESCAPED_UNICODE); ?></script>

<!-- ① Hero -->
<section class="gap-hero">
  <div class="gap-container">
    <div class="gap-hero-grid">
      <div>
        <span class="gap-hero-badge"><span class="gap-hero-badge-dot"></span> GAP理論実践院</span>
        <h1 class="gap-h1 gap-font-serif">
          <?php echo esc_html(gap_aio_clinic('tagline')); ?>
        </h1>
        <?php if ($sub = gap_aio_clinic('sub_tagline')): ?>
          <p class="gap-p-lead"><?php echo esc_html($sub); ?></p>
        <?php endif; ?>
        <div style="margin-top:1.5rem;display:flex;flex-wrap:wrap;gap:.75rem;">
          <?php if ($line = gap_aio_clinic('line_url')): ?>
            <a class="gap-btn gap-btn-primary" href="<?php echo esc_url($line); ?>" target="_blank" rel="noopener">💬 LINEで予約する →</a>
          <?php endif; ?>
          <?php if ($phone = gap_aio_clinic('phone')): ?>
            <a class="gap-btn gap-btn-outline" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>">📞 <?php echo esc_html($phone); ?></a>
          <?php endif; ?>
        </div>
        <?php if (!empty($specialties)): ?>
          <div class="gap-hero-tags">
            <?php foreach ($specialties as $s): ?>
              <span class="gap-hero-tag"><?php echo esc_html($s); ?></span>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
      <div>
        <div class="gap-hero-image">🏥</div>
      </div>
    </div>
  </div>
</section>

<!-- ② Features -->
<?php if (!empty($features)): ?>
<section id="features" class="gap-section" style="background:#f8fafc;">
  <div class="gap-container">
    <h2 class="gap-h2 gap-font-serif gap-text-center">当院が選ばれる理由</h2>
    <div class="gap-grid gap-grid-4" style="margin-top:2.5rem;">
      <?php foreach ($features as $f): ?>
        <div class="gap-card gap-text-center">
          <div class="gap-card-icon"><?php echo esc_html($f['icon']); ?></div>
          <h3 class="gap-card-title gap-font-serif"><?php echo esc_html($f['title']); ?></h3>
          <p class="gap-card-body"><?php echo esc_html($f['body']); ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ③ Policy -->
<?php
$policy_title = get_theme_mod('gap_policy_title', '');
$policy_body = get_theme_mod('gap_policy_body', '');
if ($policy_title || $policy_body): ?>
<section class="gap-section" style="background:#fff;">
  <div class="gap-container" style="max-width:768px;text-align:center;">
    <div class="gap-section-eyebrow">施術方針</div>
    <h2 class="gap-h2 gap-font-serif"><?php echo esc_html($policy_title); ?></h2>
    <p class="gap-p-lead"><?php echo nl2br(esc_html($policy_body)); ?></p>
  </div>
</section>
<?php endif; ?>

<!-- ④ Symptoms -->
<?php if (!empty($symptoms)): ?>
<section id="symptoms" class="gap-section" style="background:#f8fafc;">
  <div class="gap-container">
    <h2 class="gap-h2 gap-font-serif gap-text-center">こんな症状はご相談ください</h2>
    <p class="gap-text-center" style="font-size:13px;color:#64748b;margin-top:.5rem;">GAP理論で根本原因にアプローチします</p>
    <div class="gap-grid gap-grid-4" style="margin-top:2.5rem;">
      <?php foreach ($symptoms as $s): ?>
        <div class="gap-card gap-text-center" style="padding:1.25rem;">
          <div style="font-size:30px;"><?php echo esc_html($s['icon']); ?></div>
          <div class="gap-font-serif" style="margin-top:.6rem;font-size:14px;font-weight:700;"><?php echo esc_html($s['name']); ?></div>
          <?php if ($s['desc']): ?><div style="margin-top:.25rem;font-size:11px;color:#64748b;"><?php echo esc_html($s['desc']); ?></div><?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ⑤ Menu/Pricing -->
<?php if (!empty($menu_items)): ?>
<section id="menu" class="gap-section" style="background:#fff;">
  <div class="gap-container" style="max-width:768px;">
    <h2 class="gap-h2 gap-font-serif gap-text-center">施術メニュー・料金</h2>
    <div class="gap-menu-card" style="margin-top:2.5rem;">
      <?php foreach ($menu_items as $m): ?>
        <div class="gap-menu-row">
          <div>
            <div class="gap-menu-name gap-font-serif"><?php echo esc_html($m['name']); ?></div>
            <?php if ($m['duration']): ?><div class="gap-menu-meta"><?php echo esc_html($m['duration']); ?></div><?php endif; ?>
            <?php if ($m['note']): ?><div class="gap-menu-note"><?php echo esc_html($m['note']); ?></div><?php endif; ?>
          </div>
          <div class="gap-menu-price gap-font-serif"><?php echo esc_html($m['price']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
    <p class="gap-text-center" style="margin-top:1rem;font-size:11px;color:#94a3b8;">※ 表記価格は税込です。</p>
  </div>
</section>
<?php endif; ?>

<!-- ⑥ Staff -->
<?php if (!empty($staff)): ?>
<section id="staff" class="gap-section" style="background:#f8fafc;">
  <div class="gap-container" style="max-width:896px;">
    <h2 class="gap-h2 gap-font-serif gap-text-center">院長・スタッフ紹介</h2>
    <div style="margin-top:2.5rem;display:flex;flex-direction:column;gap:1.5rem;">
      <?php foreach ($staff as $s): ?>
        <div class="gap-staff-card">
          <div><div class="gap-staff-photo">👤</div></div>
          <div>
            <div class="gap-font-serif" style="font-size:18px;font-weight:700;"><?php echo esc_html($s['name']); ?></div>
            <div style="margin-top:.25rem;font-size:12px;color:var(--gap-primary);"><?php echo esc_html($s['role']); ?></div>
            <p style="margin-top:.75rem;font-size:13px;line-height:1.8;color:#475569;"><?php echo nl2br(esc_html($s['bio'])); ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ⑦ Voices -->
<?php if (!empty($voices)): ?>
<section id="voices" class="gap-section" style="background:#fff;">
  <div class="gap-container">
    <h2 class="gap-h2 gap-font-serif gap-text-center">患者さまの声</h2>
    <div class="gap-grid gap-grid-3" style="margin-top:2.5rem;">
      <?php foreach ($voices as $v): ?>
        <div class="gap-voice-card">
          <div class="gap-voice-mark">"</div>
          <div class="gap-voice-title gap-font-serif"><?php echo esc_html($v['title']); ?></div>
          <p class="gap-voice-body"><?php echo nl2br(esc_html($v['body'])); ?></p>
          <div class="gap-voice-name"><?php echo esc_html($v['attr']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ⑧ AI Articles (Latest Posts) -->
<?php if (!empty($recent)): ?>
<section class="gap-section" style="background:#f8fafc;">
  <div class="gap-container">
    <div class="gap-text-center"><div class="gap-section-eyebrow">AI監修コンテンツ</div></div>
    <h2 class="gap-h2 gap-font-serif gap-text-center">症状別 解説記事</h2>
    <p class="gap-text-center" style="font-size:13px;color:#64748b;margin-top:.5rem;">GAP理論に基づき、毎日更新される深い考察</p>
    <div class="gap-grid gap-grid-3" style="margin-top:2.5rem;">
      <?php foreach ($recent as $post): setup_postdata($post); ?>
        <a href="<?php the_permalink(); ?>" class="gap-card" style="display:block;">
          <div style="font-size:11px;color:#64748b;"><?php echo get_the_date(); ?></div>
          <h3 class="gap-card-title gap-font-serif" style="margin-top:.5rem;"><?php the_title(); ?></h3>
          <p class="gap-card-body" style="margin-top:.75rem;"><?php echo wp_trim_words(get_the_excerpt(), 50); ?></p>
        </a>
      <?php endforeach; wp_reset_postdata(); ?>
    </div>
    <div class="gap-text-center" style="margin-top:2rem;">
      <a class="gap-btn gap-btn-outline" href="<?php echo esc_url(home_url('/blog/')); ?>">すべての症状解説記事 →</a>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ⑨ Access -->
<section id="access" class="gap-section" style="background:#fff;">
  <div class="gap-container" style="max-width:896px;">
    <h2 class="gap-h2 gap-font-serif gap-text-center">アクセス・営業時間</h2>
    <div class="gap-access-card" style="margin-top:2.5rem;">
      <div>
        <div class="gap-section-eyebrow">所在地</div>
        <p style="font-weight:500;"><?php echo esc_html(gap_aio_clinic('address')); ?></p>
        <p style="margin-top:.25rem;font-size:13px;color:#64748b;"><?php echo esc_html(gap_aio_clinic('station')); ?></p>
        <div class="gap-section-eyebrow" style="margin-top:1.5rem;">営業時間</div>
        <p style="font-weight:500;"><?php echo esc_html(gap_aio_clinic('hours')); ?></p>
        <p style="margin-top:.25rem;font-size:13px;color:#64748b;"><?php echo esc_html(gap_aio_clinic('closed')); ?></p>
      </div>
      <div>
        <div class="gap-section-eyebrow">ご予約</div>
        <div style="margin-top:.75rem;display:flex;flex-direction:column;gap:.5rem;">
          <?php if ($phone = gap_aio_clinic('phone')): ?>
            <a class="gap-btn gap-btn-outline" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" style="justify-content:center;">📞 <?php echo esc_html($phone); ?></a>
          <?php endif; ?>
          <?php if ($line = gap_aio_clinic('line_url')): ?>
            <a class="gap-btn gap-btn-primary" href="<?php echo esc_url($line); ?>" target="_blank" rel="noopener" style="justify-content:center;">💬 LINEで予約する →</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ⑩ Final CTA -->
<section class="gap-final-cta">
  <div class="gap-final-cta-inner">
    <h2 class="gap-h2 gap-font-serif" style="color:#fff;">痛みの根本原因に、向き合いませんか。</h2>
    <p style="margin-top:1rem;font-size:14px;line-height:1.8;">まずは初回カウンセリングで、あなたの症状を物理的にひも解きます。</p>
    <div class="gap-final-cta-buttons">
      <?php if ($line = gap_aio_clinic('line_url')): ?>
        <a class="gap-btn" href="<?php echo esc_url($line); ?>" target="_blank" rel="noopener" style="background:#fff;color:var(--gap-primary);">💬 LINEで予約する →</a>
      <?php endif; ?>
      <?php if ($phone = gap_aio_clinic('phone')): ?>
        <a class="gap-btn" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" style="border:2px solid #fff;color:#fff;background:transparent;">📞 <?php echo esc_html($phone); ?></a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer();
