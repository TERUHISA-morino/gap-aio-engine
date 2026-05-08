<?php
/**
 * GAP-AIO Clinic Customizer 設定
 */
if (!defined('ABSPATH')) exit;

function gap_aio_customize_register($wp) {
    // ━━━ パネル: GAP-AIO 設定 ━━━
    $wp->add_panel('gap_aio_panel', [
        'title' => 'GAP-AIO 院設定',
        'priority' => 30,
        'description' => '院情報・スタイル・各セクションの内容を一元管理します。',
    ]);

    // ─── スタイル選択 ───
    $wp->add_section('gap_aio_style', [
        'title' => '① テーマスタイル',
        'panel' => 'gap_aio_panel',
        'priority' => 10,
    ]);
    $wp->add_setting('gap_theme_style', ['default' => 'medical-trust', 'sanitize_callback' => 'sanitize_key']);
    $wp->add_control('gap_theme_style', [
        'section' => 'gap_aio_style',
        'label' => 'スタイルを選択',
        'type' => 'select',
        'description' => 'プリセットを切り替えると配色・雰囲気が変わります。',
        'choices' => [
            'medical-trust' => '🌿 medical-trust（緑＋明朝・伝統的）',
            'clean-modern' => '💎 clean-modern（青＋ゴシック・モダン）',
            'warm-care' => '🌻 warm-care（オレンジ＋暖色・女性向け）',
            'premium-dark' => '🌑 premium-dark（黒＋金・高級感）',
        ],
    ]);

    // ─── 院基本情報 ───
    $wp->add_section('gap_aio_clinic', [
        'title' => '② 院の基本情報',
        'panel' => 'gap_aio_panel',
        'priority' => 20,
    ]);
    $fields = [
        ['gap_clinic_name', 'テキスト', '院名', '○○整体院'],
        ['gap_clinic_short_name', 'テキスト', 'ロゴ用短縮名', '○○整体'],
        ['gap_clinic_tagline', 'テキスト', 'キャッチコピー', '痛みを再発させない、根本改善を目指す整体院'],
        ['gap_clinic_sub_tagline', 'テキスト', 'サブコピー', 'GAP理論に基づく独自の物理アプローチ'],
        ['gap_clinic_phone', 'テキスト', '電話番号', '03-0000-0000'],
        ['gap_clinic_line_url', 'URL', 'LINE 予約URL', ''],
        ['gap_clinic_instagram', 'URL', 'Instagram URL', ''],
        ['gap_clinic_youtube', 'URL', 'YouTube URL', ''],
        ['gap_clinic_address', 'テキスト', '所在地（フル）', '東京都中央区銀座1-1-1 ビル名'],
        ['gap_clinic_station', 'テキスト', '最寄駅・徒歩', '○○駅 徒歩3分'],
        ['gap_clinic_hours', 'テキスト', '営業時間', '10:00 - 20:00'],
        ['gap_clinic_closed', 'テキスト', '定休日', '年中無休'],
    ];
    foreach ($fields as [$id, $type, $label, $default]) {
        $sanitize = ($type === 'URL') ? 'esc_url_raw' : 'sanitize_text_field';
        $wp->add_setting($id, ['default' => $default, 'sanitize_callback' => $sanitize]);
        $wp->add_control($id, [
            'section' => 'gap_aio_clinic',
            'label' => $label,
            'type' => $type === 'URL' ? 'url' : 'text',
        ]);
    }

    // ─── Hero ＆ 専門分野 ───
    $wp->add_section('gap_aio_hero', [
        'title' => '③ 専門分野（タグ）',
        'panel' => 'gap_aio_panel',
        'priority' => 30,
    ]);
    $wp->add_setting('gap_specialties', [
        'default' => '腰痛,肩こり,頭痛,自律神経',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp->add_control('gap_specialties', [
        'section' => 'gap_aio_hero',
        'label' => '専門分野（カンマ区切り）',
        'type' => 'text',
        'description' => '例: 腰痛,肩こり,頭痛,自律神経',
    ]);

    // ─── 4つの特徴 ───
    $wp->add_section('gap_aio_features', [
        'title' => '④ 当院の4つの特徴',
        'panel' => 'gap_aio_panel',
        'priority' => 40,
    ]);
    $defaults_features = [
        ['🏥', '臨床歴20年以上', '国家資格保有の施術者が、長年の臨床経験で培った技術で対応します。'],
        ['📜', '国家資格保有', '鍼灸師・あんまマッサージ指圧師の資格を持つ施術者が施術にあたります。'],
        ['🚉', '駅から徒歩3分', 'アクセス良好で通いやすい立地。お仕事帰りにも気軽にお立ち寄りいただけます。'],
        ['🌿', 'GAP理論実践院', '重力感知点と関節構造から、症状の根本原因にアプローチする独自理論を実践。'],
    ];
    for ($i = 1; $i <= 4; $i++) {
        $d = $defaults_features[$i-1] ?? ['', '', ''];
        foreach (['icon' => $d[0], 'title' => $d[1], 'body' => $d[2]] as $key => $default) {
            $id = "gap_feature{$i}_{$key}";
            $wp->add_setting($id, ['default' => $default, 'sanitize_callback' => 'sanitize_text_field']);
            $wp->add_control($id, [
                'section' => 'gap_aio_features',
                'label' => "特徴 {$i} ／ " . ['icon' => 'アイコン(絵文字)', 'title' => 'タイトル', 'body' => '説明'][$key],
                'type' => $key === 'body' ? 'textarea' : 'text',
            ]);
        }
    }

    // ─── ポリシー ───
    $wp->add_section('gap_aio_policy', [
        'title' => '⑤ 施術方針',
        'panel' => 'gap_aio_panel',
        'priority' => 50,
    ]);
    $wp->add_setting('gap_policy_title', [
        'default' => '「気持ちいい」では、根本的に治らない。',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp->add_control('gap_policy_title', ['section' => 'gap_aio_policy', 'label' => 'タイトル', 'type' => 'text']);
    $wp->add_setting('gap_policy_body', [
        'default' => 'GAP理論では、症状の本当の原因を「重力感知点（GAP①〜④）の機能不全」と「関節構造の歪み」から読み解きます。',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);
    $wp->add_control('gap_policy_body', ['section' => 'gap_aio_policy', 'label' => '本文', 'type' => 'textarea']);

    // ─── 症状リスト ───
    $wp->add_section('gap_aio_symptoms', [
        'title' => '⑥ 症状アイコングリッド（最大8件）',
        'panel' => 'gap_aio_panel',
        'priority' => 60,
    ]);
    $defaults_symptoms = [
        ['🩹', '腰痛', 'ぎっくり腰・慢性腰痛'],
        ['💪', '肩こり', '慢性肩こり・首こり'],
        ['🧠', '頭痛', '緊張性頭痛・偏頭痛'],
        ['🦴', '四十肩・五十肩', '肩関節周囲炎'],
        ['🦵', '膝痛', '変形性膝関節症'],
        ['💤', '自律神経', '不眠・倦怠感'],
        ['🤰', '産後の不調', '骨盤の歪み'],
        ['🚶', 'ふらつき', '歩行時の不安定感'],
    ];
    for ($i = 1; $i <= 8; $i++) {
        $d = $defaults_symptoms[$i-1] ?? ['', '', ''];
        foreach (['icon' => $d[0], 'name' => $d[1], 'desc' => $d[2]] as $key => $default) {
            $id = "gap_symptom{$i}_{$key}";
            $wp->add_setting($id, ['default' => $default, 'sanitize_callback' => 'sanitize_text_field']);
            $wp->add_control($id, [
                'section' => 'gap_aio_symptoms',
                'label' => "症状 {$i} ／ " . ['icon' => 'アイコン', 'name' => '名前', 'desc' => '補足'][$key],
                'type' => 'text',
            ]);
        }
    }

    // ─── 料金メニュー ───
    $wp->add_section('gap_aio_menu', [
        'title' => '⑦ 施術メニュー・料金（最大5件）',
        'panel' => 'gap_aio_panel',
        'priority' => 70,
    ]);
    $defaults_menu = [
        ['初回カウンセリング＋整体', '60分', '6,500円', '初回限定 2,980円'],
        ['GAP整体（標準）', '60分', '6,500円', ''],
        ['GAP整体（短時間）', '30分', '3,800円', ''],
        ['産後骨盤矯正', '60分', '7,500円', ''],
        ['回数券（10回）', '', '55,000円', '5,500円/回相当'],
    ];
    for ($i = 1; $i <= 5; $i++) {
        $d = $defaults_menu[$i-1] ?? ['', '', '', ''];
        foreach (['name' => $d[0], 'duration' => $d[1], 'price' => $d[2], 'note' => $d[3]] as $key => $default) {
            $id = "gap_menu{$i}_{$key}";
            $wp->add_setting($id, ['default' => $default, 'sanitize_callback' => 'sanitize_text_field']);
            $wp->add_control($id, [
                'section' => 'gap_aio_menu',
                'label' => "メニュー {$i} ／ " . ['name' => '名称', 'duration' => '時間', 'price' => '価格', 'note' => '注釈'][$key],
                'type' => 'text',
            ]);
        }
    }

    // ─── スタッフ ───
    $wp->add_section('gap_aio_staff', [
        'title' => '⑧ スタッフ（最大2名）',
        'panel' => 'gap_aio_panel',
        'priority' => 80,
    ]);
    for ($i = 1; $i <= 2; $i++) {
        foreach (['name' => '名前', 'role' => '役職・資格', 'bio' => '経歴・紹介文'] as $key => $label) {
            $id = "gap_staff{$i}_{$key}";
            $wp->add_setting($id, ['default' => '', 'sanitize_callback' => 'sanitize_textarea_field']);
            $wp->add_control($id, [
                'section' => 'gap_aio_staff',
                'label' => "スタッフ {$i} ／ {$label}",
                'type' => $key === 'bio' ? 'textarea' : 'text',
            ]);
        }
    }

    // ─── 患者の声 ───
    $wp->add_section('gap_aio_voices', [
        'title' => '⑨ 患者さまの声（最大3件）',
        'panel' => 'gap_aio_panel',
        'priority' => 90,
    ]);
    for ($i = 1; $i <= 3; $i++) {
        foreach (['attr' => '属性（例: 40代 女性 / 慢性腰痛）', 'title' => '見出し', 'body' => '本文'] as $key => $label) {
            $id = "gap_voice{$i}_{$key}";
            $wp->add_setting($id, ['default' => '', 'sanitize_callback' => 'sanitize_textarea_field']);
            $wp->add_control($id, [
                'section' => 'gap_aio_voices',
                'label' => "声 {$i} ／ {$label}",
                'type' => $key === 'body' ? 'textarea' : 'text',
            ]);
        }
    }
}
add_action('customize_register', 'gap_aio_customize_register');
