<?php
/**
 * GAP-AIO Clinic Theme - 関数定義
 */

if (!defined('ABSPATH')) exit;

define('GAP_AIO_THEME_VERSION', '0.1.0');

/**
 * テーマセットアップ
 */
function gap_aio_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('custom-logo', [
        'height' => 60,
        'width' => 200,
        'flex-height' => true,
        'flex-width' => true,
    ]);
    register_nav_menus([
        'primary' => __('メインメニュー', 'gap-aio-clinic'),
        'footer'  => __('フッターメニュー', 'gap-aio-clinic'),
    ]);
}
add_action('after_setup_theme', 'gap_aio_setup');

/**
 * スタイル/スクリプト読み込み
 */
function gap_aio_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'gap-aio-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700;900&family=Noto+Serif+JP:wght@400;500;700;900&display=swap',
        [],
        null
    );

    // メインCSS（テーマルート）
    wp_enqueue_style('gap-aio-base', get_stylesheet_uri(), [], GAP_AIO_THEME_VERSION);

    // 選択中のスタイルバリエーションCSS
    $style = get_theme_mod('gap_theme_style', 'medical-trust');
    $style_path = "/inc/theme-styles/{$style}.css";
    if (file_exists(get_template_directory() . $style_path)) {
        wp_enqueue_style(
            'gap-aio-variant',
            get_template_directory_uri() . $style_path,
            ['gap-aio-base'],
            GAP_AIO_THEME_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'gap_aio_enqueue_assets');

/**
 * テーマカラーをCSS変数として head に出力
 */
function gap_aio_inline_theme_vars() {
    $vars = gap_aio_get_theme_vars();
    $css = ':root {';
    foreach ($vars as $k => $v) {
        $css .= "--gap-{$k}: {$v};";
    }
    $css .= '}';
    echo "<style id='gap-aio-vars'>{$css}</style>\n";
}
add_action('wp_head', 'gap_aio_inline_theme_vars', 5);

/**
 * 4スタイルプリセットの色パレット
 */
function gap_aio_get_theme_vars() {
    $style = get_theme_mod('gap_theme_style', 'medical-trust');
    $presets = [
        'medical-trust' => [
            'primary' => '#047857', 'primary-dark' => '#065f46', 'accent' => '#fbbf24',
            'bg-1' => '#ecfdf5', 'bg-2' => '#fef3c7',
        ],
        'clean-modern' => [
            'primary' => '#0284c7', 'primary-dark' => '#075985', 'accent' => '#f97316',
            'bg-1' => '#f0f9ff', 'bg-2' => '#fff7ed',
        ],
        'warm-care' => [
            'primary' => '#c2410c', 'primary-dark' => '#9a3412', 'accent' => '#facc15',
            'bg-1' => '#fff7ed', 'bg-2' => '#fefce8',
        ],
        'premium-dark' => [
            'primary' => '#1e293b', 'primary-dark' => '#0f172a', 'accent' => '#fde68a',
            'bg-1' => '#f1f5f9', 'bg-2' => '#fef3c7',
        ],
    ];
    return $presets[$style] ?? $presets['medical-trust'];
}

/**
 * Customizer
 */
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/helpers.php';

/**
 * AI記事カテゴリの自動作成
 */
function gap_aio_ensure_categories() {
    $categories = ['症状解説', '理論解説', 'お知らせ'];
    foreach ($categories as $cat) {
        if (!term_exists($cat, 'category')) {
            wp_insert_term($cat, 'category');
        }
    }
}
add_action('init', 'gap_aio_ensure_categories');

/**
 * 記事末尾に schema.org FAQPage JSON-LD を出力（custom field 'gap_faq' があれば）
 */
function gap_aio_inject_faq_jsonld() {
    if (!is_single()) return;
    $faq_raw = get_post_meta(get_the_ID(), 'gap_faq', true);
    if (!$faq_raw) return;
    $faq = json_decode($faq_raw, true);
    if (!is_array($faq)) return;

    $entities = [];
    foreach ($faq as $pair) {
        if (!is_array($pair) || count($pair) < 2) continue;
        $entities[] = [
            '@type' => 'Question',
            'name'  => $pair[0],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $pair[1]],
        ];
    }
    if (empty($entities)) return;

    $jsonld = [
        '@context' => 'https://schema.org',
        '@type'    => 'FAQPage',
        'mainEntity' => $entities,
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($jsonld, JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action('wp_footer', 'gap_aio_inject_faq_jsonld');
