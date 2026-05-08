<?php
/**
 * GAP-AIO ヘルパー関数
 */
if (!defined('ABSPATH')) exit;

/**
 * Customizer から特徴/症状/メニュー/スタッフ/声 をまとめて取得
 */
function gap_aio_get_features() {
    $items = [];
    for ($i = 1; $i <= 4; $i++) {
        $title = get_theme_mod("gap_feature{$i}_title", '');
        if (!$title) continue;
        $items[] = [
            'icon' => get_theme_mod("gap_feature{$i}_icon", ''),
            'title' => $title,
            'body' => get_theme_mod("gap_feature{$i}_body", ''),
        ];
    }
    return $items;
}

function gap_aio_get_symptoms() {
    $items = [];
    for ($i = 1; $i <= 8; $i++) {
        $name = get_theme_mod("gap_symptom{$i}_name", '');
        if (!$name) continue;
        $items[] = [
            'icon' => get_theme_mod("gap_symptom{$i}_icon", ''),
            'name' => $name,
            'desc' => get_theme_mod("gap_symptom{$i}_desc", ''),
        ];
    }
    return $items;
}

function gap_aio_get_menu_items() {
    $items = [];
    for ($i = 1; $i <= 5; $i++) {
        $name = get_theme_mod("gap_menu{$i}_name", '');
        if (!$name) continue;
        $items[] = [
            'name' => $name,
            'duration' => get_theme_mod("gap_menu{$i}_duration", ''),
            'price' => get_theme_mod("gap_menu{$i}_price", ''),
            'note' => get_theme_mod("gap_menu{$i}_note", ''),
        ];
    }
    return $items;
}

function gap_aio_get_staff() {
    $items = [];
    for ($i = 1; $i <= 2; $i++) {
        $name = get_theme_mod("gap_staff{$i}_name", '');
        if (!$name) continue;
        $items[] = [
            'name' => $name,
            'role' => get_theme_mod("gap_staff{$i}_role", ''),
            'bio' => get_theme_mod("gap_staff{$i}_bio", ''),
        ];
    }
    return $items;
}

function gap_aio_get_voices() {
    $items = [];
    for ($i = 1; $i <= 3; $i++) {
        $title = get_theme_mod("gap_voice{$i}_title", '');
        if (!$title) continue;
        $items[] = [
            'attr' => get_theme_mod("gap_voice{$i}_attr", ''),
            'title' => $title,
            'body' => get_theme_mod("gap_voice{$i}_body", ''),
        ];
    }
    return $items;
}

function gap_aio_get_specialties() {
    $raw = get_theme_mod('gap_specialties', '');
    if (!$raw) return [];
    return array_filter(array_map('trim', explode(',', $raw)));
}

/**
 * 院情報の一括取得
 */
function gap_aio_clinic($key, $default = '') {
    return get_theme_mod("gap_clinic_{$key}", $default);
}
